@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">Edit Student</h4>
        </div>

        <div class="card-body">

            {{-- ================= ERRORS ================= --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ================= FORM ================= --}}
            <form action="{{ route('students.update', $student->id) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="row">

                    {{-- STUDENT ID (READ ONLY) --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Student ID</label>
                        <input type="text"
                               class="form-control bg-light"
                               value="{{ $student->student_id }}"
                               disabled>
                    </div>

                    {{-- FIRST NAME --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text"
                               name="first_name"
                               value="{{ old('first_name', $student->first_name) }}"
                               class="form-control">
                    </div>

                    {{-- LAST NAME --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text"
                               name="last_name"
                               value="{{ old('last_name', $student->last_name) }}"
                               class="form-control">
                    </div>

                    {{-- CLASS (USING class_id FIXED) --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Class</label>

                        <select name="class_id" class="form-control" required>
                            <option value="">-- Select Class --</option>

                            @foreach($classes as $class)
                                <option value="{{ $class->id }}"
                                    {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- SECTION --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Section</label>

                        <select name="section_id" class="form-control" required>
                            <option value="">-- Select Section --</option>

                            @foreach($sections as $section)
                                <option value="{{ $section->id }}"
                                    {{ old('section_id', $student->section_id) == $section->id ? 'selected' : '' }}>
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- ACADEMIC YEAR --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Academic Year</label>

                        <select name="academic_year_id" class="form-control" required>
                            <option value="">-- Select Year --</option>

                            @foreach($years as $year)
                                <option value="{{ $year->id }}"
                                    {{ old('academic_year_id', $student->academic_year_id) == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- GENDER (FIXED) --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Gender</label>

                        <select name="gender" class="form-control" required>
                            <option value="Male"
                                {{ old('gender', $student->gender) == 'Male' ? 'selected' : '' }}>
                                Male
                            </option>

                            <option value="Female"
                                {{ old('gender', $student->gender) == 'Female' ? 'selected' : '' }}>
                                Female
                            </option>
                        </select>
                    </div>

                    {{-- STUDENT TYPE --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Student Type</label>

                        <select name="student_type" class="form-control" required>
                            <option value="New"
                                {{ old('student_type', $student->student_type) == 'New' ? 'selected' : '' }}>
                                New
                            </option>

                            <option value="Old"
                                {{ old('student_type', $student->student_type) == 'Old' ? 'selected' : '' }}>
                                Old
                            </option>
                        </select>
                    </div>

                    {{-- PHONE --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text"
                               name="phone"
                               value="{{ old('phone', $student->phone) }}"
                               class="form-control">
                    </div>

                    {{-- GUARDIAN NAME --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Guardian Name</label>
                        <input type="text"
                               name="guardian_name"
                               value="{{ old('guardian_name', $student->guardian_name) }}"
                               class="form-control">
                    </div>

                    {{-- GUARDIAN PHONE --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Guardian Phone</label>
                        <input type="text"
                               name="guardian_phone"
                               value="{{ old('guardian_phone', $student->guardian_phone) }}"
                               class="form-control">
                    </div>

                    {{-- PHOTO --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Photo</label>

                        <br>

                        @if($student->photo)
                            <img src="{{ asset('storage/'.$student->photo) }}"
                                 width="80"
                                 class="mb-2 rounded">
                        @endif

                        <input type="file" name="photo" class="form-control">
                    </div>

                    {{-- ADDRESS --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Address</label>
                        <input type="text"
                               name="address"
                               value="{{ old('address', $student->address) }}"
                               class="form-control">
                    </div>

                </div>

                <button type="submit" class="btn btn-primary">
                    Update Student
                </button>

            </form>

        </div>
    </div>

</div>

@endsection