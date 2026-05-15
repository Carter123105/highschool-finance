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
        $studentType = $request->input('student_type'); // 'Old' or 'New'

        // Get all classes for filter dropdown
        $classes = SchoolClass::orderBy('name')->get();

        // Build student query with filters (this controls the TABLE display)
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

        // Paginate for table display
        $students = $studentsQuery->paginate(50)->withQueryString();

        // Fetch ALL students in the selected class for summary cards (ignore student_type filter)
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

        // Fetch invoices for ALL students in the class (both Old and New)
        $allStudentData = $allClassStudents->mapWithKeys(function ($student) {
            return [$student->id => [
                'class_id' => $student->class_id,
                'section_id' => $student->section_id,
                'student_type' => $student->student_type,
            ]];
        });

        // Get unique class+section+type combinations for invoice fetching
        $invoiceConditions = [];
        $seenCombinations = [];
        foreach ($allStudentData as $data) {
            $key = $data['class_id'] . '-' . $data['section_id'] . '-' . $data['student_type'];
            if (!isset($seenCombinations[$key])) {
                $seenCombinations[$key] = true;
                $invoiceConditions[] = [
                    'class_id' => $data['class_id'],
                    'section_id' => $data['section_id'],
                    'student_type' => $data['student_type'],
                ];
            }
        }

        $invoices = collect();
        if (!empty($invoiceConditions)) {
            $invoiceQuery = Invoice::query();
            $invoiceQuery->where(function ($q) use ($invoiceConditions) {
                foreach ($invoiceConditions as $index => $condition) {
                    $method = $index === 0 ? 'where' : 'orWhere';
                    $q->{$method}(function ($subQ) use ($condition) {
                        $subQ->where('class_id', $condition['class_id'])
                             ->where('section_id', $condition['section_id'])
                             ->where('student_type', $condition['student_type']);
                    });
                }
            });

            $invoices = $invoiceQuery
                ->when($yearId, fn ($q) => $q->where('academic_year_id', $yearId))
                ->get()
                ->keyBy(function ($invoice) {
                    return $invoice->class_id . '-' . $invoice->section_id . '-' . $invoice->student_type;
                });
        }

        // Fetch payments for ALL students in the class
        $allStudentIds = $allClassStudents->pluck('id');
        $payments = Payment::whereIn('student_id', $allStudentIds)
            ->select('student_id', 'invoice_id', DB::raw('SUM(amount_paid) as total_paid'))
            ->groupBy('student_id', 'invoice_id')
            ->get();

        $paymentLookup = [];
        foreach ($payments as $payment) {
            $paymentLookup[$payment->student_id][$payment->invoice_id] = (float) $payment->total_paid;
        }

        // Helper function to build reports
        $buildReport = function ($studentList) use ($invoices, $paymentLookup, $statusFilter, $yearId) {
            $reports = [];
            foreach ($studentList as $student) {
                $invoiceKey = $student->class_id . '-' . $student->section_id . '-' . $student->student_type;
                $invoice = $invoices->get($invoiceKey);

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

                $expected = (float) $invoice->total_amount;
                $paidFromPayments = (float) ($paymentLookup[$student->id][$invoice->id] ?? 0);
                $paid = max((float) $invoice->paid_amount, $paidFromPayments);
                $balance = max(0, $expected - $paid);

                $status = match (true) {
                    $paid == 0 => 'Not Paid',
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

        // Build reports for TABLE (filtered by student_type)
        $reports = $buildReport($students);

        // Build reports for ALL students in class (for summary cards)
        $allReports = $buildReport($allClassStudents);

        // Calculate grand totals from TABLE data
        $grandExpected = 0;
        $grandPaid = 0;
        $grandBalance = 0;
        foreach ($reports as $report) {
            $grandExpected += $report['expected'];
            $grandPaid += $report['paid'];
            $grandBalance += $report['balance'];
        }

        // Calculate per-type totals from ALL class students (for summary cards)
        $oldExpected = 0;
        $oldPaid = 0;
        $oldBalance = 0;
        $oldCount = 0;
        $newExpected = 0;
        $newPaid = 0;
        $newBalance = 0;
        $newCount = 0;

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

        // Build student query with all filters
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
        $studentData = $students->mapWithKeys(function ($student) {
            return [$student->id => [
                'class_id' => $student->class_id,
                'section_id' => $student->section_id,
                'student_type' => $student->student_type,
            ]];
        });

        $invoiceConditions = [];
        $seenCombinations = [];
        foreach ($studentData as $data) {
            $key = $data['class_id'] . '-' . $data['section_id'] . '-' . $data['student_type'];
            if (!isset($seenCombinations[$key])) {
                $seenCombinations[$key] = true;
                $invoiceConditions[] = [
                    'class_id' => $data['class_id'],
                    'section_id' => $data['section_id'],
                    'student_type' => $data['student_type'],
                ];
            }
        }

        $invoices = collect();
        if (!empty($invoiceConditions)) {
            $invoiceQuery = Invoice::query();
            $invoiceQuery->where(function ($q) use ($invoiceConditions) {
                foreach ($invoiceConditions as $index => $condition) {
                    $method = $index === 0 ? 'where' : 'orWhere';
                    $q->{$method}(function ($subQ) use ($condition) {
                        $subQ->where('class_id', $condition['class_id'])
                             ->where('section_id', $condition['section_id'])
                             ->where('student_type', $condition['student_type']);
                    });
                }
            });

            $invoices = $invoiceQuery
                ->when($yearId, fn ($q) => $q->where('academic_year_id', $yearId))
                ->get()
                ->keyBy(function ($invoice) {
                    return $invoice->class_id . '-' . $invoice->section_id . '-' . $invoice->student_type;
                });
        }

        // Payment lookup
        $studentIds = $students->pluck('id');
        $payments = Payment::whereIn('student_id', $studentIds)
            ->select('student_id', 'invoice_id', DB::raw('SUM(amount_paid) as total_paid'))
            ->groupBy('student_id', 'invoice_id')
            ->get();

        $paymentLookup = [];
        foreach ($payments as $payment) {
            $paymentLookup[$payment->student_id][$payment->invoice_id] = (float) $payment->total_paid;
        }

        $headers = [
            'Student ID',
            'Name',
            'Type',
            'Class',
            'Section',
            'Invoice No',
            'Expected',
            'Paid',
            'Balance',
            'Status'
        ];

        $callback = function () use ($students, $invoices, $paymentLookup, $statusFilter) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $headers);

            foreach ($students as $student) {
                $invoiceKey = $student->class_id . '-' . $student->section_id . '-' . $student->student_type;
                $invoice = $invoices->get($invoiceKey);

                if (!$invoice) {
                    if ($statusFilter && $statusFilter !== 'No Invoice') continue;

                    fputcsv($file, [
                        $student->student_id,
                        $student->first_name . ' ' . $student->last_name,
                        $student->student_type,
                        $student->schoolClass?->name ?? 'N/A',
                        $student->section?->name ?? 'N/A',
                        'N/A',
                        '0.00',
                        '0.00',
                        '0.00',
                        'No Invoice'
                    ]);
                    continue;
                }

                $expected = (float) $invoice->total_amount;
                $paidFromPayments = (float) ($paymentLookup[$student->id][$invoice->id] ?? 0);
                $paid = max((float) $invoice->paid_amount, $paidFromPayments);
                $balance = max(0, $expected - $paid);

                $status = match (true) {
                    $paid == 0 => 'Not Paid',
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