@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Record Payment</h3>
            <p class="text-muted mb-0">Record payments for Old and New student invoices</p>
        </div>
        <a href="{{ route('payments.index') }}" class="btn btn-dark">← Back</a>
    </div>

    {{-- ERRORS --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FILTER FORM --}}
    <form method="GET" action="{{ route('payments.create') }}" class="mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white fw-bold">Filter Students & Invoices</div>
            <div class="card-body">
                <div class="row g-3">
                    {{-- CLASS --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Select Class</label>
                        <select name="class_id" class="form-select" onchange="this.form.submit()" required>
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ $selectedClass == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- STUDENT TYPE --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Student Type</label>
                        <select name="student_type" class="form-select" onchange="this.form.submit()" required>
                            <option value="">Select Type</option>
                            <option value="Old" {{ $selectedType == 'Old' ? 'selected' : '' }}>Old Students</option>
                            <option value="New" {{ $selectedType == 'New' ? 'selected' : '' }}>New Students</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- PAYMENT FORM --}}
    <form method="POST" action="{{ route('payments.store') }}">
        @csrf

        <input type="hidden" name="class_id" value="{{ $selectedClass }}">
        <input type="hidden" name="student_type" value="{{ $selectedType }}">

        <div class="row g-4">

            {{-- LEFT --}}
            <div class="col-lg-5">

                {{-- STUDENT --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-dark text-white fw-bold">Student Information</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Select Student</label>
                            <select name="student_id" id="student_id" class="form-select" required>
                                <option value="">Select Student</option>
                                @forelse($students as $student)
                                    <option value="{{ $student->id }}">
                                        {{ $student->first_name }} {{ $student->last_name }}
                                        ({{ $student->student_type }})
                                    </option>
                                @empty
                                    <option disabled>No students found</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="alert alert-info mb-0">
                            <strong>Student Type:</strong> {{ $selectedType ?: 'Not Selected' }}
                        </div>
                    </div>
                </div>

                {{-- INVOICE --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white fw-bold">Invoice Selection</div>
                    <div class="card-body">
                        <label class="form-label fw-semibold">Select Invoice</label>
                        <select name="invoice_id" id="invoice_id" class="form-select" required>
                            <option value="">Select Invoice</option>
                            @forelse($invoices as $invoice)
                                <option value="{{ $invoice->id }}" data-total="{{ $invoice->balance }}">
                                    {{ $invoice->invoice_no }} - {{ number_format($invoice->balance, 2) }}
                                    ({{ $invoice->student_type }})
                                </option>
                            @empty
                                <option disabled>No unpaid invoices found</option>
                            @endforelse
                        </select>

                        {{-- BALANCE --}}
                        <div class="mt-3">
                            <div class="border rounded p-3 bg-light">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-semibold">Invoice Type</span>
                                    <span id="invoiceTypeText" class="fw-bold text-primary">--</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semibold">Remaining Balance</span>
                                    <span id="balanceText" class="fw-bold text-danger">--</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- RIGHT --}}
            <div class="col-lg-7">

                {{-- INVOICE DETAILS --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-info text-white fw-bold">Invoice Details</div>
                    <div class="card-body">
                        <div class="alert alert-warning mb-0">
                            <strong>Important:</strong> Make sure you are paying the correct invoice for this student.
                        </div>
                    </div>
                </div>

                {{-- PAYMENT --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-secondary text-white fw-bold">Payment Information</div>
                    <div class="card-body">
                        {{-- AMOUNT --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Amount Paid</label>
                            <input type="number" step="0.01" min="0" name="amount_paid" id="amount_paid" class="form-control" required>
                            <small id="overpayWarning" class="text-danger d-none">⚠ Amount exceeds remaining balance</small>
                        </div>
                        {{-- DATE --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Payment Date</label>
                            <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" class="form-control" required>
                        </div>
                        {{-- METHOD --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Payment Method</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="Cash">Cash</option>
                                <option value="Bank">Bank</option>
                                <option value="Mobile Money">Mobile Money</option>
                            </select>
                        </div>
                        {{-- SUBMIT --}}
                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Save Payment</button>
                    </div>
                </div>

            </div>

        </div>

    </form>

</div>

{{-- JS --}}
<script>
let remainingBalance = 0;

function updateBalance() {
    let invoice = document.getElementById('invoice_id');
    let selected = invoice.options[invoice.selectedIndex];
    let total = selected.getAttribute('data-total');
    let typeText = selected.text.includes('Old') ? 'Old' : (selected.text.includes('New') ? 'New' : '--');

    if (!total) {
        document.getElementById('balanceText').innerText = '--';
        document.getElementById('invoiceTypeText').innerText = '--';
        return;
    }

    remainingBalance = parseFloat(total);
    document.getElementById('balanceText').innerText = 'LRD ' + remainingBalance.toFixed(2);
    document.getElementById('invoiceTypeText').innerText = typeText;
}

document.getElementById('invoice_id').addEventListener('change', updateBalance);

document.getElementById('amount_paid').addEventListener('input', function () {
    let value = parseFloat(this.value || 0);
    let warning = document.getElementById('overpayWarning');
    warning.classList.toggle('d-none', value <= remainingBalance);
});
</script>

@endsection