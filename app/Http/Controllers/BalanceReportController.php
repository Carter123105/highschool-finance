<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BalanceReportController extends Controller
{
    /*
    |-----------------------------------------
    | BALANCE REPORT INDEX
    |-----------------------------------------
    */
    public function index(Request $request)
    {
        $yearId = session('academic_year_id');

        $classId = $request->input('class_id');
        $search = $request->input('search');
        $statusFilter = $request->input('status');
        $studentType = $request->input('student_type');

        $classes = SchoolClass::orderBy('name')->get();

        // Build student query with filters
        $studentsQuery = Student::with(['schoolClass', 'section'])
            ->when($classId, fn ($q) => $q->where('class_id', $classId))
            ->when($studentType, fn ($q) => $q->where('student_type', $studentType))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%")
                       ->orWhere('student_id', 'like', "%{$search}%");
                });
            });

        $students = $studentsQuery->paginate(50)->withQueryString();

        // Fetch ALL students in selected class for summary cards
        $allClassStudentsQuery = Student::with(['schoolClass', 'section'])
            ->when($classId, fn ($q) => $q->where('class_id', $classId))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%")
                       ->orWhere('student_id', 'like', "%{$search}%");
                });
            });

        $allClassStudents = $allClassStudentsQuery->get();

        // ============================================================
        // FETCH INVOICES (class-level, keyed for lookup)
        // ============================================================
        $allStudentIds = $allClassStudents->pluck('id');

        $invoices = Invoice::query()
            ->when($yearId, fn ($q) => $q->where('academic_year_id', $yearId))
            ->when($classId, fn ($q) => $q->where('class_id', $classId))
            ->when($studentType, fn ($q) => $q->where('student_type', $studentType))
            ->get();

        // Key invoices: exact match AND class-wide fallback (null section)
        $invoiceLookup = [];
        foreach ($invoices as $invoice) {
            $exactKey = $invoice->class_id . '-' . ($invoice->section_id ?? 'null') . '-' . $invoice->student_type;
            $invoiceLookup[$exactKey] = $invoice;

            $classWideKey = $invoice->class_id . '-null-' . $invoice->student_type;
            if (!isset($invoiceLookup[$classWideKey])) {
                $invoiceLookup[$classWideKey] = $invoice;
            }
        }

        // ============================================================
        // FIX: FETCH PAYMENTS PER STUDENT (not from invoice paid_amount)
        // ============================================================
        $payments = Payment::whereIn('student_id', $allStudentIds)
            ->select('student_id', DB::raw('SUM(amount_paid) as total_paid'))
            ->groupBy('student_id')
            ->get()
            ->keyBy('student_id');

        $resolveInvoice = function ($student) use ($invoiceLookup) {
            $exactKey = $student->class_id . '-' . ($student->section_id ?? 'null') . '-' . $student->student_type;
            if (isset($invoiceLookup[$exactKey])) {
                return $invoiceLookup[$exactKey];
            }
            $fallbackKey = $student->class_id . '-null-' . $student->student_type;
            return $invoiceLookup[$fallbackKey] ?? null;
        };

        // ============================================================
        // BUILD REPORTS: Per-student expected (from invoice) vs paid (individual)
        // ============================================================
        $buildReport = function ($studentList) use ($resolveInvoice, $payments, $statusFilter) {
            $reports = [];
            foreach ($studentList as $student) {
                $invoice = $resolveInvoice($student);

                if (!$invoice) {
                    if ($statusFilter && $statusFilter !== 'No Invoice') {
                        continue;
                    }
                    $reports[] = [
                        'student' => $student,
                        'invoice' => null,
                        'expected' => 0,
                        'paid' => 0,
                        'balance' => 0,
                        'status' => 'No Invoice',
                    ];
                    continue;
                }

                // Expected = class invoice total (same for all students in class/type)
                $expected = (float) $invoice->total_amount;

                // PAID = ONLY this student's individual payments
                $paid = (float) ($payments->get($student->id)?->total_paid ?? 0);

                $balance = max(0, $expected - $paid);

                $status = match (true) {
                    $paid <= 0 => 'Not Paid',
                    $paid >= $expected => 'Fully Paid',
                    default => 'Partially Paid',
                };

                if ($statusFilter && $statusFilter !== $status) {
                    continue;
                }

                $reports[] = [
                    'student' => $student,
                    'invoice' => $invoice,
                    'expected' => $expected,
                    'paid' => $paid,
                    'balance' => $balance,
                    'status' => $status,
                ];
            }
            return $reports;
        };

        // Build reports for TABLE (paginated)
        $reports = $buildReport($students);

        // Build reports for ALL class students (summary cards)
        $allReports = $buildReport($allClassStudents);

        // Grand totals from TABLE
        $grandExpected = 0;
        $grandPaid = 0;
        $grandBalance = 0;
        foreach ($reports as $report) {
            $grandExpected += $report['expected'];
            $grandPaid += $report['paid'];
            $grandBalance += $report['balance'];
        }

        // Per-type totals from ALL students
        $oldExpected = 0; $oldPaid = 0; $oldBalance = 0; $oldCount = 0;
        $newExpected = 0; $newPaid = 0; $newBalance = 0; $newCount = 0;

        foreach ($allReports as $report) {
            $student = $report['student'];
            if ($student->student_type === 'Old') {
                $oldExpected += $report['expected'];
                $oldPaid += $report['paid'];
                $oldBalance += $report['balance'];
                $oldCount++;
            } elseif ($student->student_type === 'New') {
                $newExpected += $report['expected'];
                $newPaid += $report['paid'];
                $newBalance += $report['balance'];
                $newCount++;
            }
        }

        return view('finance.balance', compact(
            'reports',
            'classes',
            'grandExpected',
            'grandPaid',
            'grandBalance',
            'oldExpected',
            'oldPaid',
            'oldBalance',
            'oldCount',
            'newExpected',
            'newPaid',
            'newBalance',
            'newCount',
            'classId',
            'search',
            'statusFilter',
            'studentType',
            'students'
        ));
    }

    /*
    |-----------------------------------------
    | EXPORT BALANCE REPORT TO CSV
    |-----------------------------------------
    */
    public function export(Request $request): StreamedResponse
    {
        $yearId = session('academic_year_id');
        $classId = $request->input('class_id');
        $search = $request->input('search');
        $statusFilter = $request->input('status');
        $studentType = $request->input('student_type');

        $studentsQuery = Student::with(['schoolClass', 'section'])
            ->when($classId, fn ($q) => $q->where('class_id', $classId))
            ->when($studentType, fn ($q) => $q->where('student_type', $studentType))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%")
                       ->orWhere('student_id', 'like', "%{$search}%");
                });
            });

        $students = $studentsQuery->get();

        // Fetch invoices
        $invoices = Invoice::query()
            ->when($yearId, fn ($q) => $q->where('academic_year_id', $yearId))
            ->when($classId, fn ($q) => $q->where('class_id', $classId))
            ->when($studentType, fn ($q) => $q->where('student_type', $studentType))
            ->get();

        $invoiceLookup = [];
        foreach ($invoices as $invoice) {
            $exactKey = $invoice->class_id . '-' . ($invoice->section_id ?? 'null') . '-' . $invoice->student_type;
            $invoiceLookup[$exactKey] = $invoice;

            $classWideKey = $invoice->class_id . '-null-' . $invoice->student_type;
            if (!isset($invoiceLookup[$classWideKey])) {
                $invoiceLookup[$classWideKey] = $invoice;
            }
        }

        // FIX: Payments per student (not grouped by invoice_id)
        $studentIds = $students->pluck('id');
        $payments = Payment::whereIn('student_id', $studentIds)
            ->select('student_id', DB::raw('SUM(amount_paid) as total_paid'))
            ->groupBy('student_id')
            ->get()
            ->keyBy('student_id');

        $resolveInvoice = function ($student) use ($invoiceLookup) {
            $exactKey = $student->class_id . '-' . ($student->section_id ?? 'null') . '-' . $student->student_type;
            if (isset($invoiceLookup[$exactKey])) {
                return $invoiceLookup[$exactKey];
            }
            $fallbackKey = $student->class_id . '-null-' . $student->student_type;
            return $invoiceLookup[$fallbackKey] ?? null;
        };

        $headers = [
            'Student ID', 'Name', 'Type', 'Class', 'Section',
            'Invoice No', 'Expected', 'Paid', 'Balance', 'Status'
        ];

        $callback = function () use ($students, $resolveInvoice, $payments, $statusFilter) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $headers);

            foreach ($students as $student) {
                $invoice = $resolveInvoice($student);

                if (!$invoice) {
                    if ($statusFilter && $statusFilter !== 'No Invoice') continue;

                    fputcsv($file, [
                        $student->student_id,
                        $student->first_name . ' ' . $student->last_name,
                        $student->student_type,
                        $student->schoolClass?->name ?? 'N/A',
                        $student->section?->name ?? 'N/A',
                        'N/A', '0.00', '0.00', '0.00', 'No Invoice'
                    ]);
                    continue;
                }

                $expected = (float) $invoice->total_amount;
                $paid = (float) ($payments->get($student->id)?->total_paid ?? 0);
                $balance = max(0, $expected - $paid);

                $status = match (true) {
                    $paid <= 0 => 'Not Paid',
                    $paid >= $expected => 'Fully Paid',
                    default => 'Partially Paid',
                };

                if ($statusFilter && $statusFilter !== $status) continue;

                fputcsv($file, [
                    $student->student_id,
                    $student->first_name . ' ' . $student->last_name,
                    $student->student_type,
                    $student->schoolClass?->name ?? 'N/A',
                    $student->section?->name ?? 'N/A',
                    $invoice->invoice_no ?? 'N/A',
                    number_format($expected, 2),
                    number_format($paid, 2),
                    number_format($balance, 2),
                    $status
                ]);
            }

            fclose($file);
        };

        $filename = 'balance_report_' . now()->format('Y-m-d_His') . '.csv';

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}