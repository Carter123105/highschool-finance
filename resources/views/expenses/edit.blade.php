@extends('layouts.app')

@section('content')

<div class="container-fluid py-4 expense-edit-page">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

        <div>
            <h2 class="fw-bold mb-1 text-dark">Edit Expense</h2>
            <p class="text-muted mb-0">Update expense record details</p>
        </div>

        <a href="{{ route('expenses.index') }}" class="btn btn-outline-dark btn-sm">
            <i class="bi bi-arrow-left"></i> Back
        </a>

    </div>

    {{-- CARD --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            {{-- ERRORS --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORM --}}
            <form action="{{ route('expenses.update', $expense->id) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- TITLE --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Expense Title</label>
                        <input type="text"
                               name="title"
                               class="form-control"
                               value="{{ old('title', $expense->title) }}"
                               required>
                    </div>

                    {{-- AMOUNT --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Amount</label>
                        <input type="number"
                               step="0.01"
                               name="amount"
                               class="form-control"
                               value="{{ old('amount', $expense->amount) }}"
                               required>
                    </div>

                    {{-- CATEGORY --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Category</label>

                        <select name="category" class="form-select">

                            <option value="">-- Select Category --</option>

                            <option value="Salary"
                                {{ old('category', $expense->category) == 'Salary' ? 'selected' : '' }}>
                                Salary
                            </option>

                            <option value="Transport"
                                {{ old('category', $expense->category) == 'Transport' ? 'selected' : '' }}>
                                Transport
                            </option>

                            <option value="Maintenance"
                                {{ old('category', $expense->category) == 'Maintenance' ? 'selected' : '' }}>
                                Maintenance
                            </option>

                            <option value="Utility"
                                {{ old('category', $expense->category) == 'Utility' ? 'selected' : '' }}>
                                Utility
                            </option>

                            <option value="Office"
                                {{ old('category', $expense->category) == 'Office' ? 'selected' : '' }}>
                                Office Supplies
                            </option>

                            <option value="Other"
                                {{ old('category', $expense->category) == 'Other' ? 'selected' : '' }}>
                                Other
                            </option>

                        </select>
                    </div>

                    {{-- DATE --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Expense Date</label>

                        <input type="date"
                               name="expense_date"
                               class="form-control"
                               value="{{ old('expense_date', $expense->expense_date ? $expense->expense_date->format('Y-m-d') : '') }}">
                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">Description</label>

                        <textarea name="description"
                                  class="form-control"
                                  rows="4">{{ old('description', $expense->description) }}</textarea>
                    </div>

                    {{-- BUTTON --}}
                    <div class="col-12 d-flex justify-content-end mt-3">

                        <button type="submit" class="btn btn-primary px-4">

                            <i class="bi bi-save me-1"></i>
                            Update Expense

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- STYLE --}}
<style>

.expense-edit-page{
    background:#f4f7fb;
    min-height:100vh;
}

.card{
    border-radius:16px;
}

.form-label{
    font-size:14px;
    color:#334155;
}

.form-control,
.form-select{
    border-radius:10px;
    padding:10px 12px;
    border:1px solid #e2e8f0;
    font-size:14px;
}

.form-control:focus,
.form-select:focus{
    box-shadow:none;
    border-color:#2563eb;
}

.btn-primary{
    border-radius:10px;
    font-weight:600;
}

</style>

@endsection