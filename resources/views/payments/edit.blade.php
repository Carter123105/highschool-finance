@extends('layouts.app')

@section('content')

<div class="container">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Edit Payment</h5>
        </div>

        <div class="card-body">

            {{-- ERRORS --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $invoice = $payment->invoice;

                $invoiceTotal = floatval($invoice->total_amount ?? 0);

                $otherPayments = $invoice->payments->where('id', '!=', $payment->id);
                $otherPaid = floatval($otherPayments->sum('amount_paid'));

                $currentPayment = floatval($payment->amount_paid);

                $maxAllowed = max(0, $invoiceTotal - $otherPaid);

                $currentBalance = max(0, $invoiceTotal - ($otherPaid + $currentPayment));
            @endphp

            <form action="{{ route('payments.update', $payment->id) }}" method="POST">

                @csrf
                @method('PUT')

                {{-- ✅ FIX: REQUIRED PAYMENT DATE --}}
                <input type="hidden"
                       name="payment_date"
                       value="{{ $payment->payment_date ?? now()->format('Y-m-d') }}">

                {{-- INVOICE ID --}}
                <input type="hidden"
                       name="invoice_id"
                       value="{{ $payment->invoice_id }}">

                {{-- STUDENT --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Student</label>
                    <input type="text"
                           class="form-control"
                           value="{{ $payment->student->first_name ?? '' }} {{ $payment->student->last_name ?? '' }}"
                           disabled>
                </div>

                {{-- INVOICE --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Invoice No</label>
                    <input type="text"
                           class="form-control"
                           value="{{ $invoice->invoice_no ?? '' }}"
                           disabled>
                </div>

                {{-- TOTAL --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Total Invoice Amount</label>
                    <input type="text"
                           class="form-control bg-light fw-bold"
                           value="{{ number_format($invoiceTotal, 2) }}"
                           disabled>
                </div>

                {{-- OTHER PAYMENTS --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Other Payments Already Made</label>
                    <input type="text"
                           class="form-control bg-light"
                           value="{{ number_format($otherPaid, 2) }}"
                           disabled>
                </div>

                {{-- CURRENT PAYMENT --}}
                <div class="mb-3">
                    <label class="form-label fw-bold text-primary">Current Payment Amount</label>
                    <input type="text"
                           class="form-control bg-light text-primary fw-bold"
                           value="{{ number_format($currentPayment, 2) }}"
                           disabled>
                </div>

                {{-- BALANCE --}}
                <div class="mb-3">
                    <label class="form-label fw-bold text-danger">Current Invoice Balance</label>
                    <input type="text"
                           class="form-control bg-light text-danger fw-bold"
                           value="{{ number_format($currentBalance, 2) }}"
                           disabled>
                </div>

                {{-- AMOUNT --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Edit Amount Paid</label>

                    <input type="number"
                           step="0.01"
                           min="0.01"
                           max="{{ $maxAllowed }}"
                           name="amount_paid"
                           value="{{ old('amount_paid', $payment->amount_paid) }}"
                           class="form-control form-control-lg"
                           required>

                    <small class="text-muted d-block mt-2">
                        Maximum allowed amount:
                        <strong>{{ number_format($maxAllowed, 2) }}</strong>
                    </small>

                    <small class="text-danger d-block">
                        You cannot exceed the invoice total.
                    </small>
                </div>

                {{-- PAYMENT METHOD --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Payment Method</label>

                    <select name="payment_method" class="form-select" required>
                        <option value="Cash" @selected($payment->payment_method == 'Cash')>Cash</option>
                        <option value="Bank" @selected($payment->payment_method == 'Bank')>Bank</option>
                        <option value="Mobile Money" @selected($payment->payment_method == 'Mobile Money')>Mobile Money</option>
                    </select>
                </div>

                {{-- FEE BREAKDOWN --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Invoice Fee Breakdown</label>

                    <div class="border rounded p-3 bg-light">
                        @foreach($payment->invoice->invoiceItems as $item)
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <div>{{ $item->feeCategory->name ?? 'Unknown Fee' }}</div>
                                <div class="fw-bold">{{ number_format($item->subtotal, 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- SUBMIT --}}
                <button class="btn btn-success w-100 py-2">
                    Update Payment
                </button>

            </form>

        </div>

    </div>

</div>

@endsection