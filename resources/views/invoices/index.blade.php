@extends('layouts.app')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
.invoice-page {
    background: #f8fafc;
    min-height: 100vh;
    padding: 20px;
}

.page-header {
    background: linear-gradient(135deg, #0f172a, #1e293b);
    color: #fff;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.table-container {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}

.table th {
    background: #f1f5f9;
    font-size: 12px;
    text-transform: uppercase;
}

.btn-action {
    width: 34px;
    height: 34px;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    text-decoration: none;
}

.btn-action.delete:hover {
    color: #dc2626;
    border-color: #dc2626;
    background: #fee2e2;
}

.class-badge {
    font-size: 10px;
    padding: 2px 8px;
    border-radius: 12px;
    background: #dbeafe;
    color: #1e40af;
    font-weight: 600;
}

.excess-badge {
    font-size: 10px;
    padding: 2px 8px;
    border-radius: 12px;
    background: #fef3c7;
    color: #92400e;
    font-weight: 600;
}
</style>

@php
    $user = auth()->user();
    $role = $user->roles->first();
    $rolePermissions = $role ? $role->permissions->pluck('name')->toArray() : [];
    $canDeleteInvoice = in_array('delete invoices', $rolePermissions);
@endphp

<div class="container-fluid invoice-page">

    {{-- HEADER --}}
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h3 class="mb-0">Invoice Management</h3>
            <small class="text-light">Manage class invoices, student payments & balances</small>
        </div>

        <a href="{{ route('invoices.create') }}" class="btn btn-light">
            <i class="bi bi-plus-lg"></i> Create Invoice
        </a>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Total Expected (Invoice)</h6>
                    <h4 class="text-primary fw-bold">{{ number_format($invoices->sum('total_amount'), 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Total Collected</h6>
                    <h4 class="text-success fw-bold">{{ number_format($invoices->sum('total_collected'), 2) }}</h4>
                    <small class="text-muted">All student payments</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Applied to Invoices</h6>
                    <h4 class="text-info fw-bold">{{ number_format($invoices->sum('paid_amount'), 2) }}</h4>
                    <small class="text-muted">Capped at invoice totals</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Total Balance</h6>
                    <h4 class="text-danger fw-bold">{{ number_format($invoices->sum('balance'), 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-container">
        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Class / Type</th>
                        <th>Invoice Total</th>
                        <th>Applied Paid</th>
                        <th>Balance</th>
                        <th>Total Collected</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($invoices as $invoice)

                    @php
                        $status = strtolower($invoice->status ?? '');
                        $statusColor = match($status) {
                            'paid', 'fully paid' => 'success',
                            'partial', 'partially paid' => 'warning',
                            'unpaid', 'not paid' => 'danger',
                            default => 'secondary'
                        };
                        $statusLabel = match($status) {
                            'paid', 'fully paid' => 'Paid',
                            'partial', 'partially paid' => 'Partial',
                            'unpaid', 'not paid' => 'Unpaid',
                            default => ucfirst($status ?: 'Unknown')
                        };

                        $hasExcess = ($invoice->excess_collected ?? 0) > 0;
                    @endphp

                    <tr>

                        <td>
                            <div class="fw-bold">#{{ $invoice->invoice_no }}</div>
                            <span class="class-badge">CLASS INVOICE</span>
                        </td>

                        <td>
                            <div class="fw-bold">{{ $invoice->schoolClass->name ?? 'N/A' }}</div>
                            <small class="text-muted">
                                {{ $invoice->student_type ?? 'All' }} Students
                                @if($invoice->section_id)
                                    • {{ $invoice->section->name ?? '' }}
                                @endif
                            </small>
                        </td>

                        <td class="fw-bold text-primary">
                            {{ number_format($invoice->total_amount ?? 0, 2) }}
                        </td>

                        <td class="fw-bold text-success">
                            {{ number_format($invoice->paid_amount ?? 0, 2) }}
                        </td>

                        <td class="fw-bold {{ ($invoice->balance ?? 0) > 0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($invoice->balance ?? 0, 2) }}
                        </td>

                        <td>
                            <div class="fw-bold">{{ number_format($invoice->total_collected ?? 0, 2) }}</div>
                            @if($hasExcess)
                                <span class="excess-badge">
                                    +{{ number_format($invoice->excess_collected, 2) }} excess
                                </span>
                            @endif
                        </td>

                        <td>
                            <span class="badge bg-{{ $statusColor }}">
                                {{ $statusLabel }}
                            </span>
                        </td>

                        <td>
                            <small>{{ optional($invoice->created_at)->format('d M Y') }}</small>
                        </td>

                        <td class="d-flex gap-1">

                            <a href="{{ route('invoices.show', $invoice->id) }}"
                               class="btn-action"
                               title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('invoices.edit', $invoice->id) }}"
                               class="btn-action"
                               title="Edit Invoice">
                                <i class="bi bi-pencil"></i>
                            </a>

                            @if($canDeleteInvoice)
                                <form action="{{ route('invoices.destroy', $invoice->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this invoice permanently? This will also delete all associated payments.')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn-action delete"
                                            title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </form>
                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            No invoices found
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        @if($invoices->hasPages())
            <div class="p-3 border-top">
                {{ $invoices->links() }}
            </div>
        @endif

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@endsection