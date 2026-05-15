@extends('layouts.app')

@section('content')

@php
    use App\Models\Invoice;
    use App\Models\Payment;

    /*
    |----------------------------------------------------
    | TOTAL BILLED (CLASS-BASED SYSTEM - SAFE)
    |----------------------------------------------------
    */
    $totalBilled = Invoice::where('class_id', $student->class_id)
        ->sum('total_amount');

    /*
    |----------------------------------------------------
    | TOTAL PAID BY STUDENT
    |----------------------------------------------------
    */
    $totalPaid = Payment::where('student_id', $student->id)
        ->sum('amount_paid');

    /*
    |----------------------------------------------------
    | BALANCE (ALWAYS SHOWS EVEN IF ZERO PAYMENT)
    |----------------------------------------------------
    */
    $balance = max(0, $totalBilled - $totalPaid);
@endphp

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1 text-dark">
                🎓 Student Payment Overview
            </h3>
            <p class="text-muted mb-0">
                Complete financial history for
                {{ $student->first_name }} {{ $student->last_name }}
            </p>
        </div>

        <a href="{{ url()->previous() }}" class="btn btn-dark btn-sm">
            <i class="bi bi-arrow-left"></i> Back
        </a>

    </div>

    {{-- SUMMARY CARDS --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card shadow-sm border-0 p-3 text-white bg-primary">
                <h6>Total Billed</h6>
                <h3 class="fw-bold">
                    {{ number_format($totalBilled, 2) }}
                </h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 p-3 text-white bg-success">
                <h6>Total Paid</h6>
                <h3 class="fw-bold">
                    {{ number_format($totalPaid, 2) }}
                </h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 p-3 text-white bg-danger">
                <h6>Outstanding Balance</h6>
                <h3 class="fw-bold">
                    {{ number_format($balance, 2) }}
                </h3>
            </div>
        </div>

    </div>

    {{-- STUDENT INFO --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-3">
                    <strong>Student ID</strong>
                    <div>{{ $student->student_id }}</div>
                </div>

                <div class="col-md-3">
                    <strong>Name</strong>
                    <div>{{ $student->first_name }} {{ $student->last_name }}</div>
                </div>

                <div class="col-md-3">
                    <strong>Class</strong>
                    <div>{{ $student->class_id }}</div>
                </div>

                <div class="col-md-3">
                    <strong>Status</strong><br>
                    <span class="badge bg-success">
                        {{ $student->status }}
                    </span>
                </div>

            </div>

        </div>

    </div>

    {{-- PAYMENT HISTORY --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <h5 class="mb-3">💳 Payment History</h5>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Receipt</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($payments as $payment)

                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <span class="badge bg-dark">
                                        {{ $payment->receipt_no }}
                                    </span>
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
                                    {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No payments found for this student.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection