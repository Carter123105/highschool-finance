@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Profile Settings
            </h3>

            <p class="text-muted mb-0">
                Manage your account information and security
            </p>
        </div>

    </div>

    {{-- SUCCESS MESSAGE --}}
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('status') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    {{-- PROFILE INFORMATION --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0 fw-bold">
                Profile Information
            </h5>

        </div>

        <div class="card-body">

            @include('profile.partials.update-profile-information-form')

        </div>

    </div>

    {{-- UPDATE PASSWORD --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-warning text-dark">

            <h5 class="mb-0 fw-bold">
                Update Password
            </h5>

        </div>

        <div class="card-body">

            @include('profile.partials.update-password-form')

        </div>

    </div>

    {{-- DELETE ACCOUNT --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-danger text-white">

            <h5 class="mb-0 fw-bold">
                Delete Account
            </h5>

        </div>

        <div class="card-body">

            @include('profile.partials.delete-user-form')

        </div>

    </div>

</div>

@endsection