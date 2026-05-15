@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm border-0">

        {{-- CARD HEADER --}}
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">
                <i class="bi bi-person-plus-fill"></i>
                Create New User
            </h4>

            <a href="{{ route('admin.users.index') }}"
               class="btn btn-light btn-sm">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>

        </div>

        {{-- CARD BODY --}}
        <div class="card-body">

            {{-- SUCCESS MESSAGE --}}
            @if(session('success'))

                <div class="alert alert-success">
                    {{ session('success') }}
                </div>

            @endif

            {{-- ERROR MESSAGE --}}
            @if ($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            {{-- FORM --}}
            <form action="{{ route('admin.users.store') }}"
                  method="POST">

                @csrf

                {{-- NAME + EMAIL --}}
                <div class="row">

                    {{-- NAME --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Full Name
                        </label>

                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name') }}"
                               placeholder="Enter full name"
                               required>

                    </div>

                    {{-- EMAIL --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Email Address
                        </label>

                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email') }}"
                               placeholder="Enter email address"
                               required>

                    </div>

                </div>

                {{-- PASSWORDS --}}
                <div class="row">

                    {{-- PASSWORD --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Password
                        </label>

                        <input type="password"
                               name="password"
                               class="form-control"
                               placeholder="Enter password"
                               required>

                    </div>

                    {{-- CONFIRM PASSWORD --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Confirm Password
                        </label>

                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               placeholder="Confirm password"
                               required>

                    </div>

                </div>

                {{-- ROLE --}}
                <div class="mb-3">

                    <label class="form-label fw-bold">
                        Role
                    </label>

                    {{-- FIXED --}}
                    <select name="role"
                            class="form-select"
                            required>

                        <option value="">
                            -- Select Role --
                        </option>

                        <option value="Admin"
                            {{ old('role') == 'Admin' ? 'selected' : '' }}>
                            Admin
                        </option>

                        <option value="Accountant"
                            {{ old('role') == 'Accountant' ? 'selected' : '' }}>
                            Accountant
                        </option>

                        <option value="Registrar"
                            {{ old('role') == 'Registrar' ? 'selected' : '' }}>
                            Registrar
                        </option>

                        <option value="Teacher"
                            {{ old('role') == 'Teacher' ? 'selected' : '' }}>
                            Teacher
                        </option>

                        <option value="User"
                            {{ old('role') == 'User' ? 'selected' : '' }}>
                            User
                        </option>

                    </select>

                    @error('role')

                        <div class="text-danger mt-1">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

                {{-- BLOCK USER --}}
                <div class="form-check mb-4">

                    <input type="checkbox"
                           name="is_blocked"
                           value="1"
                           class="form-check-input"
                           id="blocked">

                    <label class="form-check-label" for="blocked">
                        Block this user
                    </label>

                </div>

                {{-- SUBMIT BUTTON --}}
                <div class="d-flex justify-content-end">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-save-fill"></i>
                        Save User

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection