@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <h4 class="fw-bold mb-0">
            Students
        </h4>

        <a href="{{ route('students.create') }}"
           class="btn btn-primary">
            + Create Student
        </a>

    </div>

    {{-- FILTER BAR --}}
    <div class="card mb-3 shadow-sm border-0">

        <div class="card-body">

            <form method="GET" action="{{ route('students.index') }}">

                <div class="row g-3 align-items-end">

                    {{-- CLASS FILTER --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Filter by Class
                        </label>

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

                    {{-- SEARCH --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Search Student
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Search name or ID..."
                               value="{{ request('search') }}">

                    </div>

                    {{-- BUTTONS --}}
                    <div class="col-md-4 d-flex gap-2">

                        <button type="submit" class="btn btn-primary">
                            Filter
                        </button>

                        <a href="{{ route('students.index') }}" class="btn btn-secondary">
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-dark text-white">
            All Students ({{ $students->total() }})
        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Year</th>
                        <th>Gender</th>
                        <th>Student Type</th>
                        <th>Phone</th>
                        <th style="width: 200px;">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($students as $student)

                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $student->student_id }}</td>

                            <td class="fw-semibold">
                                {{ $student->first_name }} {{ $student->last_name }}
                            </td>

                            <td>{{ $student->schoolClass?->name ?? 'N/A' }}</td>

                            <td>{{ $student->section?->name ?? 'N/A' }}</td>

                            <td>{{ $student->academicYear?->name ?? 'N/A' }}</td>

                            <td>
                                <span class="badge bg-secondary">
                                    {{ $student->gender }}
                                </span>
                            </td>

                            <td>
                                @if($student->student_type == 'New')
                                    <span class="badge bg-success">New</span>
                                @elseif($student->student_type == 'Old')
                                    <span class="badge bg-warning text-dark">Old</span>
                                @else
                                    <span class="badge bg-light text-dark">N/A</span>
                                @endif
                            </td>

                            <td>{{ $student->phone ?? 'N/A' }}</td>

                            <td>
                                <div class="d-flex gap-1 flex-wrap">

                                    <a href="{{ route('students.show', $student->id) }}"
                                       class="btn btn-sm btn-info">
                                        View
                                    </a>

                                    <a href="{{ route('students.edit', $student->id) }}"
                                       class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form action="{{ route('students.destroy', $student->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this student?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-danger">
                                            Delete
                                        </button>

                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                No students found
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

            {{-- PAGINATION --}}
            @if($students->hasPages())

                <div class="students-pagination-wrapper">

                    <div class="pagination-info">
                        Showing
                        <strong>{{ $students->firstItem() }}</strong>
                        -
                        <strong>{{ $students->lastItem() }}</strong>
                        of
                        <strong>{{ $students->total() }}</strong>
                        students
                    </div>

                    <div class="pagination-links">

                        {{-- PREVIOUS --}}
                        @if ($students->onFirstPage())
                            <span class="pagination-btn disabled">Previous</span>
                        @else
                            <a href="{{ $students->previousPageUrl() }}"
                               class="pagination-btn">Previous</a>
                        @endif

                        {{-- PAGES --}}
                        @foreach ($students->getUrlRange(1, $students->lastPage()) as $page => $url)

                            @if ($page == $students->currentPage())
                                <span class="pagination-number active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="pagination-number">{{ $page }}</a>
                            @endif

                        @endforeach

                        {{-- NEXT --}}
                        @if ($students->hasMorePages())
                            <a href="{{ $students->nextPageUrl() }}"
                               class="pagination-btn">Next</a>
                        @else
                            <span class="pagination-btn disabled">Next</span>
                        @endif

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection

{{-- ================= PAGINATION CSS ================= --}}
<style>

.students-pagination-wrapper{
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:20px;
    margin-top:25px;
    padding:18px 22px;
    background:#fff;
    border-radius:16px;
    box-shadow:0 4px 18px rgba(0,0,0,0.05);
    border:1px solid #eef2f7;
}

.pagination-info{
    color:#64748b;
    font-size:14px;
}

.pagination-links{
    display:flex;
    align-items:center;
    gap:8px;
    flex-wrap:wrap;
}

.pagination-btn,
.pagination-number{
    text-decoration:none;
    min-width:42px;
    height:42px;
    padding:0 16px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:12px;
    font-weight:600;
    font-size:14px;
    transition:all .2s ease;
    border:1px solid #e2e8f0;
    background:#fff;
    color:#334155;
}

.pagination-number:hover,
.pagination-btn:hover{
    background:#2563eb;
    color:#fff;
    border-color:#2563eb;
    transform:translateY(-2px);
    box-shadow:0 6px 14px rgba(37,99,235,.18);
}

.pagination-number.active{
    background:#2563eb;
    color:#fff;
    border-color:#2563eb;
    box-shadow:0 6px 14px rgba(37,99,235,.20);
}

.pagination-btn.disabled{
    opacity:.5;
    pointer-events:none;
    background:#f8fafc;
}

@media(max-width:768px){
    .students-pagination-wrapper{
        flex-direction:column;
        align-items:flex-start;
    }

    .pagination-links{
        width:100%;
    }
}

</style>