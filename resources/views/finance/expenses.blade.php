@extends('layouts.app')

@section('content')

<div class="container-fluid py-4 expenses-report-page">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

        <div>
            <h3 class="fw-bold mb-1">Expenses Report</h3>
            <p class="text-muted mb-0">
                View, manage, edit and delete school expenses
            </p>
        </div>

        <div class="d-flex gap-2 flex-wrap">

            {{-- SEARCH --}}
            <input type="text"
                   id="searchExpense"
                   class="form-control search-box"
                   placeholder="Search expenses...">

            {{-- ADD BUTTON --}}
            <a href="{{ route('expenses.create') }}"
               class="btn btn-danger fw-semibold px-4">

                <i class="bi bi-plus-circle me-1"></i>
                Add Expense

            </a>

        </div>

    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm border-0">
            {{ session('success') }}
        </div>
    @endif

    {{-- ERROR --}}
    @if(session('error'))
        <div class="alert alert-danger shadow-sm border-0">
            {{ session('error') }}
        </div>
    @endif

    {{-- CARD --}}
    <div class="card border-0 shadow-sm report-card">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table align-middle table-hover mb-0" id="expenseTable">

                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Added By</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($expenses as $expense)

                            <tr class="expense-row">

                                <td class="fw-semibold">
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    <div class="fw-bold text-dark">
                                        {{ $expense->title ?? 'N/A' }}
                                    </div>
                                </td>

                                <td>
                                    <span class="badge bg-primary px-3 py-2">
                                        {{ $expense->category ?? 'General' }}
                                    </span>
                                </td>

                                <td class="text-danger fw-bold">
                                    LRD {{ number_format($expense->amount, 2) }}
                                </td>

                                <td>
                                    {{ $expense->expense_date
                                        ? \Carbon\Carbon::parse($expense->expense_date)->format('d M Y')
                                        : $expense->created_at->format('d M Y') }}
                                </td>

                                <td>
                                    {{ $expense->user->name ?? 'System' }}
                                </td>

                                <td>

                                    <div class="d-flex justify-content-center gap-2">

                                        {{-- VIEW --}}
                                        <a href="{{ route('expenses.show', $expense->id) }}"
                                           class="btn btn-sm btn-info text-white action-btn">

                                            <i class="bi bi-eye"></i>

                                        </a>

                                        {{-- EDIT --}}
                                        <a href="{{ route('expenses.edit', $expense->id) }}"
                                           class="btn btn-sm btn-primary action-btn">

                                            <i class="bi bi-pencil-square"></i>

                                        </a>

                                        {{-- DELETE --}}
                                        <form action="{{ route('expenses.destroy', $expense->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this expense?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger action-btn">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="text-center py-5">

                                    <div class="text-muted">
                                        <i class="bi bi-receipt fs-1 d-block mb-3"></i>
                                        No expenses found
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

{{-- STYLES --}}
<style>

.expenses-report-page{
    background:#f5f7fb;
    min-height:100vh;
}

.report-card{
    border-radius:18px;
    overflow:hidden;
}

.table thead th{
    font-size:13px;
    letter-spacing:.5px;
    padding:16px;
    white-space:nowrap;
}

.table tbody td{
    padding:16px;
    vertical-align:middle;
    font-size:14px;
}

.table tbody tr:hover{
    background:#f8fbff;
}

.search-box{
    min-width:250px;
    border-radius:12px;
    padding:11px 15px;
    border:1px solid #dbe2ea;
}

.action-btn{
    width:36px;
    height:36px;
    border-radius:10px;
    display:flex;
    align-items:center;
    justify-content:center;
}

.btn{
    border-radius:12px;
}

.badge{
    font-size:12px;
    font-weight:600;
}

</style>

{{-- SEARCH --}}
<script>

document.getElementById('searchExpense').addEventListener('keyup', function () {

    let value = this.value.toLowerCase();

    document.querySelectorAll('.expense-row').forEach(row => {

        row.style.display = row.innerText.toLowerCase().includes(value)
            ? ''
            : 'none';

    });

});

</script>

@endsection