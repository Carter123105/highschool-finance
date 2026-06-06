<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\SchoolClass;
use App\Models\AcademicYear;
use App\Models\Section;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /*
    |--------------------------------------------------
    | LOGIN REDIRECT
    |--------------------------------------------------
    */
    public function index()
    {
        $user = Auth::user();

        if (!$user) abort(403);

        if ($user->is_blocked ?? false) {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            abort(403, 'Your account has been blocked.');
        }

        return match (true) {
            $user->hasRole('Admin') => redirect()->route('admin.dashboard'),
            $user->hasRole('Accountant') => redirect()->route('accountant.dashboard'),
            $user->hasRole('Registrar') => redirect()->route('registrar.dashboard'),
            default => abort(403, 'Unauthorized.'),
        };
    }

    /*
    |--------------------------------------------------
    | ADMIN DASHBOARD - FIXED
    |--------------------------------------------------
    */
    public function admin()
    {
        $yearId = session('academic_year_id');

        $academicYears = AcademicYear::orderBy('id', 'desc')->get();

        $totalStudents = Student::count();
        $activeStudents = Student::where('status', 'Active')->count();

        /* ================= INVOICES ================= */
        $invoiceQuery = Invoice::when($yearId, fn($q) =>
            $q->where('academic_year_id', $yearId)
        );

        $totalInvoices = (clone $invoiceQuery)->count();
        $paidInvoices = (clone $invoiceQuery)->where('status', 'Paid')->count();
        $unpaidInvoices = (clone $invoiceQuery)->where('status', 'Unpaid')->count();
        $partialInvoices = (clone $invoiceQuery)->where('status', 'Partial')->count();

        /* ================= PAYMENTS ================= */
        $paymentQuery = Payment::whereHas('invoice', fn($q) =>
            $q->when($yearId, fn($sub) =>
                $sub->where('academic_year_id', $yearId)
            )
        );

        $totalPayments = (clone $paymentQuery)->sum('amount_paid');

        $todayPayments = (clone $paymentQuery)
            ->whereDate('payment_date', today())
            ->sum('amount_paid');

        $thisMonthPayments = (clone $paymentQuery)
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount_paid');

        /* ================= EXPENSES ================= */
        $totalExpenses = Expense::when($yearId, fn($q) =>
            $q->where('academic_year_id', $yearId)
        )->sum('amount');

        /* ================= REVENUE ================= */
        $totalRevenue = $totalPayments;

        /* ================= OUTSTANDING BALANCE - FIXED ================= */
        $outstandingBalance = $this->calculateOutstandingBalance($yearId);

        /* ================= STUDENT PAYMENT STATUS - FIXED ================= */
        $studentStats = $this->calculateStudentPaymentStats($yearId);
        
        $fullyPaidStudents = $studentStats['fully_paid'];
        $partiallyPaidStudents = $studentStats['partially_paid'];
        $neverPaidStudents = $studentStats['never_paid'];
        $studentsOwing = $partiallyPaidStudents + $neverPaidStudents; // Total who still owe money

        return view('Admin.admin', compact(
            'totalStudents',
            'activeStudents',
            'academicYears',
            'totalInvoices',
            'paidInvoices',
            'unpaidInvoices',
            'partialInvoices',
            'totalPayments',
            'todayPayments',
            'thisMonthPayments',
            'totalRevenue',
            'totalExpenses',
            'outstandingBalance',
            'fullyPaidStudents',
            'partiallyPaidStudents',
            'neverPaidStudents',
            'studentsOwing'
        ));
    }

    /*
    |--------------------------------------------------
    | ACCOUNTANT DASHBOARD - FULLY FIXED
    |--------------------------------------------------
    */
    public function accountant()
    {
        $yearId = session('academic_year_id');

        /* ================= PAYMENTS ================= */
        $paymentQuery = Payment::whereHas('invoice', fn($q) =>
            $q->when($yearId, fn($sub) =>
                $sub->where('academic_year_id', $yearId)
            )
        );

        $totalRevenue = (clone $paymentQuery)->sum('amount_paid');

        $todayRevenue = (clone $paymentQuery)
            ->whereDate('payment_date', today())
            ->sum('amount_paid');

        $monthlyRevenue = (clone $paymentQuery)
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount_paid');

        /* ================= OUTSTANDING BALANCE - FIXED ================= */
        $outstandingBalance = $this->calculateOutstandingBalance($yearId);

        /* ================= TOTAL EXPECTED ================= */
        $totalExpected = $this->calculateTotalExpected($yearId);

        /* ================= STUDENT PAYMENT STATUS - FIXED ================= */
        $studentStats = $this->calculateStudentPaymentStats($yearId);
        
        $fullyPaidStudents = $studentStats['fully_paid'];
        $partiallyPaidStudents = $studentStats['partially_paid'];
        $neverPaidStudents = $studentStats['never_paid'];
        $studentsOwing = $partiallyPaidStudents + $neverPaidStudents;

        /* ================= RECENT PAYMENTS ================= */
        $recentPayments = Payment::with(['student', 'invoice'])
            ->whereHas('invoice', fn($q) =>
                $q->when($yearId, fn($sub) =>
                    $sub->where('academic_year_id', $yearId)
                )
            )
            ->latest()
            ->take(10)
            ->get();

        return view('Accountant.accountant', compact(
            'totalRevenue',
            'todayRevenue',
            'monthlyRevenue',
            'totalExpected',
            'outstandingBalance',
            'recentPayments',
            'fullyPaidStudents',
            'partiallyPaidStudents',
            'neverPaidStudents',
            'studentsOwing'
        ));
    }

    /*
    |--------------------------------------------------
    | REGISTRAR DASHBOARD
    |--------------------------------------------------
    */
    public function registrar()
    {
        return view('Registrar.registrar', [
            'totalStudents' => Student::count(),
            'newStudents' => Student::where('student_type', 'New')->count(),
            'oldStudents' => Student::where('student_type', 'Old')->count(),
            'activeStudents' => Student::where('status', 'Active')->count(),
            'graduatedStudents' => Student::where('status', 'Graduated')->count(),
            'totalClasses' => SchoolClass::count(),
            'recentStudents' => Student::latest()->take(10)->get(),
        ]);
    }

    /*
    |--------------------------------------------------
    | FIXED: Calculate Outstanding Balance Per Student
    |--------------------------------------------------
    */
    private function calculateOutstandingBalance($yearId = null)
    {
        $totalOutstanding = 0;

        /* === CLASS INVOICES (student_id = NULL) === */
        $classInvoiceQuery = Invoice::whereNull('student_id')
            ->whereIn('status', ['Unpaid', 'Partial']);

        if ($yearId) {
            $classInvoiceQuery->where('academic_year_id', $yearId);
        }

        $classInvoices = $classInvoiceQuery->get();

        foreach ($classInvoices as $invoice) {
            $studentQuery = Student::where('class_id', $invoice->class_id);

            if ($invoice->student_type) {
                $studentQuery->where('student_type', $invoice->student_type);
            }

            if ($invoice->section_id) {
                $studentQuery->where('section_id', $invoice->section_id);
            }

            $students = $studentQuery->get();

            foreach ($students as $student) {
                $paid = Payment::where('invoice_id', $invoice->id)
                    ->where('student_id', $student->id)
                    ->sum('amount_paid');

                $remaining = max(0, $invoice->total_amount - $paid);
                $totalOutstanding += $remaining;
            }
        }

        /* === INDIVIDUAL INVOICES (student_id != NULL) === */
        $individualInvoiceQuery = Invoice::whereNotNull('student_id')
            ->whereIn('status', ['Unpaid', 'Partial']);

        if ($yearId) {
            $individualInvoiceQuery->where('academic_year_id', $yearId);
        }

        $individualInvoices = $individualInvoiceQuery->get();

        foreach ($individualInvoices as $invoice) {
            $paid = Payment::where('invoice_id', $invoice->id)->sum('amount_paid');
            $remaining = max(0, $invoice->total_amount - $paid);
            $totalOutstanding += $remaining;
        }

        return $totalOutstanding;
    }

    /*
    |--------------------------------------------------
    | FIXED: Calculate Total Expected Revenue
    |--------------------------------------------------
    */
    private function calculateTotalExpected($yearId = null)
    {
        $totalExpected = 0;

        /* === CLASS INVOICES === */
        $classInvoiceQuery = Invoice::whereNull('student_id');

        if ($yearId) {
            $classInvoiceQuery->where('academic_year_id', $yearId);
        }

        $classInvoices = $classInvoiceQuery->get();

        foreach ($classInvoices as $invoice) {
            $studentQuery = Student::where('class_id', $invoice->class_id);

            if ($invoice->student_type) {
                $studentQuery->where('student_type', $invoice->student_type);
            }

            if ($invoice->section_id) {
                $studentQuery->where('section_id', $invoice->section_id);
            }

            $studentCount = $studentQuery->count();
            $totalExpected += ($invoice->total_amount * $studentCount);
        }

        /* === INDIVIDUAL INVOICES === */
        $individualQuery = Invoice::whereNotNull('student_id');

        if ($yearId) {
            $individualQuery->where('academic_year_id', $yearId);
        }

        $totalExpected += $individualQuery->sum('total_amount');

        return $totalExpected;
    }

    /*
    |--------------------------------------------------
    | FIXED: Calculate Student Payment Status Counts
    |--------------------------------------------------
    */
    private function calculateStudentPaymentStats($yearId = null)
    {
        $fullyPaid = 0;
        $partiallyPaid = 0;
        $neverPaid = 0;

        // Get all students
        $students = Student::all();

        foreach ($students as $student) {
            // Find all invoices applicable to this student
            $applicableInvoices = $this->getApplicableInvoices($student, $yearId);

            if ($applicableInvoices->isEmpty()) {
                continue; // No invoices for this student
            }

            $totalExpected = 0;
            $totalPaid = 0;

            foreach ($applicableInvoices as $invoice) {
                $totalExpected += $invoice->total_amount;

                $paid = Payment::where('invoice_id', $invoice->id)
                    ->where('student_id', $student->id)
                    ->sum('amount_paid');

                $totalPaid += $paid;
            }

            if ($totalExpected <= 0) {
                continue;
            }

            if ($totalPaid >= $totalExpected) {
                $fullyPaid++;
            } elseif ($totalPaid > 0) {
                $partiallyPaid++;
            } else {
                $neverPaid++;
            }
        }

        return [
            'fully_paid' => $fullyPaid,
            'partially_paid' => $partiallyPaid,
            'never_paid' => $neverPaid,
        ];
    }

    /*
    |--------------------------------------------------
    | HELPER: Get invoices applicable to a student
    |--------------------------------------------------
    */
    private function getApplicableInvoices($student, $yearId = null)
    {
        $query = Invoice::query();

        if ($yearId) {
            $query->where('academic_year_id', $yearId);
        }

        return $query->where(function ($q) use ($student) {
            // Individual invoices for this student
            $q->where('student_id', $student->id);

            // OR class invoices matching student's class/type/section
            $q->orWhere(function ($sub) use ($student) {
                $sub->whereNull('student_id')
                    ->where('class_id', $student->class_id);

                // Only include if student_type matches (if specified on invoice)
                $sub->where(function ($typeQ) use ($student) {
                    $typeQ->whereNull('student_type')
                          ->orWhere('student_type', $student->student_type);
                });

                // Only include if section matches (if specified on invoice)
                $sub->where(function ($secQ) use ($student) {
                    $secQ->whereNull('section_id')
                          ->orWhere('section_id', $student->section_id);
                });
            });
        })->get();
    }
}