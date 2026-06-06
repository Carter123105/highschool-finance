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
</style>

@php
    $user = auth()->user();

    // 🔥 STRICT ROLE-BASED PERMISSION RESOLUTION (NO CACHE, NO GUESSING)
    $role = $user->roles->first();

    $rolePermissions = $role
        ? $role->permissions->pluck('name')->toArray()
        : [];

    // FINAL DECISION
    $canDeleteInvoice = in_array('delete invoices', $rolePermissions);
@endphp

<div class="container-fluid invoice-page">

    {{-- HEADER --}}
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h3 class="mb-0">Invoice Management</h3>
            <small class="text-light">Manage invoices, payments & balances</small>
        </div>

        <a href="{{ route('invoices.create') }}" class="btn btn-light">
            <i class="bi bi-plus-lg"></i> Create Invoice
        </a>
    </div>

    {{-- TABLE --}}
    <div class="table-container">
        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Student</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Balance</th>
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
                            'paid' => 'success',
                            'partial' => 'warning',
                            'unpaid' => 'danger',
                            default => 'secondary'
                        };
                    @endphp

                    <tr>

                        <td>#{{ $invoice->invoice_no }}</td>

                        <td>
                            {{ $invoice->student->first_name ?? '' }}
                            {{ $invoice->student->last_name ?? '' }}
                        </td>

                        <td>{{ number_format($invoice->total_amount ?? 0, 2) }}</td>
                        <td>{{ number_format($invoice->paid_amount ?? 0, 2) }}</td>
                        <td>{{ number_format($invoice->balance ?? 0, 2) }}</td>

                        <td>
                            <span class="badge bg-{{ $statusColor }}">
                                {{ ucfirst($status ?: 'Unknown') }}
                            </span>
                        </td>

                        <td>{{ optional($invoice->created_at)->format('d M Y') }}</td>

                        <td class="d-flex gap-1">

                            <a href="{{ route('invoices.show', $invoice->id) }}"
                               class="btn-action"
                               title="View">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('invoices.edit', $invoice->id) }}"
                               class="btn-action"
                               title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>

                            {{-- 🔥 FIXED DELETE (ROLE ONLY) --}}
                            @if($canDeleteInvoice)
                                <form action="{{ route('invoices.destroy', $invoice->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this invoice permanently?')">

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
                        <td colspan="8" class="text-center py-5">
                            No invoices found
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@endsection