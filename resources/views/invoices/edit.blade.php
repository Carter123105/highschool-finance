@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">Edit Invoice</h3>
            <p class="text-muted">Update invoice details</p>
        </div>

        <a href="{{ route('invoices.index') }}" class="btn btn-dark">
            Back
        </a>
    </div>

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

    <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- STUDENT --}}
        <div class="card mb-3">
            <div class="card-body">
                <label class="form-label">Student</label>
                <select name="student_id" class="form-select" required>
                    @if(isset($students) && $students->count() > 0)
                        @foreach($students as $student)
                            <option value="{{ $student->id }}"
                                @selected($student->id == $invoice->student_id)>
                                {{ $student->first_name }} {{ $student->last_name }}
                            </option>
                        @endforeach
                    @else
                        <option value="" disabled selected>No students available</option>
                    @endif
                </select>
                @if(!isset($students) || $students->count() == 0)
                    <div class="text-danger mt-1 small">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        No students loaded. Please check your controller.
                    </div>
                @endif
            </div>
        </div>

        {{-- ACADEMIC YEAR --}}
        <div class="card mb-3">
            <div class="card-body">
                <label class="form-label">Academic Year</label>

                <select name="academic_year_id" class="form-select" required>
                    <option value="">Select Academic Year</option>

                    @if(isset($academicYears) && $academicYears->count() > 0)
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}"
                                @selected($year->id == $invoice->academic_year_id)>
                                {{ $year->name ?? $year->id }}
                            </option>
                        @endforeach
                    @else
                        <option value="" disabled>No academic years available</option>
                    @endif

                </select>
            </div>
        </div>

        {{-- FEE SELECT --}}
        <div class="card mb-3">
            <div class="card-body">

                <div class="row">
                    <div class="col-md-8">
                        <select id="feeTypeSelect" class="form-select">
                            <option value="">Select Fee Type</option>

                            @if(isset($feeCategories) && $feeCategories->count() > 0)
                                @foreach($feeCategories as $fee)
                                    <option value="{{ $fee->id }}"
                                        data-name="{{ $fee->name }}"
                                        data-amount="{{ $fee->amount ?? 0 }}">
                                        {{ $fee->name }}
                                    </option>
                                @endforeach
                            @else
                                <option value="" disabled>No fee categories available</option>
                            @endif

                        </select>
                    </div>

                    <div class="col-md-4">
                        <button type="button" class="btn btn-success w-100" id="addFeeBtn">
                            + Add Fee
                        </button>
                    </div>
                </div>

            </div>
        </div>

        {{-- TABLE --}}
        <div class="card">
            <div class="card-body">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Fee</th>
                            <th>Amount</th>
                            <th>Discount</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody id="invoiceTableBody">

                        @if(isset($invoice) && $invoice->invoiceItems && $invoice->invoiceItems->count() > 0)
                            @foreach($invoice->invoiceItems as $item)
                                <tr data-id="{{ $item->fee_category_id }}">

                                    <td>
                                        {{ $item->feeCategory->name ?? 'Unknown Fee' }}
                                        <input type="hidden" name="fee_category_id[]" value="{{ $item->fee_category_id }}">
                                    </td>

                                    <td>
                                        <input type="number" name="amount[]" class="form-control amount"
                                            value="{{ $item->amount }}" step="0.01">
                                    </td>

                                    <td>
                                        <input type="number" name="discount[]" class="form-control discount"
                                            value="{{ $item->discount }}" step="0.01">
                                    </td>

                                    <td>
                                        <input type="number" class="form-control subtotal"
                                            value="{{ $item->subtotal }}" readonly step="0.01">
                                    </td>

                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove">X</button>
                                    </td>

                                </tr>
                            @endforeach
                        @endif

                    </tbody>
                </table>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="fw-bold fs-5">
                        Total: <span id="grandTotal">{{ number_format($invoice->total_amount ?? 0, 2) }}</span>
                    </div>
                    <button class="btn btn-primary">
                        <i class="bi bi-save"></i> Update Invoice
                    </button>
                </div>

            </div>
        </div>

    </form>

</div>

{{-- JS --}}
<script>

let feeTypeSelect = document.getElementById('feeTypeSelect');
let addFeeBtn = document.getElementById('addFeeBtn');
let tbody = document.getElementById('invoiceTableBody');
let grandTotalEl = document.getElementById('grandTotal');

let added = [];

/* LOAD EXISTING ITEMS */
document.querySelectorAll('#invoiceTableBody tr').forEach(row => {
    if(row.dataset.id) added.push(row.dataset.id);
});

/* CALCULATE TOTALS */
function calculateTotals() {
    let grandTotal = 0;
    document.querySelectorAll('#invoiceTableBody tr').forEach(row => {
        let amount = parseFloat(row.querySelector('.amount')?.value) || 0;
        let discount = parseFloat(row.querySelector('.discount')?.value) || 0;
        let subtotal = Math.max(0, amount - discount);

        let subtotalInput = row.querySelector('.subtotal');
        if(subtotalInput) subtotalInput.value = subtotal.toFixed(2);

        grandTotal += subtotal;
    });

    if(grandTotalEl) {
        grandTotalEl.textContent = grandTotal.toFixed(2);
    }
}

/* ADD FEE */
addFeeBtn.onclick = function () {

    let option = feeTypeSelect.options[feeTypeSelect.selectedIndex];
    let id = option.value;

    if (!id) return alert('Select fee type');
    if (added.includes(id)) return alert('Already added');

    added.push(id);

    let name = option.dataset.name;
    let amount = parseFloat(option.dataset.amount) || 0;

    let row = `
        <tr data-id="${id}">
            <td>
                ${name}
                <input type="hidden" name="fee_category_id[]" value="${id}">
            </td>

            <td>
                <input type="number" name="amount[]" class="form-control amount" value="${amount.toFixed(2)}" step="0.01">
            </td>

            <td>
                <input type="number" name="discount[]" class="form-control discount" value="0.00" step="0.01">
            </td>

            <td>
                <input type="number" class="form-control subtotal" value="${amount.toFixed(2)}" readonly step="0.01">
            </td>

            <td>
                <button type="button" class="btn btn-danger btn-sm remove">X</button>
            </td>
        </tr>
    `;

    tbody.insertAdjacentHTML('beforeend', row);
    feeTypeSelect.value = '';
    calculateTotals();
};

/* REMOVE ROW */
document.addEventListener('click', e => {
    if (e.target.classList.contains('remove')) {
        let row = e.target.closest('tr');
        let id = row.dataset.id;

        added = added.filter(i => i !== id);
        row.remove();
        calculateTotals();
    }
});

/* AUTO-CALC ON INPUT CHANGE */
document.addEventListener('input', e => {
    if (e.target.classList.contains('amount') || e.target.classList.contains('discount')) {
        calculateTotals();
    }
});

/* INITIAL CALC */
calculateTotals();

</script>

@endsection