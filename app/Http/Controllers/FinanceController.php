<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SUMMARY
    |--------------------------------------------------------------------------
    */
    public function summary(Request $request)
    {
        $studentType = $request->student_type;

        $totalIncome = Payment::when($studentType, function ($q) use ($studentType) {
            $q->whereHas('student', fn($q2) =>
                $q2->where('student_type', $studentType)
            );
        })->sum('amount_paid');

        $totalExpenses = Expense::sum('amount');
        $totalExpected = Invoice::sum('total_amount');

        return view('finance.summary', [
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'totalExpected' => $totalExpected,
            'balanceFees' => max(0, $totalExpected - $totalIncome),
            'netProfit' => $totalIncome - $totalExpenses,
            'totalStudents' => Student::when($studentType, function ($q) use ($studentType) {
                $q->where('student_type', $studentType);
            })->count(),
            'studentType' => $studentType,
        ]);
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
    | INVOICES (FIXED — NO ERRORS + NO DUPLICATION)
    |--------------------------------------------------------------------------
    */
    public function invoices()
    {
        $invoices = Invoice::with([
            'schoolClass',
            'invoiceItems.feeCategory',
            'student'
        ])->latest()->get();

        // GROUP BY CLASS (FIXES DUPLICATES)
        $groupedInvoices = $invoices->groupBy('class_id');

        // PRELOAD STUDENTS (NO DB QUERY IN BLADE)
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
    public function classStudents($classId)
    {
        $students = Student::with('schoolClass')
            ->withSum('payments', 'amount_paid')
            ->where('class_id', $classId)
            ->get();

        return view('finance.students', compact('students', 'classId'));
    }

    /*
    |--------------------------------------------------------------------------
    | ALL STUDENTS
    |--------------------------------------------------------------------------
    */
    public function students()
    {
        $students = Student::with('schoolClass')
            ->withSum('payments', 'amount_paid')
            ->get();

        return view('finance.students', compact('students'));
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
    | DAILY TRANSACTIONS
    |--------------------------------------------------------------------------
    */
    public function dailyTransactions(Request $request)
    {
        $date = $request->date ?? now()->toDateString();

        return response()->json([
            'payments' => Payment::whereDate('created_at', $date)->get(),
            'expenses' => Expense::whereDate('created_at', $date)->get(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT INVOICE (FIXED SAFELY)
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