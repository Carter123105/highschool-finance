<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\PaymentAllocation;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $yearId = session('academic_year_id');

        $payments = Payment::with([
                'student',
                'invoice.schoolClass',
                'receiver'
            ])
            ->when($yearId, function ($q) use ($yearId) {
                $q->whereHas('invoice', function ($sub) use ($yearId) {
                    $sub->where('academic_year_id', $yearId);
                });
            })
            ->latest()
            ->paginate(20);

        return view('payments.index', compact('payments'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE (FIXED - Added student_type filtering)
    |--------------------------------------------------------------------------
    */
    public function create(Request $request)
    {
        $classId = $request->class_id;
        $studentType = $request->student_type; // ✅ ADDED
        $yearId = session('academic_year_id');

        $students = collect();
        $invoices = collect();

        if ($classId) {

            // ✅ Filter students by class AND student_type
            $students = Student::where('class_id', $classId)
                ->when($studentType, function ($query) use ($studentType) {
                    return $query->where('student_type', $studentType);
                })
                ->orderBy('first_name')
                ->get();

            // ✅ Filter invoices by class AND student_type (from invoice table)
            $invoices = Invoice::where('class_id', $classId)
                ->when($studentType, function ($query) use ($studentType) {
                    return $query->where('student_type', $studentType);
                })
                ->when($yearId, function ($q) use ($yearId) {
                    $q->where('academic_year_id', $yearId);
                })
                ->where('status', '!=', 'Paid') // Only show unpaid/partial
                ->orderByDesc('created_at')
                ->get();
        }

        return view('payments.create', [
            'classes' => SchoolClass::orderBy('name')->get(),
            'students' => $students,
            'invoices' => $invoices,
            'selectedClass' => $classId,
            'selectedType' => $studentType, // ✅ ADDED
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $data = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'student_id' => 'required|exists:students,id',
            'amount_paid' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:255',
        ]);

        $result = DB::transaction(function () use ($data) {

            $invoice = Invoice::with('invoiceItems')
                ->lockForUpdate()
                ->findOrFail($data['invoice_id']);

            $totalPaid = Payment::where('invoice_id', $invoice->id)
                ->sum('amount_paid');

            $remaining = round(
                $invoice->total_amount - $totalPaid,
                2
            );

            $amount = round($data['amount_paid'], 2);

            /*
            |------------------------------------------------------
            | PREVENT OVERPAYMENT
            |------------------------------------------------------
            */
            if ($amount > $remaining) {
                return [
                    'error' => true,
                    'message' =>
                        'Overpayment not allowed. Remaining balance: ' .
                        number_format($remaining, 2)
                ];
            }

            /*
            |------------------------------------------------------
            | RECEIPT NUMBER
            |------------------------------------------------------
            */
            $setting = Setting::first();

            $nextId = (Payment::max('id') ?? 0) + 1;

            $receiptNo =
                ($setting->receipt_prefix ?? 'REC') . '-' .
                str_pad($nextId, 6, '0', STR_PAD_LEFT);

            /*
            |------------------------------------------------------
            | CREATE PAYMENT
            |------------------------------------------------------
            */
            $payment = Payment::create([
                'receipt_no' => $receiptNo,
                'invoice_id' => $invoice->id,
                'student_id' => $data['student_id'],
                'amount_paid' => $amount,
                'payment_method' => $data['payment_method'],
                'payment_date' => $data['payment_date'],
                'received_by' => auth()->id(),
            ]);

            /*
            |------------------------------------------------------
            | PAYMENT ALLOCATION
            |------------------------------------------------------
            */
            $remainingPay = $amount;

            foreach ($invoice->invoiceItems as $item) {

                if ($remainingPay <= 0) {
                    break;
                }

                $allocated = PaymentAllocation::where(
                        'invoice_item_id',
                        $item->id
                    )
                    ->whereHas('payment', function ($q) use ($invoice) {
                        $q->where('invoice_id', $invoice->id);
                    })
                    ->sum('amount');

                $due = max(0, $item->subtotal - $allocated);

                if ($due <= 0) {
                    continue;
                }

                $payNow = min($remainingPay, $due);

                PaymentAllocation::create([
                    'payment_id' => $payment->id,
                    'invoice_item_id' => $item->id,
                    'amount' => $payNow,
                ]);

                $remainingPay -= $payNow;
            }

            /*
            |------------------------------------------------------
            | UPDATE INVOICE
            |------------------------------------------------------
            */
            $this->recalculateInvoice($invoice);

            return ['error' => false];
        });

        /*
        |----------------------------------------------------------
        | HANDLE ERROR
        |----------------------------------------------------------
        */
        if ($result['error'] ?? false) {
            return back()
                ->withErrors([
                    'amount_paid' => $result['message']
                ])
                ->withInput();
        }

        return redirect()
            ->route('payments.index')
            ->with(
                'success',
                'Payment recorded successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT PAYMENTS
    |--------------------------------------------------------------------------
    */
    public function studentPayments(Student $student)
    {
        $payments = Payment::with([
                'invoice',
                'receiver',
                'allocations.invoiceItem.feeCategory'
            ])
            ->where('student_id', $student->id)
            ->latest()
            ->paginate(20);

        $totalPaid = $payments->sum('amount_paid');

        return view('payments.student-payments', [
            'student' => $student,
            'payments' => $payments,
            'totalPaid' => $totalPaid,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW SINGLE PAYMENT
    |--------------------------------------------------------------------------
    */
    public function show(Payment $payment)
    {
        $payment->load([
            'student',
            'invoice',
            'receiver',
            'allocations.invoiceItem.feeCategory'
        ]);

        return view('payments.show', compact('payment'));
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit(Payment $payment)
    {
        $payment->load('invoice', 'student');

        return view('payments.edit', compact('payment'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'payment_method' => 'required|string|max:255',
            'payment_date' => 'required|date',
        ]);

        $payment->update($data);

        return redirect()
            ->route('payments.index')
            ->with(
                'success',
                'Payment updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */
    public function destroy(Payment $payment)
    {
        DB::transaction(function () use ($payment) {

            $invoice = $payment->invoice;

            $payment->allocations()->delete();

            $payment->delete();

            if ($invoice) {
                $this->recalculateInvoice($invoice);
            }
        });

        return redirect()
            ->route('payments.index')
            ->with(
                'success',
                'Payment deleted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | RECEIPT
    |--------------------------------------------------------------------------
    */
    public function receipt(Payment $payment)
    {
        $payment->load([
            'student.schoolClass',
            'student.section',
            'invoice.invoiceItems.feeCategory',
            'allocations.invoiceItem.feeCategory',
            'receiver',
        ]);

        return view('payments.receipt', compact('payment'));
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT BALANCE CHECK
    |--------------------------------------------------------------------------
    */
    public function studentBalance(Request $request)
    {
        $invoice = Invoice::find($request->invoice_id);

        if (!$invoice) {
            return response()->json([
                'paid' => 0,
                'balance' => 0,
                'status' => 'Invoice Not Found'
            ]);
        }

        $paid = Payment::where(
                'student_id',
                $request->student_id
            )
            ->where(
                'invoice_id',
                $request->invoice_id
            )
            ->sum('amount_paid');

        $balance = max(
            0,
            $invoice->total_amount - $paid
        );

        return response()->json([
            'paid' => $paid,
            'balance' => $balance,
            'status' => match (true) {
                $paid <= 0 => 'Unpaid',
                $balance <= 0 => 'Fully Paid',
                default => 'Partially Paid'
            }
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | RECALCULATE INVOICE
    |--------------------------------------------------------------------------
    */
    private function recalculateInvoice($invoice)
    {
        $paid = Payment::where(
                'invoice_id',
                $invoice->id
            )
            ->sum('amount_paid');

        $invoice->update([
            'paid_amount' => $paid,
            'balance' => max(
                0,
                $invoice->total_amount - $paid
            ),
            'status' => match (true) {
                $paid <= 0 => 'Unpaid',
                $paid < $invoice->total_amount => 'Partial',
                default => 'Paid'
            }
        ]);
    }
}