<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SUMMARY (DASHBOARD) - FULLY FIXED
    |--------------------------------------------------------------------------
    */
    public function summary(Request $request)
    {
        $studentType = $request->student_type;
        $yearId = session('academic_year_id');

        // ============================================
        // TOTAL INCOME: All payments received
        // ============================================
        $totalIncome = Payment::query()
            ->when($studentType, function ($q) use ($studentType) {
                $q->whereHas('student', fn($q2) => $q2->where('student_type', $studentType));
            })
            ->when($yearId, function ($q) use ($yearId) {
                $q->whereHas('invoice', fn($q2) => $q2->where('academic_year_id', $yearId));
            })
            ->sum('amount_paid');

        // ============================================
        // TOTAL EXPENSES
        // ============================================
        $totalExpenses = Expense::query()
            ->sum('amount');

        // ============================================
        // TOTAL EXPECTED: Sum of all invoice totals
        // ============================================
        $totalExpected = Invoice::query()
            ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
            ->sum('total_amount');

        // ============================================
        // OUTSTANDING FEES: Sum of all individual student balances
        // ============================================
        $totalOutstanding = $this->calculateStudentOutstanding($studentType, $yearId);

        // ============================================
        // OVERPAYMENT: If any student paid more than expected
        // ============================================
        $totalOverpayment = $this->calculateStudentOverpayment($studentType, $yearId);

        // ============================================
        // DAILY TRANSACTIONS
        // ============================================
        $todayPayments = Payment::with(['student', 'invoice'])
            ->whereDate('payment_date', today())
            ->when($yearId, fn($q) => $q->whereHas('invoice', fn($q2) => $q2->where('academic_year_id', $yearId)))
            ->latest()
            ->get();

        $todayExpenses = Expense::whereDate('expense_date', today())
            ->latest()
            ->get();

        $todayTotalPayments = $todayPayments->sum('amount_paid');
        $todayTotalExpenses = $todayExpenses->sum('amount');
        $todayCount = $todayPayments->count() + $todayExpenses->count();

        // ============================================
        // NET PROFIT
        // ============================================
        $netProfit = $totalIncome - $totalExpenses;

        // ============================================
        // STUDENT COUNT
        // ============================================
        $totalStudents = Student::query()
            ->when($studentType, fn($q) => $q->where('student_type', $studentType))
            ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
            ->count();

        return view('finance.summary', [
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'totalExpected' => $totalExpected,
            'totalOutstanding' => $totalOutstanding,
            'totalOverpayment' => $totalOverpayment,
            'netProfit' => $netProfit,
            'totalStudents' => $totalStudents,
            'studentType' => $studentType,
            'todayPayments' => $todayPayments,
            'todayExpenses' => $todayExpenses,
            'todayTotalPayments' => $todayTotalPayments,
            'todayTotalExpenses' => $todayTotalExpenses,
            'todayCount' => $todayCount,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CALCULATE OUTSTANDING PER STUDENT
    |--------------------------------------------------------------------------
    */
    private function calculateStudentOutstanding(?string $studentType, ?int $yearId): float
    {
        $outstanding = 0;

        // Get all students with their class/section/type
        $students = Student::query()
            ->with(['schoolClass', 'section'])
            ->when($studentType, fn($q) => $q->where('student_type', $studentType))
            ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
            ->get();

        // Get all relevant invoices keyed for lookup
        $invoiceQuery = Invoice::query()
            ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId));

        if ($studentType) {
            $invoiceQuery->where('student_type', $studentType);
        }

        $invoices = $invoiceQuery->get();

        // Build invoice lookup: class_id-section_id-student_type => invoice
        $invoiceLookup = [];
        foreach ($invoices as $invoice) {
            $key = $invoice->class_id . '-' . ($invoice->section_id ?? 'null') . '-' . $invoice->student_type;
            $invoiceLookup[$key] = $invoice;

            // Also add fallback without section
            $fallbackKey = $invoice->class_id . '-null-' . $invoice->student_type;
            if (!isset($invoiceLookup[$fallbackKey])) {
                $invoiceLookup[$fallbackKey] = $invoice;
            }
        }

        // Get all payments per student
        $studentIds = $students->pluck('id');
        $payments = Payment::whereIn('student_id', $studentIds)
            ->when($yearId, fn($q) => $q->whereHas('invoice', fn($q2) => $q2->where('academic_year_id', $yearId)))
            ->select('student_id', DB::raw('SUM(amount_paid) as total_paid'))
            ->groupBy('student_id')
            ->get()
            ->keyBy('student_id');

        foreach ($students as $student) {
            // Find matching invoice
            $exactKey = $student->class_id . '-' . ($student->section_id ?? 'null') . '-' . $student->student_type;
            $fallbackKey = $student->class_id . '-null-' . $student->student_type;

            $invoice = $invoiceLookup[$exactKey] ?? $invoiceLookup[$fallbackKey] ?? null;

            if (!$invoice) {
                continue; // No invoice for this student
            }

            $expected = (float) $invoice->total_amount;
            $paid = (float) ($payments->get($student->id)?->total_paid ?? 0);
            $balance = max(0, $expected - $paid);

            $outstanding += $balance;
        }

        return $outstanding;
    }

    /*
    |--------------------------------------------------------------------------
    | CALCULATE OVERPAYMENT PER STUDENT
    |--------------------------------------------------------------------------
    */
    private function calculateStudentOverpayment(?string $studentType, ?int $yearId): float
    {
        $overpayment = 0;

        $students = Student::query()
            ->with(['schoolClass', 'section'])
            ->when($studentType, fn($q) => $q->where('student_type', $studentType))
            ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
            ->get();

        $invoiceQuery = Invoice::query()
            ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId));

        if ($studentType) {
            $invoiceQuery->where('student_type', $studentType);
        }

        $invoices = $invoiceQuery->get();

        $invoiceLookup = [];
        foreach ($invoices as $invoice) {
            $key = $invoice->class_id . '-' . ($invoice->section_id ?? 'null') . '-' . $invoice->student_type;
            $invoiceLookup[$key] = $invoice;

            $fallbackKey = $invoice->class_id . '-null-' . $invoice->student_type;
            if (!isset($invoiceLookup[$fallbackKey])) {
                $invoiceLookup[$fallbackKey] = $invoice;
            }
        }

        $studentIds = $students->pluck('id');
        $payments = Payment::whereIn('student_id', $studentIds)
            ->when($yearId, fn($q) => $q->whereHas('invoice', fn($q2) => $q2->where('academic_year_id', $yearId)))
            ->select('student_id', DB::raw('SUM(amount_paid) as total_paid'))
            ->groupBy('student_id')
            ->get()
            ->keyBy('student_id');

        foreach ($students as $student) {
            $exactKey = $student->class_id . '-' . ($student->section_id ?? 'null') . '-' . $student->student_type;
            $fallbackKey = $student->class_id . '-null-' . $student->student_type;

            $invoice = $invoiceLookup[$exactKey] ?? $invoiceLookup[$fallbackKey] ?? null;

            if (!$invoice) {
                continue;
            }

            $expected = (float) $invoice->total_amount;
            $paid = (float) ($payments->get($student->id)?->total_paid ?? 0);
            $excess = max(0, $paid - $expected);

            $overpayment += $excess;
        }

        return $overpayment;
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENTS
    |--------------------------------------------------------------------------
    */
    public function payments(Request $request)
    {
        $date = $request->date ?? now()->toDateString();
        $classId = $request->class_id;
        $studentType = $request->student_type;
        $yearId = session('academic_year_id');

        $payments = Payment::with(['student.schoolClass', 'invoice', 'receiver'])
            ->when($studentType, fn($q) =>
                $q->whereHas('student', fn($q2) =>
                    $q2->where('student_type', $studentType)
                )
            )
            ->when($classId, fn($q) =>
                $q->whereHas('student', fn($q2) =>
                    $q2->where('class_id', $classId)
                )
            )
            ->when($yearId, fn($q) => $q->whereHas('invoice', fn($q2) => $q2->where('academic_year_id', $yearId)))
            ->whereDate('payment_date', $date)
            ->latest()
            ->get();

        return view('finance.payments', [
            'payments' => $payments,
            'date' => $date,
            'selectedClass' => $classId,
            'studentType' => $studentType,
            'totalAmount' => $payments->sum('amount_paid'),
            'totalTransactions' => $payments->count(),
            'classes' => SchoolClass::orderBy('name')->get(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | INCOME
    |--------------------------------------------------------------------------
    */
    public function income()
    {
        $yearId = session('academic_year_id');

        $payments = Payment::with(['student.schoolClass', 'invoice'])
            ->when($yearId, fn($q) => $q->whereHas('invoice', fn($q2) => $q2->where('academic_year_id', $yearId)))
            ->latest()
            ->get();

        return view('finance.income', [
            'payments' => $payments,
            'totalIncome' => $payments->sum('amount_paid'),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | INVOICES
    |--------------------------------------------------------------------------
    */
    public function invoices()
    {
        $yearId = session('academic_year_id');

        $invoices = Invoice::with([
            'schoolClass',
            'invoiceItems.feeCategory',
            'student'
        ])
        ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
        ->latest()
        ->get();

        $groupedInvoices = $invoices->groupBy('class_id');
        $studentsByClass = Student::all()->groupBy('class_id');

        return view('finance.invoices', compact('groupedInvoices', 'studentsByClass'));
    }

    /*
    |--------------------------------------------------------------------------
    | CLASSES
    |--------------------------------------------------------------------------
    */
    public function classes()
    {
        $classes = SchoolClass::withCount('students')
            ->orderBy('name')
            ->get();

        return view('finance.classes', compact('classes'));
    }

    /*
    |--------------------------------------------------------------------------
    | CLASS STUDENTS
    |--------------------------------------------------------------------------
    */
    public function classStudents(Request $request, $classId)
    {
        $classes = SchoolClass::orderBy('name')->get();
        $sections = class_exists(Section::class) ? Section::all() : collect();

        $query = Student::with('schoolClass')
            ->withSum('payments', 'amount_paid')
            ->where('class_id', $classId);

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        $students = $query->get();

        return view('finance.students', compact('students', 'classId', 'classes', 'sections'));
    }

    /*
    |--------------------------------------------------------------------------
    | ALL STUDENTS
    |--------------------------------------------------------------------------
    */
    public function students(Request $request)
    {
        $classes = SchoolClass::orderBy('name')->get();
        $sections = class_exists(Section::class) ? Section::all() : collect();

        $query = Student::with('schoolClass')
            ->withSum('payments', 'amount_paid');

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        $students = $query->get();

        return view('finance.students', compact('students', 'classes', 'sections'));
    }

    /*
    |--------------------------------------------------------------------------
    | EXPENSES
    |--------------------------------------------------------------------------
    */
    public function expenses()
    {
        $expenses = Expense::latest()->get();

        return view('finance.expenses', [
            'expenses' => $expenses,
            'totalExpenses' => $expenses->sum('amount'),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DAILY TRANSACTIONS (AJAX)
    |--------------------------------------------------------------------------
    */
    public function dailyTransactions(Request $request)
    {
        $date = $request->date ?? now()->toDateString();
        $yearId = session('academic_year_id');

        $payments = Payment::with('student')
            ->when($yearId, fn($q) => $q->whereHas('invoice', fn($q2) => $q2->where('academic_year_id', $yearId)))
            ->whereDate('payment_date', $date)
            ->get()
            ->map(function ($payment) {
                return [
                    'receipt_no' => $payment->receipt_no,
                    'student_name' => ($payment->student->first_name ?? '') . ' ' . ($payment->student->last_name ?? ''),
                    'student_id' => $payment->student->student_id ?? '',
                    'amount_paid' => (float) $payment->amount_paid,
                    'payment_method' => $payment->payment_method ?? 'Cash',
                    'payment_date' => \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y'),
                    'time' => \Carbon\Carbon::parse($payment->created_at)->format('h:i A'),
                ];
            });

        $expenses = Expense::whereDate('expense_date', $date)
            ->get()
            ->map(function ($expense) {
                return [
                    'title' => $expense->title,
                    'category' => $expense->category,
                    'amount' => (float) $expense->amount,
                    'description' => $expense->description,
                    'expense_date' => $expense->expense_date ? \Carbon\Carbon::parse($expense->expense_date)->format('M d, Y') : 'N/A',
                    'time' => \Carbon\Carbon::parse($expense->created_at)->format('h:i A'),
                ];
            });

        return response()->json([
            'success' => true,
            'payments' => $payments,
            'expenses' => $expenses,
            'totalPayments' => $payments->sum('amount_paid'),
            'totalExpenses' => $expenses->sum('amount'),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT INVOICE
    |--------------------------------------------------------------------------
    */
    public function studentInvoice(Request $request, $invoiceId)
    {
        $invoice = Invoice::with([
            'schoolClass',
            'invoiceItems.feeCategory',
            'student'
        ])->findOrFail($invoiceId);

        $studentId = $request->student_id;

        if (!$studentId) {
            return redirect()->back()->with('error', 'Please select a student first.');
        }

        $student = Student::where('id', $studentId)
            ->where('class_id', $invoice->class_id)
            ->firstOrFail();

        return view('finance.student-invoice', compact('invoice', 'student'));
    }
}