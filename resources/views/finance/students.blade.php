@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Students Finance View</h3>

    {{-- 🔍 FILTER SECTION --}}
    <div class="card mb-4">
        <div class="card-header bg-light">
            <strong>Filter Students</strong>
        </div>
        <div class="card-body">
            <form action="{{ url()->current() }}" method="GET" class="row align-items-end">
                
                {{-- Class Filter --}}
                <div class="col-md-4 mb-3 mb-md-0">
                    <label for="class_id" class="form-label text-muted small uppercase font-weight-bold">Class</label>
                    <select name="class_id" id="class_id" class="form-control custom-select">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Section Filter --}}
                <div class="col-md-4 mb-3 mb-md-0">
                    <label for="section_id" class="form-label text-muted small uppercase font-weight-bold">Section</label>
                    <select name="section_id" id="section_id" class="form-control custom-select">
                        <option value="">All Sections</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Action Buttons --}}
                <div class="col-md-4">
                    <div class="d-flex">
                        <button type="submit" class="btn btn-primary flex-grow-1 mr-2">
                            Apply Filter
                        </button>
                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary">
                            Reset
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </div>

    {{-- 📊 DATA TABLE SECTION --}}
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Total Paid</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($students as $student)
                        <tr>
                            {{-- ✅ FIXED NAME --}}
                            <td>
                                {{ $student->first_name }} {{ $student->last_name }}
                            </td>

                            {{-- ✅ FIXED CLASS RELATION --}}
                            <td>
                                {{ $student->schoolClass->name ?? 'N/A' }}
                            </td>

                            {{-- ✅ FIXED PAYMENT SUM --}}
                            <td>
                                {{ number_format($student->payments_sum_amount_paid ?? 0) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                No students found matching the selected filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection