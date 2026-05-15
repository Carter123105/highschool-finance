@extends('layouts.app')

@section('content')

<div class="dashboard-container">

    {{-- TOP BAR --}}
    <div class="top-bar">
        <div class="page-title">
            <div class="title-icon">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <div class="title-text">
                <h1>Create Class Invoice</h1>
                <p>Bulk invoice generation for students by class and type</p>
            </div>
        </div>
        <a href="{{ route('invoices.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Invoices</span>
        </a>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="alert alert-success-modern">
            <div class="alert-icon"><i class="fas fa-check-circle"></i></div>
            <div class="alert-text">{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger-modern">
            <div class="alert-icon"><i class="fas fa-exclamation-circle"></i></div>
            <div class="alert-text">{{ session('error') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger-modern">
            <div class="alert-icon"><i class="fas fa-exclamation-circle"></i></div>
            <div class="alert-text">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('invoices.store') }}" method="POST">
        @csrf

        <div class="form-grid">

            {{-- LEFT: INVOICE SETUP --}}
            <div class="form-sidebar">
                <div class="card-modern">
                    <div class="card-header-modern primary">
                        <div class="header-icon"><i class="fas fa-sliders-h"></i></div>
                        <span>Invoice Setup</span>
                    </div>
                    <div class="card-body-modern">

                        {{-- CLASS --}}
                        <div class="form-group">
                            <label class="form-label">
                                Class <span class="required">*</span>
                            </label>
                            <div class="select-wrapper">
                                <select name="class_id" id="classSelect" class="form-select-modern" required>
                                    <option value="">Select Class</option>
                                    @foreach($classes ?? [] as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down select-arrow"></i>
                            </div>
                        </div>

                        {{-- STUDENT TYPE --}}
                        <div class="form-group">
                            <label class="form-label">
                                Student Type <span class="required">*</span>
                            </label>
                            <div class="select-wrapper">
                                <select name="student_type" id="studentType" class="form-select-modern" required>
                                    <option value="">Select Type</option>
                                    <option value="Old" {{ old('student_type') == 'Old' ? 'selected' : '' }}>Old Students</option>
                                    <option value="New" {{ old('student_type') == 'New' ? 'selected' : '' }}>New Students</option>
                                </select>
                                <i class="fas fa-chevron-down select-arrow"></i>
                            </div>
                            <small class="form-hint">Invoice will be created for all students of this type in the selected class</small>
                        </div>

                        {{-- SECTION - Hardcoded IDs from database diagnostic --}}
                        <div class="form-group">
                            <label class="form-label">Section</label>
                            <div class="select-wrapper">
                                <select name="section_id" id="sectionSelect" class="form-select-modern">
                                    <option value="">All Sections</option>
                                    <option value="9" {{ old('section_id') == '9' ? 'selected' : '' }}>Section A</option>
                                    <option value="1" {{ old('section_id') == '1' ? 'selected' : '' }}>Section B</option>
                                </select>
                                <i class="fas fa-chevron-down select-arrow"></i>
                            </div>
                        </div>

                        {{-- ACADEMIC YEAR --}}
                        <div class="form-group">
                            <label class="form-label">
                                Academic Year <span class="required">*</span>
                            </label>
                            <div class="select-wrapper">
                                <select name="academic_year_id" class="form-select-modern" required>
                                    <option value="">Select Academic Year</option>
                                    @foreach($academicYears ?? [] as $year)
                                        <option value="{{ $year->id }}" {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>
                                            {{ $year->name ?? $year->title ?? 'Academic Year' }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down select-arrow"></i>
                            </div>
                        </div>

                        {{-- DUE DATE --}}
                        <div class="form-group">
                            <label class="form-label">Due Date</label>
                            <div class="input-wrapper">
                                <input type="date" name="due_date" class="form-input-modern" value="{{ old('due_date') }}">
                                <i class="fas fa-calendar input-icon"></i>
                            </div>
                        </div>

                        {{-- PREVIEW COUNT --}}
                        <div class="preview-box" id="studentCountPreview">
                            <div class="preview-icon"><i class="fas fa-info-circle"></i></div>
                            <div class="preview-text">Select class and type to see affected students</div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- RIGHT: INVOICE ITEMS --}}
            <div class="form-main">
                <div class="card-modern">
                    <div class="card-header-modern success">
                        <div class="header-icon"><i class="fas fa-list-alt"></i></div>
                        <span>Invoice Items</span>
                        <button type="button" class="btn-add-item" id="addRowBtn">
                            <i class="fas fa-plus"></i>
                            <span>Add Item</span>
                        </button>
                    </div>
                    <div class="card-body-modern">

                        <div class="table-responsive">
                            <table class="modern-table" id="invoiceTable">
                                <thead>
                                    <tr>
                                        <th width="32%">Fee Category</th>
                                        <th width="20%">Amount</th>
                                        <th width="20%">Discount</th>
                                        <th width="20%">Subtotal</th>
                                        <th width="8%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="invoice-row">
                                        <td>
                                            <div class="select-wrapper compact">
                                                <select name="fee_category_id[]" class="form-select-modern fee-category" required>
                                                    <option value="">Select Fee</option>
                                                    @foreach($feeCategories ?? [] as $fee)
                                                        <option value="{{ $fee->id }}">{{ $fee->name }}</option>
                                                    @endforeach
                                                </select>
                                                <i class="fas fa-chevron-down select-arrow"></i>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" name="amount[]" class="form-input-modern amount" value="0" required>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" name="discount[]" class="form-input-modern discount" value="0">
                                        </td>
                                        <td>
                                            <input type="text" class="form-input-modern subtotal" value="0.00" readonly>
                                        </td>
                                        <td>
                                            <button type="button" class="btn-remove" title="Remove item">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- GRAND TOTAL --}}
                        <div class="grand-total-bar">
                            <div class="total-label">
                                <i class="fas fa-calculator"></i>
                                <span>Grand Total</span>
                            </div>
                            <div class="total-value" id="grandTotal">0.00</div>
                        </div>

                        {{-- SUBMIT --}}
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane"></i>
                            <span>Create Invoices for Students</span>
                        </button>

                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<style>
    /* ─── Design Tokens ─── */
    :root {
        --primary: #6366f1;
        --primary-dark: #4f46e5;
        --primary-light: #e0e7ff;
        --success: #10b981;
        --success-dark: #059669;
        --success-light: #d1fae5;
        --danger: #ef4444;
        --danger-light: #fee2e2;
        --warning: #f59e0b;
        --warning-light: #fef3c7;
        --dark: #0f172a;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-300: #cbd5e1;
        --gray-400: #94a3b8;
        --gray-500: #64748b;
        --gray-600: #475569;
        --gray-700: #334155;
        --gray-800: #1e293b;
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
        --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        --transition: 200ms cubic-bezier(0.4, 0, 0.2, 1);
    }

    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    @media (max-width: 768px) {
        .dashboard-container { padding: 1rem; }
    }

    /* ─── Top Bar ─── */
    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .page-title {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .title-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--primary), #8b5cf6);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    .title-text h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
        letter-spacing: -0.02em;
    }

    .title-text p {
        font-size: 0.875rem;
        color: var(--gray-500);
        margin: 0.25rem 0 0;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: var(--gray-800);
        color: white;
        font-size: 0.875rem;
        font-weight: 600;
        border: none;
        border-radius: var(--radius-md);
        text-decoration: none;
        transition: all var(--transition);
    }

    .btn-back:hover {
        background: var(--dark);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
        color: white;
        text-decoration: none;
    }

    /* ─── Alerts ─── */
    .alert {
        display: flex;
        align-items: flex-start;
        gap: 0.875rem;
        padding: 1rem 1.25rem;
        border-radius: var(--radius-md);
        margin-bottom: 1.5rem;
        border: 1px solid transparent;
        animation: slideDown 0.4s ease-out;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-12px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .alert-success-modern {
        background: var(--success-light);
        border-color: rgba(16, 185, 129, 0.2);
        color: #065f46;
    }

    .alert-danger-modern {
        background: var(--danger-light);
        border-color: rgba(239, 68, 68, 0.2);
        color: #991b1b;
    }

    .alert-icon {
        font-size: 1.25rem;
        flex-shrink: 0;
        margin-top: 0.125rem;
    }

    .alert-text {
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .alert-text ul {
        padding-left: 1.25rem;
        margin: 0;
    }

    .alert-text li {
        margin-bottom: 0.25rem;
    }

    /* ─── Form Grid ─── */
    .form-grid {
        display: grid;
        grid-template-columns: 380px 1fr;
        gap: 1.5rem;
        align-items: start;
    }

    @media (max-width: 1200px) {
        .form-grid { grid-template-columns: 1fr; }
    }

    /* ─── Card Modern ─── */
    .card-modern {
        background: white;
        border-radius: var(--radius-lg);
        border: 1px solid var(--gray-200);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        transition: box-shadow var(--transition);
    }

    .card-modern:hover {
        box-shadow: var(--shadow-md);
    }

    .card-header-modern {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        font-weight: 700;
        font-size: 0.9375rem;
        color: white;
    }

    .card-header-modern.primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    }

    .card-header-modern.success {
        background: linear-gradient(135deg, var(--success), var(--success-dark));
    }

    .header-icon {
        width: 32px;
        height: 32px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
    }

    .btn-add-item {
        margin-left: auto;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        font-size: 0.8125rem;
        font-weight: 600;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all var(--transition);
    }

    .btn-add-item:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-1px);
    }

    .card-body-modern {
        padding: 1.5rem;
    }

    /* ─── Form Elements ─── */
    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-label {
        display: block;
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 0.5rem;
    }

    .required {
        color: var(--danger);
        margin-left: 0.125rem;
    }

    .form-hint {
        display: block;
        font-size: 0.75rem;
        color: var(--gray-400);
        margin-top: 0.375rem;
        line-height: 1.4;
    }

    .select-wrapper {
        position: relative;
    }

    .select-wrapper.compact .form-select-modern {
        padding: 0.5rem 2rem 0.5rem 0.75rem;
        font-size: 0.8125rem;
    }

    .form-select-modern,
    .form-input-modern {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border: 1.5px solid var(--gray-200);
        border-radius: var(--radius-sm);
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--gray-700);
        background: white;
        transition: all var(--transition);
        appearance: none;
        -webkit-appearance: none;
    }

    .form-select-modern:focus,
    .form-input-modern:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-light);
    }

    .select-arrow {
        position: absolute;
        right: 0.875rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-400);
        font-size: 0.625rem;
        pointer-events: none;
    }

    .input-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        right: 0.875rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-400);
        font-size: 0.875rem;
        pointer-events: none;
    }

    /* ─── Preview Box ─── */
    .preview-box {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 1rem;
        background: var(--gray-50);
        border: 1.5px dashed var(--gray-300);
        border-radius: var(--radius-md);
        transition: all var(--transition);
    }

    .preview-box.success {
        background: var(--success-light);
        border-color: var(--success);
        border-style: solid;
    }

    .preview-box.warning {
        background: var(--warning-light);
        border-color: var(--warning);
        border-style: solid;
    }

    .preview-icon {
        font-size: 1.125rem;
        color: var(--gray-400);
        flex-shrink: 0;
        margin-top: 0.125rem;
    }

    .preview-box.success .preview-icon { color: var(--success); }
    .preview-box.warning .preview-icon { color: var(--warning); }

    .preview-text {
        font-size: 0.8125rem;
        color: var(--gray-600);
        line-height: 1.5;
    }

    .preview-box.success .preview-text { color: #065f46; }
    .preview-box.warning .preview-text { color: #92400e; }

    /* ─── Table ─── */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .modern-table thead th {
        background: var(--gray-50);
        color: var(--gray-500);
        font-size: 0.6875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 0.875rem 1rem;
        border-bottom: 1.5px solid var(--gray-200);
        text-align: left;
        white-space: nowrap;
    }

    .modern-table tbody td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--gray-100);
        vertical-align: middle;
    }

    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    .modern-table tbody tr:hover {
        background: rgba(99, 102, 241, 0.02);
    }

    /* ─── Remove Button ─── */
    .btn-remove {
        width: 32px;
        height: 32px;
        border: none;
        background: transparent;
        color: var(--gray-400);
        border-radius: var(--radius-sm);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all var(--transition);
    }

    .btn-remove:hover {
        background: var(--danger-light);
        color: var(--danger);
    }

    /* ─── Grand Total ─── */
    .grand-total-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.25rem;
        margin-top: 1.5rem;
        background: linear-gradient(135deg, var(--gray-800), var(--dark));
        border-radius: var(--radius-md);
        color: white;
    }

    .total-label {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        font-size: 0.9375rem;
        font-weight: 600;
    }

    .total-value {
        font-size: 1.25rem;
        font-weight: 700;
        font-variant-numeric: tabular-nums;
        letter-spacing: -0.02em;
    }

    /* ─── Submit Button ─── */
    .btn-submit {
        width: 100%;
        margin-top: 1.25rem;
        padding: 0.875rem;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        font-size: 0.9375rem;
        font-weight: 700;
        border: none;
        border-radius: var(--radius-md);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.625rem;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
        transition: all var(--transition);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.45);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    /* ─── Scrollbar ─── */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb {
        background: var(--gray-300);
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover { background: var(--gray-400); }
</style>

<script>
const studentsData = @json($students ?? []);
const classSelect = document.getElementById('classSelect');
const studentType = document.getElementById('studentType');
const sectionSelect = document.getElementById('sectionSelect');
const tableBody = document.querySelector('#invoiceTable tbody');
const addRowBtn = document.getElementById('addRowBtn');
const grandTotal = document.getElementById('grandTotal');
const previewBox = document.getElementById('studentCountPreview');

/* ─── Update Student Count Preview ─── */
function updatePreview() {
    let classId = classSelect.value;
    let type = studentType.value;
    let sectionId = sectionSelect.value;

    if (!classId || !type) {
        previewBox.className = 'preview-box';
        previewBox.innerHTML = `
            <div class="preview-icon"><i class="fas fa-info-circle"></i></div>
            <div class="preview-text">Select class and type to see affected students</div>
        `;
        return;
    }

    let classIdStr = String(classId);
    let typeStr = String(type).trim();
    let sectionIdStr = sectionId ? String(sectionId) : '';

    let matched = studentsData.filter(s => {
        let sClass = String(s.class_id ?? '');
        let sType = String(s.student_type ?? '').trim();
        let sSection = String(s.section_id ?? '');
        return sClass === classIdStr && sType === typeStr && (!sectionIdStr || sSection === sectionIdStr);
    });

    let count = matched.length;

    if (count > 0) {
        previewBox.className = 'preview-box success';
        previewBox.innerHTML = `
            <div class="preview-icon"><i class="fas fa-check-circle"></i></div>
            <div class="preview-text"><strong>${count} student(s)</strong> will receive this invoice</div>
        `;
    } else {
        previewBox.className = 'preview-box warning';
        let msg = sectionIdStr
            ? `No <strong>${typeStr}</strong> students found in this class and section`
            : `No <strong>${typeStr}</strong> students found in this class`;
        previewBox.innerHTML = `
            <div class="preview-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="preview-text">${msg}</div>
        `;
    }
}

classSelect.addEventListener('change', updatePreview);
studentType.addEventListener('change', updatePreview);
sectionSelect.addEventListener('change', updatePreview);

/* ─── Add Row ─── */
addRowBtn.addEventListener('click', function () {
    let firstRow = document.querySelector('.invoice-row');
    let newRow = firstRow.cloneNode(true);

    newRow.querySelectorAll('input').forEach(input => {
        if (input.classList.contains('subtotal')) {
            input.value = '0.00';
        } else {
            input.value = '0';
        }
    });

    newRow.querySelectorAll('select').forEach(select => {
        select.selectedIndex = 0;
    });

    tableBody.appendChild(newRow);
});

/* ─── Remove Row ─── */
document.addEventListener('click', function (e) {
    if (e.target.closest('.btn-remove')) {
        let rows = document.querySelectorAll('.invoice-row');
        if (rows.length > 1) {
            e.target.closest('tr').remove();
            calculateGrandTotal();
        }
    }
});

/* ─── Calculate Row ─── */
document.addEventListener('input', function (e) {
    if (e.target.classList.contains('amount') || e.target.classList.contains('discount')) {
        let row = e.target.closest('tr');
        let amount = parseFloat(row.querySelector('.amount').value || 0);
        let discount = parseFloat(row.querySelector('.discount').value || 0);
        let subtotal = Math.max(0, amount - discount);
        row.querySelector('.subtotal').value = subtotal.toFixed(2);
        calculateGrandTotal();
    }
});

/* ─── Grand Total ─── */
function calculateGrandTotal() {
    let total = 0;
    document.querySelectorAll('.subtotal').forEach(input => {
        total += parseFloat(input.value || 0);
    });
    grandTotal.innerText = total.toFixed(2);
}
</script>

@endsection