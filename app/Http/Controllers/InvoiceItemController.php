<?php

namespace App\Http\Controllers;

use App\Models\InvoiceItem;
use App\Models\Invoice;
use App\Models\FeeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceItemController extends Controller
{
    /*
    |--------------------------------------------------
    | INDEX
    |--------------------------------------------------
    */
    public function index()
    {
        $items = InvoiceItem::with(['invoice', 'feeCategory'])
            ->latest()
            ->paginate(20);

        return view('invoice-items.index', compact('items'));
    }

    /*
    |--------------------------------------------------
    | CREATE
    |--------------------------------------------------
    */
    public function create()
    {
        $invoices = Invoice::where('status', 'active')->get();
        $feeCategories = FeeCategory::where('is_active', true)->get();

        return view('invoice-items.create', compact('invoices', 'feeCategories'));
    }

    /*
    |--------------------------------------------------
    | STORE
    |--------------------------------------------------
    */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'fee_category_id' => 'required|exists:fee_categories,id',
            'amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $amount = (float) $validated['amount'];
        $discount = (float) ($validated['discount'] ?? 0);
        $subtotal = $amount - $discount;

        DB::beginTransaction();

        try {
            $item = InvoiceItem::create([
                'invoice_id' => $validated['invoice_id'],
                'fee_category_id' => $validated['fee_category_id'],
                'amount' => $amount,
                'discount' => $discount,
                'subtotal' => $subtotal,
            ]);

            // Recalculate invoice total
            $invoice = Invoice::find($validated['invoice_id']);
            $invoice->update([
                'total_amount' => $invoice->items()->sum('subtotal')
            ]);

            DB::commit();

            return redirect()->route('invoice-items.index')
                ->with('success', 'Invoice item created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create item: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------
    | SHOW
    |--------------------------------------------------
    */
    public function show(InvoiceItem $invoiceItem)
    {
        $invoiceItem->load(['invoice', 'feeCategory']);

        return view('invoice-items.show', compact('invoiceItem'));
    }

    /*
    |--------------------------------------------------
    | EDIT
    |--------------------------------------------------
    */
    public function edit(InvoiceItem $invoiceItem)
    {
        $invoiceItem->load(['invoice', 'feeCategory']);
        $invoices = Invoice::where('status', 'active')->get();
        $feeCategories = FeeCategory::where('is_active', true)->get();

        return view('invoice-items.edit', compact('invoiceItem', 'invoices', 'feeCategories'));
    }

    /*
    |--------------------------------------------------
    | UPDATE
    |--------------------------------------------------
    */
    public function update(Request $request, InvoiceItem $invoiceItem)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'fee_category_id' => 'required|exists:fee_categories,id',
            'amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $amount = (float) $validated['amount'];
        $discount = (float) ($validated['discount'] ?? 0);
        $subtotal = $amount - $discount;

        DB::beginTransaction();

        try {
            $invoiceItem->update([
                'invoice_id' => $validated['invoice_id'],
                'fee_category_id' => $validated['fee_category_id'],
                'amount' => $amount,
                'discount' => $discount,
                'subtotal' => $subtotal,
            ]);

            // Recalculate invoice total
            $invoice = Invoice::find($validated['invoice_id']);
            $invoice->update([
                'total_amount' => $invoice->items()->sum('subtotal')
            ]);

            DB::commit();

            return redirect()->route('invoice-items.index')
                ->with('success', 'Invoice item updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update item: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------
    | DESTROY
    |--------------------------------------------------
    */
    public function destroy(InvoiceItem $invoiceItem)
    {
        DB::beginTransaction();

        try {
            $invoiceId = $invoiceItem->invoice_id;
            
            $invoiceItem->delete();

            // Recalculate invoice total
            $invoice = Invoice::find($invoiceId);
            if ($invoice) {
                $invoice->update([
                    'total_amount' => $invoice->items()->sum('subtotal')
                ]);
            }

            DB::commit();

            return redirect()->route('invoice-items.index')
                ->with('success', 'Invoice item deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete item: ' . $e->getMessage());
        }
    }
}