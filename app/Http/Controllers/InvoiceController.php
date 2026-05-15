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
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $yearId = session('academic_year_id');

        $invoices = Invoice::with([
                'schoolClass',
                'section',
                'academicYear',
                'invoiceItems.feeCategory',
                'payments'
            ])
            ->when($yearId, fn($q) => $q->where('academic_year_id', $yearId))
            ->when($request->search, function ($q) use ($request) {
                $q->where('invoice_no', 'like', "%{$request->search}%")
                  ->orWhereHas('schoolClass', function ($s) use ($request) {
                      $s->where('name', 'like', "%{$request->search}%");
                  });
            })
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->student_type, fn($q) => $q->where('student_type', $request->student_type))
            ->latest()
            ->paginate(20);

        return view('invoices.index', compact('invoices'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE - Fixed to pass sections properly
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        // Get sections from database - only A and B
        $sections = Section::whereIn('name', ['A', 'B'])
            ->orWhereIn('name', ['Section A', 'Section B'])
            ->orderBy('name')
            ->get();

        // If no sections found with those names, get all (fallback)
        if ($sections->isEmpty()) {
            $sections = Section::orderBy('name')->take(2)->get();
        }

        return view('invoices.create', [
            'classes' => SchoolClass::orderBy('name')->get(),
            'sections' => $sections,
            'academicYears' => AcademicYear::latest()->get(),
            'feeCategories' => FeeCategory::orderBy('name')->get(),
            'students' => Student::select('id', 'first_name', 'last_name', 'class_id', 'section_id', 'student_type')->get(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE - Fixed to create individual student invoices
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $data = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'student_type' => 'required|in:Old,New',
            'section_id' => 'nullable|exists:sections,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'due_date' => 'nullable|date',

            'fee_category_id' => 'required|array',
            'fee_category_id.*' => 'exists:fee_categories,id',

            'amount' => 'required|array',
            'amount.*' => 'numeric|min:0',

            'discount' => 'nullable|array',
            'discount.*' => 'nullable|numeric|min:0',
        ]);

        // Find matching students
        $students = Student::where('class_id', $data['class_id'])
            ->where('student_type', $data['student_type'])
            ->when($data['section_id'] ?? null, fn($q) => $q->where('section_id', $data['section_id']))
            ->where('status', 'Active')
            ->get();

        if ($students->isEmpty()) {
            return back()->with('error', "No {$data['student_type']} students found in this class" . ($data['section_id'] ? ' and section.' : '.'));
        }

        DB::transaction(function () use ($data, $students) {

            $total = 0;
            foreach ($data['amount'] as $i => $amount) {
                $discount = $data['discount'][$i] ?? 0;
                $total += max(0, $amount - $discount);
            }

            // Create ONE class invoice (not per student)
            $invoice = Invoice::create([
                'invoice_no' => self::generateInvoiceNumber(),
                'student_id' => null,
                'class_id' => $data['class_id'],
                'section_id' => $data['section_id'] ?? null,
                'academic_year_id' => $data['academic_year_id'],
                'student_type' => $data['student_type'],
                'total_amount' => $total,
                'paid_amount' => 0,
                'balance' => $total,
                'status' => 'Unpaid',
                'due_date' => $data['due_date'] ?? null,
                'created_by' => auth()->id(),
            ]);

            foreach ($data['fee_category_id'] as $i => $feeId) {
                $amount = $data['amount'][$i] ?? 0;
                $discount = $data['discount'][$i] ?? 0;
                $subtotal = max(0, $amount - $discount);

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'fee_category_id' => $feeId,
                    'amount' => $amount,
                    'discount' => $discount,
                    'subtotal' => $subtotal,
                    'paid_amount' => 0,
                    'balance' => $subtotal,
                    'status' => 'Unpaid',
                ]);
            }
        });

        return redirect()->route('invoices.index')
            ->with('success', "Invoice created for {$data['student_type']} students.");
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    public function show(Invoice $invoice)
    {
        $invoice->load([
            'schoolClass',
            'section',
            'academicYear',
            'invoiceItems.feeCategory',
            'payments'
        ]);

        return view('invoices.show', compact('invoice'));
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit(Invoice $invoice)
    {
        $sections = Section::whereIn('name', ['A', 'B'])
            ->orWhereIn('name', ['Section A', 'Section B'])
            ->orderBy('name')
            ->get();

        if ($sections->isEmpty()) {
            $sections = Section::orderBy('name')->take(2)->get();
        }

        return view('invoices.edit', [
            'invoice' => $invoice,
            'classes' => SchoolClass::orderBy('name')->get(),
            'sections' => $sections,
            'academicYears' => AcademicYear::latest()->get(),
            'feeCategories' => FeeCategory::orderBy('name')->get(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'student_type' => 'required|in:Old,New',
            'section_id' => 'nullable|exists:sections,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'due_date' => 'nullable|date',

            'fee_category_id' => 'required|array',
            'fee_category_id.*' => 'exists:fee_categories,id',

            'amount' => 'required|array',
            'amount.*' => 'numeric|min:0',

            'discount' => 'nullable|array',
            'discount.*' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($data, $invoice) {

            $invoice->update([
                'class_id' => $data['class_id'],
                'student_type' => $data['student_type'],
                'section_id' => $data['section_id'] ?? null,
                'academic_year_id' => $data['academic_year_id'],
                'due_date' => $data['due_date'] ?? null,
            ]);

            $invoice->invoiceItems()->delete();

            $total = 0;

            foreach ($data['fee_category_id'] as $i => $feeId) {
                $amount = $data['amount'][$i] ?? 0;
                $discount = $data['discount'][$i] ?? 0;
                $subtotal = max(0, $amount - $discount);
                $total += $subtotal;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'fee_category_id' => $feeId,
                    'amount' => $amount,
                    'discount' => $discount,
                    'subtotal' => $subtotal,
                    'paid_amount' => 0,
                    'balance' => $subtotal,
                    'status' => 'Unpaid',
                ]);
            }

            $invoice->update([
                'total_amount' => $total,
                'balance' => $total,
                'paid_amount' => 0,
                'status' => 'Unpaid',
            ]);
        });

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */
    public function destroy(Invoice $invoice)
    {
        DB::transaction(function () use ($invoice) {
            $invoice->invoiceItems()->delete();
            $invoice->payments()->delete();
            $invoice->delete();
        });

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE INVOICE NUMBER
    |--------------------------------------------------------------------------
    */
    public static function generateInvoiceNumber()
    {
        return 'INV-' . date('Y') . '-' .
            str_pad((Invoice::max('id') ?? 0) + 1, 6, '0', STR_PAD_LEFT);
    }
}