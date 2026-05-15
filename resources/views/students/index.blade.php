@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Students</h4>

        <a href="{{ route('students.create') }}" class="btn btn-primary">
            + Create Student
        </a>
    </div>

    {{-- FILTER BAR --}}
    <div class="card mb-3 shadow-sm border-0">
        <div class="card-body d-flex flex-wrap gap-3 align-items-center">

            {{-- CLASS FILTER (USING class_id) --}}
            <div style="min-width: 250px;">
                <label class="form-label fw-semibold">Filter by Class</label>

                <select id="classFilter" class="form-select">
                    <option value="">All Classes</option>

                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- SEARCH --}}
            <div style="min-width: 250px;">
                <label class="form-label fw-semibold">Search Student</label>
                <input type="text" id="searchInput" class="form-control" placeholder="Search name or ID...">
            </div>

            <div class="mt-4">
                <button class="btn btn-secondary" onclick="resetFilters()">
                    Reset
                </button>
            </div>

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

                    <tr class="student-row"
                        data-class="{{ $student->class_id }}">

                        <td>{{ $loop->iteration }}</td>

                        <td class="student-id">
                            {{ $student->student_id }}
                        </td>

                        <td class="fw-semibold student-name">
                            {{ $student->first_name }} {{ $student->last_name }}
                        </td>

                        {{-- CLASS DISPLAY (FIXED) --}}
                        <td>
                            {{ $student->schoolClass?->name ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $student->section?->name ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $student->academicYear?->name ?? 'N/A' }}
                        </td>

                        <td>
                            <span class="badge bg-secondary">
                                {{ $student->gender }}
                            </span>
                        </td>

                        {{-- STUDENT TYPE --}}
                        <td>
                            @if($student->student_type == 'New')
                                <span class="badge bg-success">New</span>
                            @elseif($student->student_type == 'Old')
                                <span class="badge bg-warning text-dark">Old</span>
                            @else
                                <span class="badge bg-light text-dark">N/A</span>
                            @endif
                        </td>

                        <td>
                            {{ $student->phone ?? 'N/A' }}
                        </td>

                        <td class="d-flex gap-1 flex-wrap">

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

                                <button class="btn btn-sm btn-danger">
                                    Delete
                                </button>

                            </form>

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

            <div class="mt-3">
                {{ $students->links() }}
            </div>

        </div>
    </div>

</div>

{{-- ================= FILTER SCRIPT ================= --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    const classFilter = document.getElementById("classFilter");
    const searchInput = document.getElementById("searchInput");
    const rows = document.querySelectorAll(".student-row");

    function filterTable() {

        const classValue = classFilter.value;
        const searchValue = searchInput.value.toLowerCase();

        rows.forEach(row => {

            const studentClass = row.dataset.class;
            const studentName = row.querySelector(".student-name").textContent.toLowerCase();
            const studentId = row.querySelector(".student-id").textContent.toLowerCase();

            const matchClass = classValue === "" || studentClass === classValue;
            const matchSearch = studentName.includes(searchValue) || studentId.includes(searchValue);

            row.style.display = (matchClass && matchSearch) ? "" : "none";
        });
    }

    classFilter.addEventListener("change", filterTable);
    searchInput.addEventListener("keyup", filterTable);

    window.resetFilters = function () {
        classFilter.value = "";
        searchInput.value = "";
        filterTable();
    }

});
</script>

@endsection