<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\AcademicYear;
use App\Models\FeeCategory;
use App\Models\Student;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);

        $this->middleware('permission:view invoices')->only(['index', 'show']);
        $this->middleware('permission:create invoices')->only(['create', 'store']);
        $this->middleware('permission:edit invoices')->only(['edit', 'update']);
        $this->middleware('permission:delete invoices')->only(['destroy']);
    }

    /* ================= INDEX (FIXED) ================= */
    public function index(Request $request)
    {
        $yearId = session('academic_year_id');

        $query = Invoice::with([
            'schoolClass',
            'section',
            'academicYear',
            'invoiceItems.feeCategory',
            'payments' => function($q) {
                $q->select('id', 'invoice_id', 'amount_paid', 'student_id');
            }
        ]);

        if ($yearId) {
            $query->where('academic_year_id', $yearId);
        }

        $invoices = $query->latest()->paginate(20);

        // FIX: Properly calculate paid_amount — cap at total, track excess separately
        foreach ($invoices as $invoice) {
            $totalPayments = $invoice->payments->sum('amount_paid');
            
            // For class invoices, paid_amount should never exceed total_amount
            // The "excess" is collected from multiple students paying the same class fee
            $cappedPaid = min($totalPayments, $invoice->total_amount);
            $excessCollected = max(0, $totalPayments - $invoice->total_amount);
            
            $actualBalance = max(0, $invoice->total_amount - $cappedPaid);

            // Determine status based on capped paid amount
            $status = match(true) {
                $cappedPaid <= 0 => 'Unpaid',
                $actualBalance <= 0 => 'Paid',
                default => 'Partial',
            };

            // Update if stored values are wrong
            if (abs($invoice->paid_amount - $cappedPaid) > 0.01 || 
                abs($invoice->balance - $actualBalance) > 0.01 ||
                $invoice->status !== $status) {
                
                $invoice->update([
                    'paid_amount' => $cappedPaid,
                    'balance' => $actualBalance,
                    'status' => $status,
                ]);

                $invoice->paid_amount = $cappedPaid;
                $invoice->balance = $actualBalance;
                $invoice->status = $status;
            }

            // Store excess on model for display (not persisted)
            $invoice->excess_collected = $excessCollected;
            $invoice->total_collected = $totalPayments;
        }

        return view('invoices.index', compact('invoices'));
    }

    /* ================= CREATE ================= */
    public function create()
    {
        return view('invoices.create', [
            'classes' => SchoolClass::orderBy('name')->get(),
            'sections' => Section::orderBy('name')->get(),
            'academicYears' => AcademicYear::latest()->get(),
            'feeCategories' => FeeCategory::orderBy('name')->get(),
            'students' => Student::all(),
        ]);
    }

    /* ================= STORE ================= */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'student_type' => 'required|in:Old,New',
            'academic_year_id' => 'required|exists:academic_years,id',
            'section_id' => 'nullable|exists:sections,id',
            'fee_category_id' => 'required|array|min:1',
            'fee_category_id.*' => 'required|exists:fee_categories,id',
            'amount' => 'required|array|min:1',
            'amount.*' => 'required|numeric|min:0',
            'discount' => 'nullable|array',
            'discount.*' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $totalAmount = 0;

            $invoice = Invoice::create([
                'invoice_no' => self::generateInvoiceNumber(),
                'student_id' => null,
                'class_id' => $validated['class_id'],
                'section_id' => $validated['section_id'] ?? null,
                'student_type' => $validated['student_type'],
                'academic_year_id' => $validated['academic_year_id'],
                'total_amount' => 0,
                'paid_amount' => 0,
                'balance' => 0,
                'status' => 'Unpaid',
                'created_by' => auth()->id(),
            ]);

            foreach ($validated['fee_category_id'] as $i => $feeId) {
                $amount = (float) ($validated['amount'][$i] ?? 0);
                $discount = (float) ($validated['discount'][$i] ?? 0);
                $subtotal = max(0, $amount - $discount);

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'fee_category_id' => $feeId,
                    'amount' => $amount,
                    'discount' => $discount,
                    'subtotal' => $subtotal,
                ]);

                $totalAmount += $subtotal;
            }

            $invoice->update([
                'total_amount' => $totalAmount,
                'balance' => $totalAmount,
            ]);
        });

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice created successfully.');
    }

    /* ================= SHOW ================= */
    public function show(Invoice $invoice)
    {
        $invoice->load([
            'student',
            'schoolClass',
            'section',
            'academicYear',
            'invoiceItems.feeCategory',
            'payments.student'
        ]);

        $totalPayments = $invoice->payments->sum('amount_paid');
        $cappedPaid = min($totalPayments, $invoice->total_amount);
        $excessCollected = max(0, $totalPayments - $invoice->total_amount);
        $actualBalance = max(0, $invoice->total_amount - $cappedPaid);

        // Sync if needed
        if (abs($invoice->paid_amount - $cappedPaid) > 0.01) {
            $status = match(true) {
                $cappedPaid <= 0 => 'Unpaid',
                $actualBalance <= 0 => 'Paid',
                default => 'Partial',
            };
            
            $invoice->update([
                'paid_amount' => $cappedPaid,
                'balance' => $actualBalance,
                'status' => $status,
            ]);
            
            $invoice->paid_amount = $cappedPaid;
            $invoice->balance = $actualBalance;
            $invoice->status = $status;
        }

        $invoice->excess_collected = $excessCollected;
        $invoice->total_collected = $totalPayments;
        $invoice->student_count = $invoice->payments->pluck('student_id')->unique()->count();

        return view('invoices.show', compact('invoice'));
    }

    /* ================= EDIT ================= */
    public function edit(Invoice $invoice)
    {
        return view('invoices.edit', [
            'invoice' => $invoice->load('invoiceItems.feeCategory'),
            'classes' => SchoolClass::orderBy('name')->get(),
            'sections' => Section::orderBy('name')->get(),
            'academicYears' => AcademicYear::latest()->get(),
            'feeCategories' => FeeCategory::orderBy('name')->get(),
            'students' => Student::all(),
        ]);
    }

    /* ================= UPDATE (FULLY FIXED) ================= */
    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'due_date' => 'nullable|date',
            'fee_category_id' => 'required|array|min:1',
            'fee_category_id.*' => 'required|exists:fee_categories,id',
            'amount' => 'required|array|min:1',
            'amount.*' => 'required|numeric|min:0',
            'discount' => 'nullable|array',
            'discount.*' => 'nullable|numeric|min:0',
            'invoice_item_id' => 'nullable|array',
            'invoice_item_id.*' => 'nullable|integer',
            'deleted_items' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $invoice->update([
                'due_date' => $validated['due_date'] ?? null,
            ]);

            // Handle deleted items
            if (!empty($validated['deleted_items'])) {
                $deletedIds = array_filter(explode(',', $validated['deleted_items']));
                if (!empty($deletedIds)) {
                    InvoiceItem::whereIn('id', $deletedIds)
                        ->where('invoice_id', $invoice->id)
                        ->delete();
                }
            }

            // Process items
            $totalAmount = 0;

            foreach ($validated['fee_category_id'] as $index => $feeCategoryId) {
                $itemId = $validated['invoice_item_id'][$index] ?? null;
                $amount = (float) ($validated['amount'][$index] ?? 0);
                $discount = (float) ($validated['discount'][$index] ?? 0);
                $subtotal = max(0, $amount - $discount);

                if ($itemId) {
                    $existingItem = InvoiceItem::where('id', $itemId)
                        ->where('invoice_id', $invoice->id)
                        ->first();

                    if ($existingItem) {
                        $existingItem->update([
                            'fee_category_id' => $feeCategoryId,
                            'amount' => $amount,
                            'discount' => $discount,
                            'subtotal' => $subtotal,
                        ]);
                    }
                } else {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'fee_category_id' => $feeCategoryId,
                        'amount' => $amount,
                        'discount' => $discount,
                        'subtotal' => $subtotal,
                    ]);
                }

                $totalAmount += $subtotal;
            }

            // Recalculate from actual payments — cap paid at total
            $totalPayments = Payment::where('invoice_id', $invoice->id)->sum('amount_paid');
            $cappedPaid = min($totalPayments, $totalAmount);
            $balance = max(0, $totalAmount - $cappedPaid);

            $status = match(true) {
                $cappedPaid <= 0 => 'Unpaid',
                $balance <= 0 => 'Paid',
                default => 'Partial',
            };

            $invoice->update([
                'total_amount' => $totalAmount,
                'paid_amount' => $cappedPaid,
                'balance' => $balance,
                'status' => $status,
            ]);

            DB::commit();

            return redirect()->route('invoices.index')
                ->with('success', 'Invoice updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update invoice: ' . $e->getMessage());
        }
    }

    /* ================= DELETE ================= */
    public function destroy(Invoice $invoice)
    {
        if (!auth()->user()->can('delete invoices')) {
            abort(403);
        }

        DB::transaction(function () use ($invoice) {
            $invoice->invoiceItems()->delete();
            $invoice->payments()->delete();
            $invoice->delete();
        });

        return redirect()->route('invoices.index')
            ->with('success', 'Deleted successfully');
    }

    /* ================= INVOICE NUMBER ================= */
    public static function generateInvoiceNumber()
    {
        return 'INV-' . date('Y') . '-' .
            str_pad((Invoice::max('id') ?? 0) + 1, 6, '0', STR_PAD_LEFT);
    }
}