<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\AcademicYear;
use App\Models\FeeCategory;
use App\Models\Student;
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

    /* ================= INDEX ================= */
    public function index(Request $request)
    {
        $yearId = session('academic_year_id');

        $query = Invoice::with([
            'student',
            'schoolClass',
            'section',
            'academicYear',
            'invoiceItems.feeCategory',
            'payments'
        ]);

        if ($yearId) {
            $query->where('academic_year_id', $yearId);
        }

        $invoices = $query->latest()->paginate(20);

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
            'fee_category_id' => 'required|array',
            'amount' => 'required|array',
        ]);

        $totalAmount = array_sum($validated['amount']);

        DB::transaction(function () use ($validated, $totalAmount) {

            $invoice = Invoice::create([
                'invoice_no' => self::generateInvoiceNumber(),
                'student_id' => null,
                'class_id' => $validated['class_id'],
                'student_type' => $validated['student_type'],
                'academic_year_id' => $validated['academic_year_id'],
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'balance' => $totalAmount,
                'status' => 'Unpaid',
                'created_by' => auth()->id(),
            ]);

            foreach ($validated['fee_category_id'] as $i => $feeId) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'fee_category_id' => $feeId,
                    'amount' => $validated['amount'][$i] ?? 0,
                    'subtotal' => $validated['amount'][$i] ?? 0,
                ]);
            }
        });

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice created successfully.');
    }

    /* ================= SHOW ================= */
    public function show(Invoice $invoice)
    {
        $invoice->load(['student','invoiceItems.feeCategory','payments']);

        return view('invoices.show', compact('invoice'));
    }

    /* ================= EDIT (FIXED) ================= */
    public function edit(Invoice $invoice)
    {
        return view('invoices.edit', [
            'invoice' => $invoice->load('invoiceItems'),

            // 🔥 FIX: THESE WERE MISSING (CAUSE OF YOUR ERROR)
            'classes' => SchoolClass::orderBy('name')->get(),
            'sections' => Section::orderBy('name')->get(),
            'academicYears' => AcademicYear::latest()->get(),
            'feeCategories' => FeeCategory::orderBy('name')->get(),
            'students' => Student::all(),
        ]);
    }

    /* ================= UPDATE ================= */
    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'student_type' => 'required|in:Old,New',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        DB::transaction(function () use ($invoice, $validated) {

            $invoice->update([
                'class_id' => $validated['class_id'],
                'student_type' => $validated['student_type'],
                'academic_year_id' => $validated['academic_year_id'],
            ]);
        });

        return redirect()->route('invoices.index')
            ->with('success', 'Updated successfully');
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