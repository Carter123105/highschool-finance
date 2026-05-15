@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm border-0">

        {{-- HEADER --}}
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Create Fee Category</h4>

            <a href="{{ route('fee-categories.index') }}" class="btn btn-light btn-sm">
                Back
            </a>
        </div>

        {{-- BODY --}}
        <div class="card-body">

            <form action="{{ route('fee-categories.store') }}" method="POST">
                @csrf

                <div class="row">

                    {{-- NAME (FIXED from type → name) --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            Category Name <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="Enter fee category name"
                            value="{{ old('name') }}"
                            required
                        >

                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- MONTHLY CHECKBOX --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold d-block">
                            Billing Type
                        </label>

                        <div class="form-check mt-2">

                            <input
                                type="checkbox"
                                name="is_monthly"
                                value="1"
                                class="form-check-input"
                                id="monthlyCheck"
                                {{ old('is_monthly') ? 'checked' : '' }}
                            >

                            <label class="form-check-label" for="monthlyCheck">
                                This is a monthly fee
                            </label>

                        </div>

                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="col-md-12 mb-4">

                        <label class="form-label fw-bold">
                            Description / Note
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            class="form-control"
                            placeholder="Enter description or note..."
                        >{{ old('description') }}</textarea>

                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                </div>

                {{-- BUTTONS --}}
                <div class="d-flex gap-2">

                    <button type="submit" class="btn btn-success">
                        Save Fee Category
                    </button>

                    <a href="{{ route('fee-categories.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection