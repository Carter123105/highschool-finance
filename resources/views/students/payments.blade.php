@extends('layouts.app')

@push('styles')
<style>
    /* Custom tweaks for a softer, modern UI */
    .rounded-4 { border-radius: 1rem !important; }
    .icon-box { width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; }
    .table-custom th { font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; }
    .avatar-wrapper img { object-fit: cover; border: 3px solid #fff; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); }
</style>
@endpush

@section('content')

@php
    use App\Models\Invoice;
    use App\Models\Payment;

    // Note: For best MVC practices, consider moving these queries to your Controller
    // and passing $totalBilled, $totalPaid, and $balance to the view via compact().

    $totalBilled = Invoice::where('class_id', $student->class_id)->sum('total_amount');
    $totalPaid = Payment::where('student_id', $student->id)->sum('amount_paid');
    $balance = max(0, $totalBilled - $totalPaid);
@endphp

<div class="container-fluid py-4">

    {{-- HEADER SECTION --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                <i class="bi bi-wallet2 text-primary"></i> Payment Overview
            </h3>
            <p class="text-muted mb-0 fs-6">
                Financial history and summary for <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
            </p>
        </div>
        <div>
            <a href="{{ url()->previous() }}" class="btn btn-light border shadow-sm btn-sm px-3 py-2 rounded-3 fw-medium">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    {{-- FINANCIAL SUMMARY CARDS --}}
    <div class="row g-4 mb-4">
        
        {{-- Total Billed --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 icon-box me-3">
                        <i class="bi bi-receipt fs-3 text-primary"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total Billed</h6>
                        <h3 class="fw-bold mb-0 text-dark">
                            ${{ number_format($totalBilled, 2) }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Paid --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="rounded-circle bg-success bg-opacity-10 icon-box me-3">
                        <i class="bi bi-cash-stack fs-3 text-success"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total Paid</h6>
                        <h3 class="fw-bold mb-0 text-dark">
                            ${{ number_format($totalPaid, 2) }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Outstanding Balance --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="rounded-circle {{ $balance > 0 ? 'bg-danger' : 'bg-success' }} bg-opacity-10 icon-box me-3">
                        <i class="bi {{ $balance > 0 ? 'bi-exclamation-triangle text-danger' : 'bi-check-circle text-success' }} fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Outstanding Balance</h6>
                        <h3 class="fw-bold mb-0 {{ $balance > 0 ? 'text-danger' : 'text-success' }}">
                            ${{ number_format($balance, 2) }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- STUDENT PROFILE CARD --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-4">
                {{-- Dynamic Avatar --}}
                <div class="avatar-wrapper d-none d-sm-block">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($student->first_name . ' ' . $student->last_name) }}&background=random&size=80" 
                         alt="Student Avatar" 
                         class="rounded-circle">
                </div>

                <div class="row w-100 g-3">
                    <div class="col-sm-6 col-md-3">
                        <span class="text-muted d-block mb-1 small text-uppercase fw-semibold">Student ID</span>
                        <span class="fw-bold text-dark fs-5">{{ $student->student_id }}</span>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <span class="text-muted d-block mb-1 small text-uppercase fw-semibold">Full Name</span>
                        <span class="fw-bold text-dark fs-5">{{ $student->first_name }} {{ $student->last_name }}</span>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <span class="text-muted d-block mb-1 small text-uppercase fw-semibold">Class</span>
                        <span class="fw-medium text-dark fs-5">{{ $student->schoolClass->name ?? 'Unassigned' }}</span>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <span class="text-muted d-block mb-2 small text-uppercase fw-semibold">Status</span>
                        @if(strtolower($student->status) === 'active')
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">
                                <i class="bi bi-circle-fill small me-1"></i> {{ ucfirst($student->status) }}
                            </span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-2 rounded-pill">
                                {{ ucfirst($student->status) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- PAYMENT HISTORY TABLE --}}
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
            <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-clock-history text-primary"></i> Payment History
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover table-custom align-middle mb-0">
                    <thead class="text-muted border-bottom">
                        <tr>
                            <th scope="col" class="pb-3 text-center" width="5%">#</th>
                            <th scope="col" class="pb-3">Receipt No.</th>
                            <th scope="col" class="pb-3">Amount Paid</th>
                            <th scope="col" class="pb-3">Payment Method</th>
                            <th scope="col" class="pb-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($payments as $payment)
                            <tr>
                                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1 font-monospace">
                                        {{ $payment->receipt_no }}
                                    </span>
                                </td>
                                <td>
                                    <strong class="text-dark">
                                        ${{ number_format($payment->amount_paid, 2) }}
                                    </strong>
                                </td>
                                <td>
                                    @php
                                        // Optional: Color code payment methods dynamically
                                        $methodClass = match(strtolower($payment->payment_method)) {
                                            'cash' => 'bg-success text-success',
                                            'bank transfer' => 'bg-primary text-primary',
                                            'card' => 'bg-info text-info',
                                            default => 'bg-secondary text-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $methodClass }} bg-opacity-10 border border-opacity-25 px-2 py-1">
                                        {{ ucfirst($payment->payment_method) }}
                                    </span>
                                </td>
                                <td class="text-muted">
                                    <i class="bi bi-calendar3 small me-1"></i>
                                    {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center justify-content-center text-muted gap-2">
                                        <i class="bi bi-inbox fs-1 opacity-50"></i>
                                        <p class="mb-0 fs-6">No payments have been recorded for this student yet.</p>
                                    </div>
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