
fixed_code = r"""@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary: #4f46e5;
        --success: #059669;
        --warning: #d97706;
        --danger: #dc2626;
        --info: #0891b2;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-700: #334155;
        --gray-800: #1e293b;
        --radius: 10px;
        --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }

    .invoice-show {
        max-width: 1100px;
        margin: 0 auto;
    }

    .invoice-header {
        background: linear-gradient(135deg, var(--gray-800) 0%, #1e293b 100%);
        color: #fff;
        padding: 2rem;
        border-radius: var(--radius);
        margin-bottom: 1.5rem;
    }

    .invoice-header h2 {
        font-weight: 800;
        margin-bottom: 0.5rem;
    }

    .invoice-meta {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
        margin-top: 1rem;
        opacity: 0.9;
    }

    .meta-item {
        display: flex;
        flex-direction: column;
    }

    .meta-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        opacity: 0.7;
    }

    .meta-value {
        font-weight: 700;
        font-size: 1.125rem;
    }

    .status-badge-large {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1.25rem;
        border-radius: 9999px;
        font-weight: 700;
        font-size: 0.875rem;
    }

    .status-paid { background: rgba(5, 150, 105, 0.2); color: #34d399; }
    .status-partial { background: rgba(217, 119, 6, 0.2); color: #fbbf24; }
    .status-unpaid { background: rgba(220, 38, 38, 0.2); color: #f87171; }

    .card-modern {
        background: #fff;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--gray-100);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .card-header-modern {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--gray-100);
        font-weight: 700;
        color: var(--gray-800);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .table-modern {
        width: 100%;
        border-collapse: collapse;
    }

    .table-modern th {
        background: var(--gray-50);
        padding: 0.875rem 1.5rem;
        text-align: left;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--gray-700);
        font-weight: 700;
    }

    .table-modern td {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--gray-100);
    }

    .amount-cell {
        font-weight: 700;
        font-variant-numeric: tabular-nums;
    }

    .summary-row {
        background: var(--gray-50);
        font-weight: 700;
    }

    .summary-row td {
        border-top: 2px solid var(--gray-200);
    }

    .allocation-row {
        background: #f0f9ff;
    }

    .allocation-row td {
        padding: 0;
    }

    .nested-table {
        width: 100%;
        background: transparent;
    }

    .nested-table th {
        background: #e0f2fe;
        padding: 0.625rem 1.5rem;
        font-size: 0.6875rem;
    }

    .nested-table td {
        padding: 0.625rem 1.5rem;
        font-size: 0.875rem;
        border-bottom: 1px dashed #bae6fd;
    }

    .allocation-label {
        color: var(--info);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: rgba(255,255,255,0.1);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: var(--radius);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-back:hover {
        background: rgba(255,255,255,0.2);
        color: #fff;
    }

    .btn-action-bar {
        display: flex;
        gap: 0.75rem;
        margin-top: 1.5rem;
        flex-wrap: wrap;
    }

    .btn-modern {
        padding: 0.625rem 1.25rem;
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-primary { background: var(--primary); color: #fff; }
    .btn-warning { background: var(--warning); color: #fff; }
    .btn-danger { background: var(--danger); color: #fff; }
    .btn-info { background: var(--info); color: #fff; }

    .allocation-indent {
        padding-left: 2.5rem !important;
    }

    .text-muted-sm {
        color: #94a3b8;
        font-size: 0.75rem;
    }
</style>

<div class="container-fluid py-4 invoice-show">
    {{-- HEADER --}}
    <div class="invoice-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <a href="{{ route('invoices.index') }}" class="btn-back mb-3">
                    <i class="bi bi-arrow-left"></i> Back to Invoices
                </a>
                <h2>Invoice #{{ $invoice->invoice_no ?? 'N/A' }}</h2>
                <p class="mb-0 opacity-75">
                    Created {{ optional($invoice->created_at)->format('F d, Y \a\t h:i A') ?? 'N/A' }}
                </p>
            </div>
            @php
                $status = strtolower($invoice->status ?? 'unpaid');
                $statusClass = match($status) {
                    'paid' => 'status-paid',
                    'partial' => 'status-partial',
                    'unpaid' => 'status-unpaid',
                    default => 'status-unpaid',
                };
            @endphp
            <span class="status-badge-large {{ $statusClass }}">
                <i class="bi bi-circle-fill" style="font-size: 0.5rem;"></i>
                {{ ucfirst($status) }}
            </span>
        </div>

        <div class="invoice-meta">
            <div class="meta-item">
                <span class="meta-label">Student</span>
                <span class="meta-value">
                    {{ optional($invoice->student)->first_name ?? '' }} 
                    {{ optional($invoice->student)->last_name ?? 'N/A' }}
                </span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Admission No</span>
                <span class="meta-value">{{ optional($invoice->student)->admission_no ?? 'N/A' }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Class</span>
                <span class="meta-value">{{ optional($invoice->schoolClass)->name ?? 'N/A' }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Academic Year</span>
                <span class="meta-value">{{ optional($invoice->academicYear)->name ?? 'N/A' }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Created By</span>
                <span class="meta-value">{{ optional($invoice->createdBy)->name ?? 'System' }}</span>
            </div>
        </div>
    </div>

    {{-- PAYMENT HISTORY WITH ALLOCATIONS --}}
    <div class="card-modern">
        <div class="card-header-modern">
            <i class="bi bi-credit-card"></i> Payment History
            <span class="ms-auto badge bg-dark">{{ count($invoice->payments ?? collect()) }} payments</span>
        </div>
        
        @if(count($invoice->payments ?? collect()) > 0)
            <div class="table-responsive">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Receipt No</th>
                            <th>Date</th>
                            <th class="text-end">Amount Paid</th>
                            <th>Method</th>
                            <th>Allocated To</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->payments as $payment)
                            @php
                                $allocations = $payment->allocations ?? collect();
                                $allocationCount = $allocations->count();
                            @endphp
                            
                            {{-- Main Payment Row --}}
                            <tr>
                                <td class="fw-bold">{{ $payment->receipt_no ?? 'N/A' }}</td>
                                <td>{{ optional($payment->created_at)->format('d M Y, h:i A') ?? 'N/A' }}</td>
                                <td class="text-end amount-cell text-success">
                                    {{ number_format($payment->amount_paid ?? 0, 2) }}
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $payment->payment_method ?? 'Cash' }}
                                    </span>
                                </td>
                                <td>
                                    @if($allocationCount > 0)
                                        <span class="badge bg-info">
                                            {{ $allocationCount }} item{{ $allocationCount > 1 ? 's' : '' }}
                                        </span>
                                    @else
                                        <span class="badge bg-warning">Unallocated</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Allocation Details Row --}}
                            @if($allocationCount > 0)
                                <tr class="allocation-row">
                                    <td colspan="5">
                                        <table class="nested-table">
                                            <thead>
                                                <tr>
                                                    <th class="allocation-indent">Fee Category</th>
                                                    <th class="text-end">Allocated Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($allocations as $allocation)
                                                    <tr>
                                                        <td class="allocation-indent">
                                                            <span class="allocation-label">
                                                                <i class="bi bi-arrow-return-right"></i>
                                                                {{ optional(optional($allocation->invoiceItem)->feeCategory)->name ?? 'Item #'.($allocation->invoice_item_id ?? 'N/A') }}
                                                            </span>
                                                        </td>
                                                        <td class="text-end amount-cell">
                                                            {{ number_format($allocation->amount ?? 0, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr style="background: #e0f2fe;">
                                                    <td class="allocation-indent fw-bold">Total Allocated</td>
                                                    <td class="text-end amount-cell fw-bold">
                                                        {{ number_format($allocations->sum('amount'), 2) }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="summary-row">
                            <td colspan="2" class="text-end">Total Paid:</td>
                            <td class="text-end amount-cell text-success" style="font-size: 1.125rem;">
                                {{ number_format($invoice->paid_amount ?? 0, 2) }}
                            </td>
                            <td colspan="2"></td>
                        </tr>
                        <tr class="summary-row">
                            <td colspan="2" class="text-end">Remaining Balance:</td>
                            <td class="text-end amount-cell {{ ($invoice->balance ?? 0) > 0 ? 'text-danger' : 'text-success' }}" style="font-size: 1.125rem;">
                                {{ number_format($invoice->balance ?? 0, 2) }}
                            </td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="p-4 text-center text-muted">
                <i class="bi bi-credit-card fs-1 d-block mb-2"></i>
                No payments recorded yet.
            </div>
        @endif
    </div>

    {{-- PAYMENT ALLOCATIONS SUMMARY CARD --}}
    @if(count($invoice->payments ?? collect()) > 0)
        @php
            $allAllocations = collect();
            foreach($invoice->payments as $payment) {
                $allAllocations = $allAllocations->merge($payment->allocations ?? collect());
            }
        @endphp
        
        @if($allAllocations->count() > 0)
            <div class="card-modern">
                <div class="card-header-modern">
                    <i class="bi bi-diagram-3"></i> Payment Allocation Summary
                </div>
                <div class="table-responsive">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>Fee Category</th>
                                <th class="text-end">Invoice Amount</th>
                                <th class="text-end">Total Allocated</th>
                                <th class="text-end">Remaining</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->invoiceItems ?? collect() as $item)
                                @php
                                    $itemAllocations = $allAllocations->where('invoice_item_id', $item->id);
                                    $totalAllocated = $itemAllocations->sum('amount');
                                    $remaining = ($item->subtotal ?? ($item->amount - $item->discount)) - $totalAllocated;
                                    $itemStatus = $remaining <= 0 ? 'paid' : ($totalAllocated > 0 ? 'partial' : 'unpaid');
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ optional($item->feeCategory)->name ?? 'N/A' }}</strong>
                                    </td>
                                    <td class="text-end amount-cell">
                                        {{ number_format($item->subtotal ?? ($item->amount - $item->discount), 2) }}
                                    </td>
                                    <td class="text-end amount-cell text-success">
                                        {{ number_format($totalAllocated, 2) }}
                                    </td>
                                    <td class="text-end amount-cell {{ $remaining > 0 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format(max($remaining, 0), 2) }}
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $badgeClass = match($itemStatus) {
                                                'paid' => 'success',
                                                'partial' => 'warning',
                                                default => 'danger',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $badgeClass }}">
                                            {{ ucfirst($itemStatus) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endif

    {{-- ACTIONS --}}
    <div class="btn-action-bar">
        <a href="{{ route('invoices.edit', $invoice) }}" class="btn-modern btn-warning">
            <i class="bi bi-pencil"></i> Edit Invoice
        </a>
        <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="d-inline" 
              onsubmit="return confirm('Are you sure you want to delete this invoice? This action cannot be undone.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-modern btn-danger">
                <i class="bi bi-trash"></i> Delete Invoice
            </button>
        </form>
        <a href="#" onclick="window.print()" class="btn-modern btn-primary ms-auto">
            <i class="bi bi-printer"></i> Print Invoice
        </a>
    </div>
</div>
@endsection"""

