@extends('layouts.app')

@section('content')

<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Payment Details</h4>

        <a href="{{ route('payments.index') }}" class="btn btn-secondary btn-sm">
            ← Back
        </a>
    </div>

    {{-- PAYMENT CARD --}}
    <div class="card shadow-sm border-0">

        <div class="card-body">

            {{-- RECEIPT HEADER --}}
            <div class="text-center border-bottom pb-3 mb-3">
                <h3 class="mb-0">SCHOOL PAYMENT RECEIPT</h3>
                <small class="text-muted">
                    Receipt #: {{ $payment->receipt_no }}
                </small>
            </div>

            {{-- TOP INFO --}}
            <div class="row mb-4">

                <div class="col-md-6">
                    <p><strong>Date:</strong>
                        {{ optional($payment->payment_date)->format('Y-m-d') }}
                    </p>

                    <p><strong>Payment Method:</strong>
                        {{ $payment->payment_method }}
                    </p>

                    <p><strong>Received By:</strong>
                        {{ $payment->receiver->name ?? 'N/A' }}
                    </p>
                </div>

                <div class="col-md-6 text-end">
                    <p><strong>Amount Paid:</strong></p>
                    <h4 class="text-success">
                        {{ number_format($payment->amount_paid, 2) }}
                    </h4>
                </div>

            </div>

            <hr>

            {{-- STUDENT INFO --}}
            <div class="mb-3">
                <h5>Student Information</h5>

                <p class="mb-1">
                    <strong>Name:</strong>
                    {{ $payment->student->first_name ?? '' }}
                    {{ $payment->student->last_name ?? '' }}
                </p>

                <p class="mb-1">
                    <strong>Student ID:</strong>
                    {{ $payment->student->student_id ?? 'N/A' }}
                </p>
            </div>

            <hr>

            {{-- INVOICE INFO --}}
            <div class="mb-3">
                <h5>Invoice Information</h5>

                <p class="mb-1">
                    <strong>Invoice No:</strong>
                    {{ $payment->invoice->invoice_no ?? 'N/A' }}
                </p>

                <p class="mb-1">
                    <strong>Total Invoice:</strong>
                    {{ number_format($payment->invoice->total_amount ?? 0, 2) }}
                </p>

                <p class="mb-1">
                    <strong>Total Paid:</strong>
                    {{ number_format($payment->invoice->paid_amount ?? 0, 2) }}
                </p>

                <p class="mb-1">
                    <strong>Balance:</strong>
                    {{ number_format($payment->invoice->balance ?? 0, 2) }}
                </p>

                <p class="mb-1">
                    <strong>Status:</strong>
                    <span class="badge bg-info">
                        {{ $payment->invoice->status ?? 'N/A' }}
                    </span>
                </p>
            </div>

            <hr>

            {{-- FEE BREAKDOWN --}}
            <div class="mb-3">
                <h5>Fee Breakdown</h5>

                @php
                    $items = $payment->invoice->invoiceItems ?? [];
                @endphp

                @forelse($items as $item)
                    <div class="d-flex justify-content-between border-bottom py-1">

                        <span>
                            {{ $item->feeCategory->name ?? 'Fee' }}
                        </span>

                        <span>
                            {{ number_format($item->paid_amount, 2) }}
                        </span>

                    </div>
                @empty
                    <p class="text-muted">No fee breakdown available</p>
                @endforelse

            </div>

            {{-- ACTIONS --}}
            <div class="text-end mt-4 no-print">

                <a href="{{ route('payments.edit', $payment->id) }}"
                   class="btn btn-warning btn-sm">
                    Edit
                </a>

                <form action="{{ route('payments.destroy', $payment->id) }}"
                      method="POST"
                      class="d-inline">

                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger btn-sm"
                            onclick="return confirm('Delete this payment?')">
                        Delete
                    </button>

                </form>

            </div>

        </div>
    </div>
</div>

{{-- PRINT STYLE --}}
<style>
@media print {
    .no-print {
        display: none;
    }
}
</style>

@endsection