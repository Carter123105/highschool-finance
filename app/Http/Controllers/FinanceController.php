<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Section; // Ensure this matches your actual Section model name if it exists
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SUMMARY (DASHBOARD)
    |--------------------------------------------------------------------------
    |
    */
    public function summary(Request $request)
    {
        $studentType = $request->student_type;

        // Total Income - payments received
        $totalIncome = Payment::when($studentType, function ($q) use ($studentType) {
            $q->whereHas('student', fn($q2) =>
                $q2->where('student_type', $studentType)
            );
        })->sum('amount_paid');

        // Total Expenses
        $totalExpenses = Expense::sum('amount');

        // Total Expected (all invoices total amount)
        $totalExpected = Invoice::sum('total_amount');

        // ✅ FIXED: Outstanding Fees = sum of balance column (NOT expected - income)
        // The balance column already tracks what's unpaid per invoice
        $totalOutstanding = Invoice::sum('balance');

        // Daily transactions for modal
        $todayPayments = Payment::whereDate('payment_date', today())->get();
        $todayExpenses = Expense::whereDate('expense_date', today())->get();

        return view('finance.summary', [
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'totalExpected' => $totalExpected,
            'totalOutstanding' => $totalOutstanding,  // ✅ Pass this to view
            'netProfit' => $totalIncome - $totalExpenses,
            'totalStudents' => Student::when($studentType, function ($q) use ($studentType) {
                $q->where('student_type', $studentType);
            })->count(),
            'studentType' => $studentType,
            'todayPayments' => $todayPayments,
            'todayExpenses' => $todayExpenses,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENTS
    |--------------------------------------------------------------------------
    |
    */
    public function payments(Request $request)
    {
        $date = $request->date ?? now()->toDateString();
        $classId = $request->class_id;
        $studentType = $request->student_type;

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
    |
    */
    public function income()
    {
        $payments = Payment::with(['student.schoolClass', 'invoice'])
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
    |
    */
    public function invoices()
    {
        $invoices = Invoice::with([
            'schoolClass',
            'invoiceItems.feeCategory',
            'student'
        ])->latest()->get();

        $groupedInvoices = $invoices->groupBy('class_id');
        $studentsByClass = Student::all()->groupBy('class_id');

        return view('finance.invoices', compact('groupedInvoices', 'studentsByClass'));
    }

    /*
    |--------------------------------------------------------------------------
    | CLASSES
    |--------------------------------------------------------------------------
    |
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
    |
    */
    public function classStudents(Request $request, $classId)
    {
        // 1. Load Filter variables for the Blade template to use
        $classes = SchoolClass::orderBy('name')->get();
        
        // Safeguard section retrieval if your model varies:
        $sections = class_exists(Section::class) ? Section::all() : collect();

        // 2. Query data with filters applied
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
    | ALL STUDENTS (With URL Filter Logic Embedded)
    |--------------------------------------------------------------------------
    |
    */
    public function students(Request $request)
    {
        // 1. Fetch data for the filter dropdowns (Fixes the undefined variable error!)
        $classes = SchoolClass::orderBy('name')->get();
        
        // Safeguard section retrieval if your model varies:
        $sections = class_exists(Section::class) ? Section::all() : collect();

        // 2. Query structure for tracking results
        $query = Student::with('schoolClass')
            ->withSum('payments', 'amount_paid');

        // 3. Conditional Filter Applications
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
    |
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
    |
    */
    public function dailyTransactions(Request $request)
    {
        $date = $request->date ?? now()->toDateString();

        $payments = Payment::with('student')
            ->whereDate('payment_date', $date)
            ->get()
            ->map(function ($payment) {
                return [
                    'receipt_no' => $payment->receipt_no,
                    'student_name' => ($payment->student->first_name ?? '') . ' ' . ($payment->student->last_name ?? ''),
                    'student_id' => $payment->student->student_id ?? '',
                    'amount_paid' => $payment->amount_paid,
                    'payment_method' => $payment->payment_method,
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
                    'amount' => $expense->amount,
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
    |
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