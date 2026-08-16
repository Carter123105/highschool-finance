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
            Per-student balances based on class invoice & payments
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
                    <label class="form-label">Payment Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="Fully Paid" {{ $statusFilter == 'Fully Paid' ? 'selected' : '' }}>Fully Paid</option>
                        <option value="Partially Paid" {{ $statusFilter == 'Partially Paid' ? 'selected' : '' }}>Partially Paid</option>
                        <option value="Not Paid" {{ $statusFilter == 'Not Paid' ? 'selected' : '' }}>Not Paid</option>
                        <option value="No Invoice" {{ $statusFilter == 'No Invoice' ? 'selected' : '' }}>No Invoice</option>
                    </select>
                </div>

                {{-- SEARCH --}}
                <div class="col-md-3">
                    <label class="form-label">Search Student</label>
                    <input type="text"
                           name="search"
                           class="form-control"
                           value="{{ $search }}"
                           placeholder="Name or ID...">
                </div>

                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
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
        <div class="card p-3 shadow-sm border-warning h-100">
            <h6 class="text-warning"><i class="bi bi-person-check"></i> Old Students</h6>
            <small>Count: <strong>{{ $oldCount ?? 0 }}</strong></small><br>
            <small>Expected: <strong>{{ number_format($oldExpected ?? 0, 2) }}</strong></small><br>
            <small>Paid: <strong class="text-success">{{ number_format($oldPaid ?? 0, 2) }}</strong></small><br>
            <h5 class="text-danger fw-bold mt-2 mb-0">Balance: {{ number_format($oldBalance ?? 0, 2) }}</h5>
        </div>
    </div>

    {{-- NEW STUDENTS --}}
    <div class="col-md-3">
        <div class="card p-3 shadow-sm border-success h-100">
            <h6 class="text-success"><i class="bi bi-person-plus"></i> New Students</h6>
            <small>Count: <strong>{{ $newCount ?? 0 }}</strong></small><br>
            <small>Expected: <strong>{{ number_format($newExpected ?? 0, 2) }}</strong></small><br>
            <small>Paid: <strong class="text-success">{{ number_format($newPaid ?? 0, 2) }}</strong></small><br>
            <h5 class="text-danger fw-bold mt-2 mb-0">Balance: {{ number_format($newBalance ?? 0, 2) }}</h5>
        </div>
    </div>

    {{-- TOTAL PAID --}}
    <div class="col-md-3">
        <div class="card p-3 shadow-sm h-100 text-center">
            <h6 class="text-muted">Total Paid</h6>
            <h3 class="text-success fw-bold">{{ number_format($grandPaid ?? 0, 2) }}</h3>
        </div>
    </div>

    {{-- TOTAL BALANCE --}}
    <div class="col-md-3">
        <div class="card p-3 shadow-sm h-100 text-center">
            <h6 class="text-muted">Total Balance</h6>
            <h3 class="text-danger fw-bold">{{ number_format($grandBalance ?? 0, 2) }}</h3>
        </div>
    </div>

</div>

{{-- TABLE --}}
<div class="card shadow-sm">

    <div class="card-body table-responsive p-0">

        <table class="table table-bordered table-hover align-middle mb-0">

            <thead class="table-dark">
                <tr>
                    <th class="text-center" style="width:40px;">#</th>
                    <th>Student</th>
                    <th>Type</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Invoice No</th>
                    <th class="text-end">Expected</th>
                    <th class="text-end">Paid</th>
                    <th class="text-end">Balance</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>

            <tbody>

            @forelse($reports as $report)

                @php
                    $student = $report['student'];
                    $invoice = $report['invoice'];
                @endphp

                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>

                    <td>
                        <div class="fw-bold">{{ $student->first_name }} {{ $student->last_name }}</div>
                        <small class="text-muted">ID: {{ $student->student_id ?? 'N/A' }}</small>
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

                    <td>{{ $student->schoolClass->name ?? 'N/A' }}</td>

                    <td>{{ $student->section->name ?? 'N/A' }}</td>

                    {{-- INVOICE NO FROM INVOICE TABLE --}}
                    <td>
                        @if($invoice)
                            <span class="badge bg-info text-dark">{{ $invoice->invoice_no }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>

                    {{-- EXPECTED FROM INVOICE TABLE --}}
                    <td class="text-end fw-bold text-primary">
                        {{ number_format($report['expected'] ?? 0, 2) }}
                    </td>

                    {{-- PAID FROM PAYMENTS TABLE (linked to invoice) --}}
                    <td class="text-end fw-bold text-success">
                        {{ number_format($report['paid'] ?? 0, 2) }}
                    </td>

                    {{-- BALANCE = EXPECTED - PAID --}}
                    <td class="text-end fw-bold {{ ($report['balance'] ?? 0) > 0 ? 'text-danger' : 'text-success' }}">
                        {{ number_format($report['balance'] ?? 0, 2) }}
                    </td>

                    {{-- STATUS BASED ON INVOICE EXPECTED VS PAID --}}
                    <td class="text-center">
                        @if($report['status'] == 'Fully Paid')
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle"></i> Fully Paid
                            </span>
                        @elseif($report['status'] == 'Partially Paid')
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-circle-half"></i> Partially Paid
                            </span>
                        @elseif($report['status'] == 'Not Paid')
                            <span class="badge bg-danger">
                                <i class="bi bi-x-circle"></i> Not Paid
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                <i class="bi bi-dash-circle"></i> No Invoice
                            </span>
                        @endif
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="10" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        No records found matching your filters
                    </td>
                </tr>

            @endforelse

            </tbody>

            <tfoot class="table-light fw-bold">
                <tr>
                    <td colspan="6" class="text-end">GRAND TOTAL</td>
                    <td class="text-end text-primary">{{ number_format($grandExpected ?? 0, 2) }}</td>
                    <td class="text-end text-success">{{ number_format($grandPaid ?? 0, 2) }}</td>
                    <td class="text-end text-danger">{{ number_format($grandBalance ?? 0, 2) }}</td>
                    <td></td>
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