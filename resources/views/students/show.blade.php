@extends('layouts.app')

@section('content')

<div class="student-profile container-fluid py-4">

    {{-- ================= HEADER ================= --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-person-badge me-2 text-primary"></i>
                Student Profile
            </h3>

            <p class="text-muted mb-0">
                View full student information and financial records
            </p>
        </div>

        <div class="d-flex gap-2 mt-2 mt-md-0">

            {{-- FIXED: Student Payment History Route --}}
            <a href="{{ route('students.payments', $student->id) }}"
               class="btn btn-success btn-sm">

                <i class="bi bi-cash-stack me-1"></i>
                View Payments

            </a>

            <a href="{{ route('students.index') }}"
               class="btn btn-dark btn-sm">

                ← Back

            </a>

        </div>

    </div>

    {{-- ================= PROFILE CARD ================= --}}
    <div class="card profile-card border-0 shadow-sm">

        <div class="card-body">

            <div class="row g-4">

                {{-- PHOTO --}}
                <div class="col-md-3 text-center">

                    <div class="profile-photo">

                        @if($student->photo)

                            <img src="{{ asset('storage/' . $student->photo) }}"
                                 class="img-fluid rounded-circle border shadow-sm"
                                 style="width:160px;height:160px;object-fit:cover;">

                        @else

                            <div class="no-photo">
                                <i class="bi bi-person"></i>
                                <p>No Photo</p>
                            </div>

                        @endif

                    </div>

                </div>

                {{-- INFO --}}
                <div class="col-md-9">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <h4 class="fw-bold mb-0">
                            {{ $student->first_name }} {{ $student->last_name }}
                        </h4>

                        <div class="d-flex gap-2">

                            @if($student->student_type == 'New')
                                <span class="badge bg-success px-3 py-2">New</span>
                            @elseif($student->student_type == 'Old')
                                <span class="badge bg-warning text-dark px-3 py-2">Old</span>
                            @endif

                            <span class="badge bg-primary px-3 py-2">
                                {{ $student->status }}
                            </span>

                        </div>

                    </div>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <div class="info-box">
                                <small>Student ID</small>
                                <h6>{{ $student->student_id }}</h6>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <small>Gender</small>
                                <h6>{{ $student->gender }}</h6>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <small>Student Type</small>
                                <h6>{{ $student->student_type }}</h6>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <small>Class</small>
                                <h6>{{ $student->schoolClass->name ?? 'N/A' }}</h6>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <small>Section</small>
                                <h6>{{ $student->section->name ?? 'N/A' }}</h6>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <small>Academic Year</small>
                                <h6>{{ $student->academicYear->name ?? 'N/A' }}</h6>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <small>Phone</small>
                                <h6>{{ $student->phone ?? 'N/A' }}</h6>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="info-box">
                                <small>Address</small>
                                <h6>{{ $student->address ?? 'N/A' }}</h6>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="info-box">
                                <small>Guardian</small>
                                <h6>
                                    {{ $student->guardian_name ?? 'N/A' }}
                                    @if($student->guardian_phone)
                                        ({{ $student->guardian_phone }})
                                    @endif
                                </h6>
                            </div>
                        </div>

                    </div>

                    {{-- ACTIONS --}}
                    <div class="mt-4 d-flex gap-2">

                        <a href="{{ route('students.edit', $student->id) }}"
                           class="btn btn-warning btn-sm">

                            <i class="bi bi-pencil-square me-1"></i>
                            Edit Student

                        </a>

                        {{-- FIXED: Payment History Route --}}
                        <a href="{{ route('students.payments', $student->id) }}"
                           class="btn btn-success btn-sm">

                            <i class="bi bi-receipt me-1"></i>
                            Payment History

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

{{-- ================= STYLE ================= --}}
<style>

.student-profile{
    background:#f4f7fb;
}

.profile-card{
    border-radius:18px;
}

.profile-photo{
    display:flex;
    justify-content:center;
    align-items:center;
}

.no-photo{
    width:160px;
    height:160px;
    border-radius:50%;
    background:#e2e8f0;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    color:#64748b;
}

.no-photo i{
    font-size:32px;
}

.info-box{
    background:#f8fafc;
    padding:12px 14px;
    border-radius:12px;
    border:1px solid #eef2f7;
}

.info-box small{
    color:#64748b;
    font-size:12px;
}

.info-box h6{
    margin:0;
    font-weight:700;
    color:#0f172a;
}

@media(max-width:768px){
    .no-photo{
        width:120px;
        height:120px;
    }
}

</style>

@endsection