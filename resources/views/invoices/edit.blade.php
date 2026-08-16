@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">🎓 School Finance</h2>
                    <p class="text-muted mb-0">Admin</p>
                </div>
            </div>

            <h4>Edit Class Invoice</h4>
            <p class="text-muted">Update fee structure for {{ $invoice->schoolClass->name ?? 'N/A' }} — Academic Year {{ $invoice->academicYear->name ?? 'N/A' }}</p>

            <a href="{{ route('invoices.index') }}" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Back to Invoices
            </a>

            {{-- Error Display --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <h5><i class="fas fa-exclamation-triangle"></i> Please fix the following errors:</h5>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Form --}}
            <form action="{{ route('invoices.update', $invoice) }}" method="POST" id="invoiceForm">
                @csrf
                @method('PUT')

                {{-- HIDDEN REQUIRED FIELDS --}}
                <input type="hidden" name="class_id" value="{{ $invoice->class_id }}">
                <input type="hidden" name="student_type" value="{{ $invoice->student_type }}">
                <input type="hidden" name="academic_year_id" value="{{ $invoice->academic_year_id }}">
                <input type="hidden" name="section_id" value="{{ $invoice->section_id }}">

                {{-- TRACK DELETED ITEMS --}}
                <input type="hidden" name="deleted_items" id="deletedItems" value="">

                <div class="card">
                    <div class="card-body">

                        <h5 class="card-title mb-3">Invoice Settings</h5>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Invoice Number</label>
                                    <input type="text" class="form-control" value="{{ $invoice->invoice_no }}" disabled>
                                    <small class="text-muted">Auto-generated, cannot be changed</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Class</label>
                                    <input type="text" class="form-control" value="{{ $invoice->schoolClass->name ?? 'N/A' }}" disabled>
                                    <small class="text-muted">Class cannot be changed</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Academic Year</label>
                                    <input type="text" class="form-control" value="{{ $invoice->academicYear->name ?? 'N/A' }}" disabled>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Applies To (Student Type)</label>
                                    <input type="text" class="form-control" value="{{ $invoice->student_type }} Students" disabled>
                                    <small class="text-muted">Student type cannot be changed</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Due Date</label>
                                    <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" 
                                           value="{{ old('due_date', $invoice->due_date ? date('Y-m-d', strtotime($invoice->due_date)) : '') }}">
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Invoice Status</label>
                                    <input type="text" class="form-control" value="{{ $invoice->status }}" disabled>
                                    <small class="text-muted">Status is auto-calculated based on payments</small>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Class-Level Invoice:</strong> Changes here will affect the fee structure for all students in this class. 
                            Individual student invoices will be generated based on this template.
                        </div>

                        <hr>

                        {{-- FEE STRUCTURE TABLE --}}
                        <h5 class="mb-3">Fee Structure <span class="badge bg-secondary" id="itemCount">{{ $invoice->invoiceItems->count() }} items</span></h5>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="feeTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 30%;">Fee Category <span class="text-danger">*</span></th>
                                        <th style="width: 20%;">Amount ($) <span class="text-danger">*</span></th>
                                        <th style="width: 20%;">Discount ($)</th>
                                        <th style="width: 20%;">Subtotal ($)</th>
                                        <th style="width: 10%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="feeItemsBody">
                                    @php
                                        $oldItems = old('fee_category_id');
                                        $items = [];

                                        if ($oldItems !== null) {
                                            foreach ($oldItems as $i => $catId) {
                                                $items[] = [
                                                    'invoice_item_id' => old('invoice_item_id.'.$i),
                                                    'fee_category_id' => $catId,
                                                    'amount' => old('amount.'.$i, 0),
                                                    'discount' => old('discount.'.$i, 0),
                                                ];
                                            }
                                        } else {
                                            foreach ($invoice->invoiceItems as $item) {
                                                $items[] = [
                                                    'invoice_item_id' => $item->id,
                                                    'fee_category_id' => $item->fee_category_id,
                                                    'amount' => $item->amount,
                                                    'discount' => $item->discount ?? 0,
                                                ];
                                            }
                                        }

                                        if (empty($items)) {
                                            $items = [['invoice_item_id' => null, 'fee_category_id' => '', 'amount' => 0, 'discount' => 0]];
                                        }
                                    @endphp

                                    @foreach($items as $index => $item)
                                        @php
                                            $invoiceItemId = $item['invoice_item_id'] ?? null;
                                            $feeCategoryId = $item['fee_category_id'] ?? '';
                                            $amount = $item['amount'] ?? 0;
                                            $discount = $item['discount'] ?? 0;
                                            $subtotal = max(0, (float)$amount - (float)$discount);
                                        @endphp

                                        <tr class="fee-item-row" data-index="{{ $index }}" data-item-id="{{ $invoiceItemId ?? '' }}">
                                            <input type="hidden" name="invoice_item_id[{{ $index }}]" value="{{ $invoiceItemId }}">
                                            <td>
                                                <select name="fee_category_id[{{ $index }}]" 
                                                        class="form-select form-select-sm fee-category-select @error('fee_category_id.'.$index) is-invalid @enderror" 
                                                        required>
                                                    <option value="">-- Select Category --</option>
                                                    @foreach($feeCategories as $category)
                                                        <option value="{{ $category->id }}" {{ $feeCategoryId == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('fee_category_id.'.$index)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number" 
                                                       name="amount[{{ $index }}]" 
                                                       class="form-control form-control-sm amount-input @error('amount.'.$index) is-invalid @enderror" 
                                                       value="{{ number_format((float)$amount, 2, '.', '') }}" 
                                                       step="0.01" 
                                                       min="0" 
                                                       required>
                                                @error('amount.'.$index)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="number" 
                                                       name="discount[{{ $index }}]" 
                                                       class="form-control form-control-sm discount-input" 
                                                       value="{{ number_format((float)$discount, 2, '.', '') }}" 
                                                       step="0.01" 
                                                       min="0">
                                            </td>
                                            <td class="align-middle text-end">
                                                <span class="subtotal-display fw-bold">{{ number_format($subtotal, 2) }}</span>
                                                <input type="hidden" class="subtotal-input" value="{{ $subtotal }}">
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm remove-row" onclick="removeRow(this)" {{ count($items) <= 1 ? 'disabled' : '' }}>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-end">
                                            <button type="button" class="btn btn-success btn-sm" id="addRowBtn">
                                                <i class="fas fa-plus"></i> Add Another Fee Category
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td colspan="3" class="text-end fw-bold fs-5">Total Invoice Amount</td>
                                        <td colspan="2" class="fw-bold fs-5 text-primary text-end">
                                            $<span id="totalAmount">{{ number_format($invoice->total_amount ?? 0, 2) }}</span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-users"></i>
                            Applied to all students in this class
                        </div>

                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
(function() {
    'use strict';

    let rowIndex = {{ count($items) }};
    let deletedItems = [];

    // Pre-build category options for new rows
    const categoryOptions = [
        @foreach($feeCategories as $category)
            { id: {{ $category->id }}, name: "{{ addslashes($category->name) }}" },
        @endforeach
    ];

    function buildCategorySelect(name) {
        let html = `<select name="${name}" class="form-select form-select-sm fee-category-select" required><option value="">-- Select Category --</option>`;
        categoryOptions.forEach(cat => {
            html += `<option value="${cat.id}">${cat.name}</option>`;
        });
        html += `</select>`;
        return html;
    }

    document.getElementById('addRowBtn').addEventListener('click', function() {
        const tbody = document.getElementById('feeItemsBody');
        const newRow = document.createElement('tr');
        newRow.className = 'fee-item-row';
        newRow.setAttribute('data-index', rowIndex);
        newRow.setAttribute('data-item-id', '');

        newRow.innerHTML = `
            <input type="hidden" name="invoice_item_id[${rowIndex}]" value="">
            <td>${buildCategorySelect('fee_category_id[' + rowIndex + ']')}</td>
            <td>
                <input type="number" name="amount[${rowIndex}]" class="form-control form-control-sm amount-input" value="0.00" step="0.01" min="0" required>
            </td>
            <td>
                <input type="number" name="discount[${rowIndex}]" class="form-control form-control-sm discount-input" value="0.00" step="0.01" min="0">
            </td>
            <td class="align-middle text-end">
                <span class="subtotal-display fw-bold">0.00</span>
                <input type="hidden" class="subtotal-input" value="0">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-row" onclick="removeRow(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;

        tbody.appendChild(newRow);
        rowIndex++;
        updateItemCount();
        attachCalculations(newRow);
        document.querySelectorAll('.remove-row').forEach(btn => btn.disabled = false);
    });

    window.removeRow = function(button) {
        const row = button.closest('tr');
        const tbody = document.getElementById('feeItemsBody');
        const itemId = row.getAttribute('data-item-id');

        // Track deleted existing items
        if (itemId && itemId !== '') {
            deletedItems.push(itemId);
            document.getElementById('deletedItems').value = deletedItems.join(',');
        }

        if (tbody.children.length > 1) {
            row.remove();
            reindexRows();
            calculateTotal();
            updateItemCount();

            if (tbody.children.length === 1) {
                tbody.querySelector('.remove-row').disabled = true;
            }
        } else {
            alert('At least one fee item is required.');
        }
    };

    function reindexRows() {
        const rows = document.querySelectorAll('.fee-item-row');
        rows.forEach((row, index) => {
            row.setAttribute('data-index', index);
            const itemIdInput = row.querySelector('input[name^="invoice_item_id"]');
            if (itemIdInput) itemIdInput.name = `invoice_item_id[${index}]`;
            row.querySelector('.fee-category-select').name = `fee_category_id[${index}]`;
            row.querySelector('.amount-input').name = `amount[${index}]`;
            row.querySelector('.discount-input').name = `discount[${index}]`;
        });
        rowIndex = rows.length;
    }

    function updateItemCount() {
        const count = document.querySelectorAll('.fee-item-row').length;
        document.getElementById('itemCount').textContent = count + ' items';
    }

    function calculateRow(row) {
        const amount = parseFloat(row.querySelector('.amount-input').value) || 0;
        const discount = parseFloat(row.querySelector('.discount-input').value) || 0;
        const subtotal = Math.max(0, amount - discount);

        row.querySelector('.subtotal-display').textContent = subtotal.toFixed(2);
        row.querySelector('.subtotal-input').value = subtotal;
    }

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal-input').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('totalAmount').textContent = total.toFixed(2);
    }

    function attachCalculations(row) {
        const amountInput = row.querySelector('.amount-input');
        const discountInput = row.querySelector('.discount-input');

        amountInput.addEventListener('input', function() {
            calculateRow(row);
            calculateTotal();
        });

        discountInput.addEventListener('input', function() {
            calculateRow(row);
            calculateTotal();
        });
    }

    // Initialize
    document.querySelectorAll('.fee-item-row').forEach(row => {
        attachCalculations(row);
    });
    calculateTotal();
})();
</script>
@endsection