<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentAllocation;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SETTINGS
    |--------------------------------------------------------------------------
    */

    private function getSettings(): object
    {
        $setting = Setting::first();

        if ($setting) {
            return $setting;
        }

        return (object) [
            'school_name'    => config('app.name', 'SCHOOL NAME'),
            'school_address' => 'Configure school address',
            'school_phone'   => null,
            'school_email'   => null,
            'logo'           => null,
            'system_name'    => null,
            'receipt_prefix' => 'REC',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RECEIPT NUMBER
    |--------------------------------------------------------------------------
    */

    private function generateReceiptNo(): string
    {
        $setting = $this->getSettings();

        $nextId = (Payment::max('id') ?? 0) + 1;

        return ($setting->receipt_prefix ?? 'REC')
            . '-'
            . str_pad((string) $nextId, 6, '0', STR_PAD_LEFT);
    }

    /*
    |--------------------------------------------------------------------------
    | RECALCULATE INVOICE
    |--------------------------------------------------------------------------
    */

    private function recalculateInvoice(Invoice $invoice): void
    {
        $paid = Payment::where('invoice_id', $invoice->id)
            ->sum('amount_paid');

        $balance = max(0, $invoice->total_amount - $paid);

        $status = match (true) {
            $paid <= 0                     => 'Unpaid',
            $paid < $invoice->total_amount => 'Partial',
            default                        => 'Paid',
        };

        $invoice->update([
            'paid_amount' => $paid,
            'balance'     => $balance,
            'status'      => $status,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(): View
    {
        $yearId = session('academic_year_id');

        $payments = Payment::with([
                'student',
                'invoice.schoolClass',
                'receiver',
                'allocations.invoiceItem.feeCategory',
            ])
            ->when($yearId, function ($query) use ($yearId) {
                $query->whereHas('invoice', function ($q) use ($yearId) {
                    $q->where('academic_year_id', $yearId);
                });
            })
            ->latest()
            ->paginate(20);

        return view('payments.index', compact('payments'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE - FIXED
    |--------------------------------------------------------------------------
    */

    public function create(Request $request): View
    {
        $classId         = $request->input('class_id');
        $studentType     = $request->input('student_type');
        $selectedSection = $request->input('section_name');
        $yearId          = session('academic_year_id');

        $students  = collect();
        $invoices  = collect();
        $sections  = Section::all();
        $debugInfo = [];

        if (!$classId) {
            return view('payments.create', [
                'classes'         => SchoolClass::orderBy('name')->get(),
                'sections'        => $sections,
                'students'        => $students,
                'invoices'        => $invoices,
                'selectedClass'   => null,
                'selectedType'    => null,
                'selectedSection' => null,
                'debugInfo'       => $debugInfo,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | STUDENTS - FIXED: Always filter by academic year if set
        |--------------------------------------------------------------------------
        */

        $studentQuery = Student::where('class_id', $classId)
            ->when($studentType, function ($q) use ($studentType) {
                $q->where('student_type', $studentType);
            })
            ->when($yearId, function ($q) use ($yearId) {
                $q->where('academic_year_id', $yearId);
            });

        $sectionIds = collect();

        if ($selectedSection) {
            $sectionIds = Section::where('name', $selectedSection)
                ->where('class_id', $classId)
                ->pluck('id');

            $studentQuery->whereIn('section_id', $sectionIds);
        }

        $students = $studentQuery
            ->with('section')
            ->orderBy('first_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | INVOICES - FIXED: Better section matching logic
        |--------------------------------------------------------------------------
        */

        $invoiceQuery = Invoice::where('class_id', $classId)
            ->when($studentType, function ($q) use ($studentType) {
                $q->where('student_type', $studentType);
            })
            ->when($yearId, function ($q) use ($yearId) {
                $q->where('academic_year_id', $yearId);
            });

        // FIXED: When section is selected, match invoices for that specific section
        // OR class-level invoices (section_id IS NULL)
        if ($selectedSection && $sectionIds->isNotEmpty()) {
            $invoiceQuery->where(function ($q) use ($sectionIds) {
                $q->whereIn('section_id', $sectionIds)
                  ->orWhereNull('section_id');
            });
        }

        // FIXED: Include ALL statuses that allow payment
        $invoiceQuery->where(function ($q) {
            $q->whereIn('status', ['Unpaid', 'Partial', 'Pending', 'Paid'])
              ->orWhereNull('status');
        });

        $invoices = $invoiceQuery
            ->with('invoiceItems.feeCategory')
            ->latest()
            ->get();

        // FIXED: Better debugging to show exactly what's happening
        $debugInfo = [
            'section_ids_found'  => $sectionIds->toArray(),
            'student_count'      => $students->count(),
            'invoice_count'      => $invoices->count(),
            'invoice_details'    => $invoices->map(fn($i) => [
                'id' => $i->id,
                'no' => $i->invoice_no,
                'type' => $i->student_type,
                'section_id' => $i->section_id,
                'status' => $i->status,
                'year' => $i->academic_year_id,
                'total' => $i->total_amount,
            ])->toArray(),
            'student_details'    => $students->map(fn($s) => [
                'id' => $s->id,
                'name' => $s->first_name . ' ' . $s->last_name,
                'type' => $s->student_type,
                'section_id' => $s->section_id,
            ])->toArray(),
            'sql'                => $invoiceQuery->toSql(),
            'bindings'           => $invoiceQuery->getBindings(),
        ];

        return view('payments.create', [
            'classes'         => SchoolClass::orderBy('name')->get(),
            'sections'        => $sections,
            'students'        => $students,
            'invoices'        => $invoices,
            'selectedClass'   => $classId,
            'selectedType'    => $studentType,
            'selectedSection' => $selectedSection,
            'debugInfo'       => $debugInfo,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE PAYMENT
    |--------------------------------------------------------------------------
    */

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'invoice_id'            => 'required|exists:invoices,id',
            'student_id'            => 'required|exists:students,id',
            'amount_paid'           => 'required|numeric|min:0.01',
            'payment_date'          => 'required|date',
            'payment_method'        => 'required|string|max:255',
            'transaction_reference' => 'nullable|string|max:255',
            'notes'                 => 'nullable|string|max:1000',
        ]);

        $result = DB::transaction(function () use ($data) {

            $invoice = Invoice::with('invoiceItems.feeCategory')
                ->lockForUpdate()
                ->findOrFail($data['invoice_id']);

            /*
            |--------------------------------------------------------------------------
            | STUDENT PAYMENT TOTAL
            |--------------------------------------------------------------------------
            */

            $alreadyPaid = Payment::where('invoice_id', $invoice->id)
                ->where('student_id', $data['student_id'])
                ->sum('amount_paid');

            $remaining = round(
                $invoice->total_amount - $alreadyPaid,
                2
            );

            $amount = round($data['amount_paid'], 2);

            /*
            |--------------------------------------------------------------------------
            | PREVENT OVERPAYMENT
            |--------------------------------------------------------------------------
            */

            if ($amount > $remaining) {

                return [
                    'error'   => true,
                    'message' => 'Overpayment not allowed. Remaining balance is LRD '
                        . number_format($remaining, 2),
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | CREATE PAYMENT
            |--------------------------------------------------------------------------
            */

            $payment = Payment::create([
                'receipt_no'            => $this->generateReceiptNo(),
                'invoice_id'            => $invoice->id,
                'student_id'            => $data['student_id'],
                'amount_paid'           => $amount,
                'payment_method'        => $data['payment_method'],
                'transaction_reference' => $data['transaction_reference'] ?? null,
                'payment_date'          => $data['payment_date'],
                'received_by'           => auth()->id(),
                'notes'                 => $data['notes'] ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | ALLOCATE PAYMENT
            |--------------------------------------------------------------------------
            */

            $this->allocatePayment(
                $payment,
                $invoice,
                $amount,
                $data['student_id']
            );

            /*
            |--------------------------------------------------------------------------
            | RECALCULATE INVOICE
            |--------------------------------------------------------------------------
            */

            $this->recalculateInvoice($invoice);

            return [
                'error'   => false,
                'payment' => $payment,
            ];
        });

        if ($result['error'] ?? false) {

            return back()
                ->withErrors([
                    'amount_paid' => $result['message'],
                ])
                ->withInput();
        }

        return redirect()
            ->route('payments.receipt', $result['payment'])
            ->with('success', 'Payment recorded successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | ALLOCATE PAYMENT
    |--------------------------------------------------------------------------
    */

    private function allocatePayment(
        Payment $payment,
        Invoice $invoice,
        float $amount,
        int $studentId
    ): void {

        $remaining = $amount;

        foreach ($invoice->invoiceItems as $item) {

            if ($remaining <= 0) {
                break;
            }

            $alreadyAllocated = PaymentAllocation::where('invoice_item_id', $item->id)
                ->whereHas('payment', function ($q) use ($studentId) {
                    $q->where('student_id', $studentId);
                })
                ->sum('amount');

            $due = max(
                0,
                $item->subtotal - $alreadyAllocated
            );

            if ($due <= 0) {
                continue;
            }

            $payNow = min($remaining, $due);

            PaymentAllocation::create([
                'payment_id'      => $payment->id,
                'invoice_item_id' => $item->id,
                'amount'          => $payNow,
            ]);

            $remaining -= $payNow;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Payment $payment): View
    {
        $payment->load([
            'student',
            'invoice',
            'receiver',
            'allocations.invoiceItem.feeCategory',
        ]);

        return view('payments.show', compact('payment'));
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Payment $payment): View
    {
        $payment->load('invoice', 'student');

        return view('payments.edit', compact('payment'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $data = $request->validate([
            'payment_method'        => 'required|string|max:255',
            'payment_date'          => 'required|date',
            'transaction_reference' => 'nullable|string|max:255',
            'notes'                 => 'nullable|string|max:1000',
        ]);

        $payment->update($data);

        return redirect()
            ->route('payments.index')
            ->with('success', 'Payment updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(Payment $payment): RedirectResponse
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
            ->with('success', 'Payment deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | RECEIPT
    |--------------------------------------------------------------------------
    */

    public function receipt(Payment $payment): View
    {
        $payment->load([
            'student.schoolClass',
            'student.section',
            'invoice.invoiceItems.feeCategory',
            'allocations.invoiceItem.feeCategory',
            'receiver',
        ]);

        return view('payments.receipt', [
            'payment' => $payment,
            'student' => $payment->student,
            'invoice' => $payment->invoice,
            'setting' => $this->getSettings(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | FIXED STUDENT BALANCE ROUTE
    |--------------------------------------------------------------------------
    */

    public function studentBalance(Request $request): JsonResponse
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'invoice_id' => 'required|exists:invoices,id',
        ]);

        $invoice = Invoice::find($request->invoice_id);

        if (!$invoice) {

            return response()->json([
                'paid'    => 0,
                'balance' => 0,
                'status'  => 'Invoice Not Found',
            ]);
        }

        $paid = Payment::where('student_id', $request->student_id)
            ->where('invoice_id', $request->invoice_id)
            ->sum('amount_paid');

        $balance = max(
            0,
            $invoice->total_amount - $paid
        );

        return response()->json([
            'paid'    => $paid,
            'balance' => $balance,
            'status'  => match (true) {
                $paid <= 0    => 'Unpaid',
                $balance <= 0 => 'Fully Paid',
                default       => 'Partially Paid',
            },
        ]);
    }
}