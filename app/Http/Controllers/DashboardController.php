<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\SchoolClass;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Auth;
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
    | ADMIN DASHBOARD
    |--------------------------------------------------
    */
    public function admin()
    {
        $yearId = session('academic_year_id');

        $academicYears = AcademicYear::orderBy('id', 'desc')->get();

        $totalStudents = Student::count();
        $activeStudents = Student::where('status', 'Active')->count();

        $invoiceQuery = Invoice::when($yearId, fn($q) =>
            $q->where('academic_year_id', $yearId)
        );

        $totalInvoices = (clone $invoiceQuery)->count();
        $paidInvoices = (clone $invoiceQuery)->where('status', 'Paid')->count();
        $unpaidInvoices = (clone $invoiceQuery)->where('status', 'Unpaid')->count();
        $partialInvoices = (clone $invoiceQuery)->where('status', 'Partial')->count();

        $totalInvoiceAmount = (clone $invoiceQuery)->sum('total_amount');

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

        $totalExpenses = Expense::when($yearId, fn($q) =>
            $q->where('academic_year_id', $yearId)
        )->sum('amount');

        $totalRevenue = $totalPayments;

        $outstandingBalance = max(0, $totalInvoiceAmount - $totalRevenue);

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
            'outstandingBalance'
        ));
    }

    /*
    |--------------------------------------------------
    | ACCOUNTANT DASHBOARD (FULL FIXED)
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

        /* ================= INVOICES ================= */
        $invoiceQuery = Invoice::when($yearId, fn($q) =>
            $q->where('academic_year_id', $yearId)
        );

        $totalExpected = (clone $invoiceQuery)->sum('total_amount');

        $outstandingBalance = max(0, $totalExpected - $totalRevenue);

        /* ================= STUDENT ANALYSIS ================= */
        $students = Student::with(['invoices.payments'])
            ->when($yearId, fn($q) =>
                $q->whereHas('invoices', fn($i) =>
                    $i->where('academic_year_id', $yearId)
                )
            )
            ->get();

        $fullyPaidStudents = 0;
        $studentsOwing = 0;

        foreach ($students as $student) {

            $invoices = $student->invoices->where('academic_year_id', $yearId);

            $expected = $invoices->sum('total_amount');

            $paid = 0;
            foreach ($invoices as $invoice) {
                $paid += $invoice->payments->sum('amount_paid');
            }

            if ($expected > 0 && $paid >= $expected) {
                $fullyPaidStudents++;
            } elseif ($expected > $paid) {
                $studentsOwing++;
            }
        }

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
}