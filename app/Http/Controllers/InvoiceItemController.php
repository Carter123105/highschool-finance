<?php

namespace App\Http\Controllers;

use App\Models\InvoiceItem;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceItemController extends Controller
{
    public function index()
    {
        $items = InvoiceItem::with(['invoice'])
            ->latest()
            ->paginate(20);

        return view('invoice-items.index', compact('items'));
    }

    public function create()
    {
        $invoices = Invoice::all();

        return view('invoice-items.create', compact('invoices'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'fee_category_id' => 'required',
            'amount' => 'required|numeric',
        ]);

        InvoiceItem::create($data);

        return redirect()->route('invoice-items.index')
            ->with('success', 'Invoice item created successfully');
    }
}