@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm border-0">

        {{-- HEADER --}}
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                Register New Student
            </h4>

            <a href="{{ route('students.index') }}"
               class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
        </div>

        <div class="card-body">

            {{-- SUCCESS MESSAGE --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- ERROR MESSAGE --}}
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">

                    <strong>
                        Please fix the following errors:
                    </strong>

                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('students.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="row">

                    {{-- CLASS --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            Class
                            <span class="text-danger">*</span>
                        </label>

                        <select name="class_id"
                                id="class_id"
                                class="form-control @error('class_id') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Class
                            </option>

                            @foreach($classes ?? [] as $class)

                                <option value="{{ $class->id }}"
                                    {{ old('class_id') == $class->id ? 'selected' : '' }}>

                                    {{ $class->name }}

                                </option>

                            @endforeach

                        </select>

                        @error('class_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- SECTION --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            Section
                            <span class="text-danger">*</span>
                        </label>

                        <select name="section_id"
                                id="section_id"
                                class="form-control @error('section_id') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Section
                            </option>

                            @foreach($sections ?? [] as $section)

                                <option value="{{ $section->id }}"
                                    data-class-id="{{ $section->class_id }}"
                                    {{ old('section_id') == $section->id ? 'selected' : '' }}>

                                    {{ $section->name }}

                                </option>

                            @endforeach

                        </select>

                        @error('section_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- ACADEMIC YEAR --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            Academic Year
                            <span class="text-danger">*</span>
                        </label>

                        <select name="academic_year_id"
                                class="form-control @error('academic_year_id') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Academic Year
                            </option>

                            @foreach($years ?? [] as $year)

                                <option value="{{ $year->id }}"
                                    {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>

                                    {{ $year->name }}

                                </option>

                            @endforeach

                        </select>

                        @error('academic_year_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- STUDENT ID --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            Student ID
                        </label>

                        <input type="text"
                               name="student_id"
                               id="student_id"
                               value="{{ old('student_id') }}"
                               class="form-control bg-light"
                               placeholder="Auto Generated"
                               readonly>

                    </div>

                    {{-- FIRST NAME --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            First Name
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="first_name"
                               value="{{ old('first_name') }}"
                               class="form-control @error('first_name') is-invalid @enderror"
                               required>

                        @error('first_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- LAST NAME --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            Last Name
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="last_name"
                               value="{{ old('last_name') }}"
                               class="form-control @error('last_name') is-invalid @enderror"
                               required>

                        @error('last_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- GENDER --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            Gender
                            <span class="text-danger">*</span>
                        </label>

                        <select name="gender"
                                class="form-control @error('gender') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Gender
                            </option>

                            <option value="Male"
                                {{ old('gender') == 'Male' ? 'selected' : '' }}>
                                Male
                            </option>

                            <option value="Female"
                                {{ old('gender') == 'Female' ? 'selected' : '' }}>
                                Female
                            </option>

                        </select>

                        @error('gender')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- STUDENT TYPE --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            Student Type
                            <span class="text-danger">*</span>
                        </label>

                        <select name="student_type"
                                class="form-control @error('student_type') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Type
                            </option>

                            <option value="New"
                                {{ old('student_type') == 'New' ? 'selected' : '' }}>
                                New
                            </option>

                            <option value="Old"
                                {{ old('student_type') == 'Old' ? 'selected' : '' }}>
                                Old
                            </option>

                        </select>

                        @error('student_type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- PHONE --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            Phone
                        </label>

                        <input type="text"
                               name="phone"
                               value="{{ old('phone') }}"
                               class="form-control">

                    </div>

                    {{-- GUARDIAN NAME --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Guardian Name
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="guardian_name"
                               value="{{ old('guardian_name') }}"
                               class="form-control @error('guardian_name') is-invalid @enderror"
                               required>

                        @error('guardian_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- GUARDIAN PHONE --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Guardian Phone
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="guardian_phone"
                               value="{{ old('guardian_phone') }}"
                               class="form-control @error('guardian_phone') is-invalid @enderror"
                               required>

                        @error('guardian_phone')
                            <div="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- PHOTO --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Photo
                        </label>

                        <input type="file"
                               name="photo"
                               class="form-control @error('photo') is-invalid @enderror">

                        @error('photo')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- ADDRESS --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Address
                        </label>

                        <input type="text"
                               name="address"
                               value="{{ old('address') }}"
                               class="form-control">

                    </div>

                </div>

                {{-- SUBMIT --}}
                <div class="mt-3">

                    <button type="submit"
                            class="btn btn-success">

                        <i class="bi bi-check-circle"></i>
                        Save Student

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- AUTO GENERATE STUDENT ID + FILTER SECTIONS BY CLASS --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    const classSelect = document.getElementById('class_id');
    const studentIdInput = document.getElementById('student_id');
    const sectionSelect = document.getElementById('section_id');

    // ========== STUDENT ID GENERATION ==========
    if (classSelect && studentIdInput) {

        function generateStudentId(classId)
        {
            if (!classId) {
                studentIdInput.value = '';
                return;
            }

            fetch(`/students/generate-id/${classId}`)
                .then(response => response.json())
                .then(data => {

                    if (data.student_id) {
                        studentIdInput.value = data.student_id;
                    } else {
                        studentIdInput.value = '';
                    }

                })
                .catch(error => {

                    console.error('Student ID Error:', error);

                    studentIdInput.value = '';
                });
        }

        classSelect.addEventListener('change', function () {

            generateStudentId(this.value);

        });

        // AUTO LOAD OLD VALUE
        if (classSelect.value && !studentIdInput.value) {

            generateStudentId(classSelect.value);

        }
    }

    // ========== FILTER SECTIONS BY CLASS ==========
    if (classSelect && sectionSelect) {

        // Store all sections for filtering
        const allSections = Array.from(sectionSelect.options).slice(1); // Skip "Select Section"

        function filterSections(classId)
        {
            // Clear current options except placeholder
            sectionSelect.innerHTML = '<option value="">Select Section</option>';

            if (!classId) {
                return;
            }

            // Filter sections by class_id
            const filteredSections = allSections.filter(option => 
                option.getAttribute('data-class-id') == classId
            );

            // Add filtered sections back
            filteredSections.forEach(option => sectionSelect.appendChild(option));

            // Restore old selection if valid
            const oldSectionId = "{{ old('section_id') }}";
            if (oldSectionId) {
                const oldOption = sectionSelect.querySelector(`option[value="${oldSectionId}"]`);
                if (oldOption) {
                    oldOption.selected = true;
                }
            }
        }

        classSelect.addEventListener('change', function () {

            filterSections(this.value);

        });

        // AUTO FILTER ON PAGE LOAD IF CLASS IS PRE-SELECTED
        if (classSelect.value) {
            filterSections(classSelect.value);
        }
    }

});
</script>

@endsection