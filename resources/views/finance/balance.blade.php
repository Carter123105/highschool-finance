@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

@php
    $search = request('search');
    $classId = request('class_id');
    $studentType = request('student_type');
    $statusFilter = request('status');
    $yearId = request('academic_year_id') ?? session('academic_year_id');
@endphp

{{-- HEADER --}}
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold mb-1">Student Balance Report</h3>
        <p class="text-muted mb-0">
            Track balances by Old and New students
        </p>
    </div>

    <div class="d-flex gap-2 no-print">

        <form method="GET" class="d-flex gap-2">

            <select name="academic_year_id" class="form-select" onchange="this.form.submit()">

                <option value="">All Years</option>

                @foreach(\App\Models\AcademicYear::all() as $year)
                    <option value="{{ $year->id }}"
                        {{ $yearId == $year->id ? 'selected' : '' }}>
                        {{ $year->name }}
                    </option>
                @endforeach

            </select>

        </form>

        <button onclick="window.print()" class="btn btn-dark">
            <i class="bi bi-printer"></i> Print
        </button>

        <a href="{{ route('finance.balance.export', request()->all()) }}" class="btn btn-success">
            <i class="bi bi-download"></i> Export CSV
        </a>

    </div>

</div>

{{-- FILTERS --}}
<div class="card mb-4 shadow-sm no-print">

    <div class="card-body">

        <form method="GET">

            <input type="hidden" name="academic_year_id" value="{{ $yearId }}">

            <div class="row g-3">

                {{-- CLASS --}}
                <div class="col-md-3">
                    <label class="form-label">Class</label>
                    <select name="class_id" class="form-select">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}"
                                {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- STUDENT TYPE --}}
                <div class="col-md-3">
                    <label class="form-label">Student Type</label>
                    <select name="student_type" class="form-select">
                        <option value="">All Types</option>
                        <option value="Old" {{ $studentType == 'Old' ? 'selected' : '' }}>Old Students</option>
                        <option value="New" {{ $studentType == 'New' ? 'selected' : '' }}>New Students</option>
                    </select>
                </div>

                {{-- STATUS --}}
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        <option value="Fully Paid" {{ $statusFilter == 'Fully Paid' ? 'selected' : '' }}>Fully Paid</option>
                        <option value="Partially Paid" {{ $statusFilter == 'Partially Paid' ? 'selected' : '' }}>Partially Paid</option>
                        <option value="Not Paid" {{ $statusFilter == 'Not Paid' ? 'selected' : '' }}>Not Paid</option>
                    </select>
                </div>

                {{-- SEARCH --}}
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text"
                           name="search"
                           class="form-control"
                           value="{{ $search }}"
                           placeholder="Student name...">
                </div>

                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary">Filter</button>
                    <a href="{{ route('finance.balance') }}" class="btn btn-secondary">Reset</a>
                </div>

            </div>

        </form>

    </div>

</div>

{{-- SUMMARY BY STUDENT TYPE --}}
<div class="row mb-4">

    {{-- OLD STUDENTS --}}
    <div class="col-md-3">
        <div class="card p-3 shadow-sm border-warning">
            <h6 class="text-warning"><i class="bi bi-person-check"></i> Old Students</h6>
            <small>Count: {{ $oldCount ?? 0 }}</small><br>
            <small>Expected: {{ number_format($oldExpected ?? 0, 2) }}</small><br>
            <small>Paid: {{ number_format($oldPaid ?? 0, 2) }}</small><br>
            <h5 class="text-danger fw-bold">Balance: {{ number_format($oldBalance ?? 0, 2) }}</h5>
        </div>
    </div>

    {{-- NEW STUDENTS --}}
    <div class="col-md-3">
        <div class="card p-3 shadow-sm border-success">
            <h6 class="text-success"><i class="bi bi-person-plus"></i> New Students</h6>
            <small>Count: {{ $newCount ?? 0 }}</small><br>
            <small>Expected: {{ number_format($newExpected ?? 0, 2) }}</small><br>
            <small>Paid: {{ number_format($newPaid ?? 0, 2) }}</small><br>
            <h5 class="text-danger fw-bold">Balance: {{ number_format($newBalance ?? 0, 2) }}</h5>
        </div>
    </div>

    {{-- TOTAL PAID --}}
    <div class="col-md-3">
        <div class="card p-3 shadow-sm">
            <h6 class="text-success">Total Paid</h6>
            <h3 class="text-success">{{ number_format($grandPaid ?? 0, 2) }}</h3>
        </div>
    </div>

    {{-- TOTAL BALANCE --}}
    <div class="col-md-3">
        <div class="card p-3 shadow-sm">
            <h6 class="text-danger">Total Balance</h6>
            <h3 class="text-danger">{{ number_format($grandBalance ?? 0, 2) }}</h3>
        </div>
    </div>

</div>

{{-- TABLE --}}
<div class="card shadow-sm">

    <div class="card-body table-responsive">

        <table class="table table-bordered align-middle">

            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th>Type</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Invoice No</th>
                    <th>Expected</th>
                    <th>Paid</th>
                    <th>Balance</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

            @forelse($reports as $report)

                @php
                    $student = $report['student'];
                    $invoice = $report['invoice'];
                @endphp

                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td class="fw-bold">
                        {{ $student->first_name }} {{ $student->last_name }}
                    </td>

                    {{-- STUDENT TYPE BADGE --}}
                    <td>
                        @if($student->student_type == 'New')
                            <span class="badge bg-success">New</span>
                        @elseif($student->student_type == 'Old')
                            <span class="badge bg-warning text-dark">Old</span>
                        @else
                            <span class="badge bg-secondary">N/A</span>
                        @endif
                    </td>

                    <td>
                        {{ $student->schoolClass->name ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $student->section->name ?? 'N/A' }}
                    </td>

                    <td>
                        {{ $invoice?->invoice_no ?? 'N/A' }}
                    </td>

                    <td class="text-primary fw-bold">
                        {{ number_format($report['expected'] ?? 0, 2) }}
                    </td>

                    <td class="text-success fw-bold">
                        {{ number_format($report['paid'] ?? 0, 2) }}
                    </td>

                    <td class="fw-bold {{ ($report['balance'] ?? 0) > 0 ? 'text-danger' : 'text-success' }}">
                        {{ number_format($report['balance'] ?? 0, 2) }}
                    </td>

                    <td>
                        <span class="badge
                            @if($report['status'] == 'Fully Paid') bg-success
                            @elseif($report['status'] == 'Partially Paid') bg-warning text-dark
                            @else bg-danger @endif">

                            {{ $report['status'] }}
                        </span>
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="10" class="text-center text-muted py-4">
                        No records found
                    </td>
                </tr>

            @endforelse

            </tbody>

            <tfoot class="table-light">
                <tr>
                    <th colspan="6" class="text-end">GRAND TOTAL</th>
                    <th class="text-primary">{{ number_format($grandExpected ?? 0, 2) }}</th>
                    <th class="text-success">{{ number_format($grandPaid ?? 0, 2) }}</th>
                    <th class="text-danger">{{ number_format($grandBalance ?? 0, 2) }}</th>
                    <th></th>
                </tr>
            </tfoot>

        </table>

    </div>

</div>

{{-- Pagination --}}
<div class="d-flex justify-content-end mt-3 no-print">
    {{ $students->links() }}
</div>

</div>

@endsection