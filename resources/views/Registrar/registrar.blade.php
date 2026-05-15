@extends('layouts.app')

@section('content')

@php
    use App\Models\Student;

    // ================= AUTO CALCULATED SAFE VALUES =================
    $totalStudents = Student::count();

    $newStudents = Student::where('student_type', 'New')->count();

    $oldStudents = Student::where('student_type', 'Old')->count();

    $graduatedStudents = Student::where('status', 'Graduated')->count();

    $recentStudents = Student::latest()->take(10)->get();
@endphp

<div class="container-fluid">

    {{-- ================= HEADER ================= --}}
    <div class="mb-4">
        <h2 class="fw-bold">
            Registrar Dashboard
        </h2>
    </div>

    {{-- ================= STATS ================= --}}
    <div class="row g-4">

        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h6>Total Students</h6>
                    <h2>{{ number_format($totalStudents) }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h6>New Students</h6>
                    <h2>{{ number_format($newStudents) }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h6>Old Students</h6>
                    <h2>{{ number_format($oldStudents) }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h6>Graduated</h6>
                    <h2>{{ number_format($graduatedStudents) }}</h2>
                </div>
            </div>
        </div>

    </div>

    {{-- ================= TABLE ================= --}}
    <div class="card mt-4">

        <div class="card-header">
            Recently Registered Students
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($recentStudents as $student)

                            <tr>
                                <td>{{ $student->student_id }}</td>

                                <td>
                                    {{ $student->first_name }} {{ $student->last_name }}
                                </td>

                                <td>
                                    {{ $student->student_type }}
                                </td>

                                <td>
                                    @if($student->status === 'Active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($student->status === 'Graduated')
                                        <span class="badge bg-primary">Graduated</span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ $student->status }}
                                        </span>
                                    @endif
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    No recent students found
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

{{-- ================= SIMPLE STYLES ================= --}}
<style>

.stat-card{
    border-radius:12px;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
    transition:0.2s ease;
}

.stat-card:hover{
    transform:translateY(-3px);
}

.stat-card h2{
    font-weight:800;
    margin:0;
    color:#0f172a;
}

.stat-card h6{
    color:#64748b;
    font-size:13px;
}

</style>

@endsection