@extends('layouts.app')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

        <div>
            <h4 class="fw-bold mb-1">
                Payment History:
                {{ $student->first_name }} {{ $student->last_name }}
            </h4>

            <small class="text-muted">
                Total Payments: {{ $payments->count() }}
            </small>
        </div>

        <a href="{{ route('payments.create') }}"
           class="btn btn-primary">
            + Add Payment
        </a>

    </div>

    {{-- TABLE --}}
    <div class="card shadow-sm border-0">

        <div class="card-body table-responsive p-0">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Invoice</th>
                        <th>Class</th>
                        <th>Installment</th>
                        <th>Amount Paid</th>
                        <th>Method</th>
                        <th>Date</th>
                        <th>Receipt No</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($payments as $payment)

                        @php
                            $invoice = $payment->invoice;

                            $total = $invoice->total_amount ?? 0;

                            // SAFE PAYMENT SUM (no crash)
                            $paid = $invoice
                                ? $invoice->payments->sum('amount_paid')
                                : 0;

                            $balance = $total - $paid;

                            if ($paid <= 0) {
                                $status = 'Not Paid';
                                $badge = 'secondary';
                            } elseif ($balance > 0) {
                                $status = 'Partially Paid';
                                $badge = 'warning';
                            } else {
                                $status = 'Fully Paid';
                                $badge = 'success';
                            }

                            // SAFE CLASS NAME
                            $className = $invoice?->schoolClass?->name
                                      ?? $invoice?->class?->name
                                      ?? 'N/A';

                            // SAFE DATE
                            $paymentDate = $payment->payment_date
                                ? \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d')
                                : 'N/A';
                        @endphp

                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <strong>
                                    {{ $invoice->invoice_no ?? 'N/A' }}
                                </strong>
                            </td>

                            <td>
                                <span class="badge bg-dark">
                                    {{ $className }}
                                </span>
                            </td>

                            <td>
                                @if($payment->feeCategory)
                                    <span class="badge bg-secondary">
                                        {{ $payment->feeCategory->name }}
                                    </span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>

                            <td>
                                <strong class="text-success">
                                    {{ number_format($payment->amount_paid, 2) }}
                                </strong>
                            </td>

                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ $payment->payment_method }}
                                </span>
                            </td>

                            <td>
                                {{ $paymentDate }}
                            </td>

                            <td>
                                {{ $payment->receipt_no ?? 'N/A' }}
                            </td>

                            <td>
                                <span class="badge bg-{{ $badge }}">
                                    {{ $status }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex gap-2 flex-wrap">

                                    <a href="{{ route('payments.receipt', $payment->id) }}"
                                       class="btn btn-primary btn-sm"
                                       target="_blank">
                                        Receipt
                                    </a>

                                    <a href="{{ route('payments.edit', $payment->id) }}"
                                       class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <form action="{{ route('payments.destroy', $payment->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this payment?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger btn-sm">
                                            Delete
                                        </button>

                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="10" class="text-center text-muted py-5">
                                No payments found for this student
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection