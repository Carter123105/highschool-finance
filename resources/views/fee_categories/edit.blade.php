@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Fee Category</h4>
                </div>

                <div class="card-body">

                    {{-- 
                        DEBUG: Uncomment the line below to see what variables are available
                        {{ dd(get_defined_vars()['__data'] ?? []) }}
                    --}}

                    {{-- Handle all possible variable names Laravel might pass --}}
                    @php
                        $category = null;
                        
                        // Check all possible variable names
                        if (isset($fee_category) && is_object($fee_category)) {
                            $category = $fee_category;
                        } elseif (isset($feecategory) && is_object($feecategory)) {
                            $category = $feecategory;
                        } elseif (isset($feeCategory) && is_object($feeCategory)) {
                            $category = $feeCategory;
                        } elseif (isset($category) && is_object($category)) {
                            $category = $category;
                        }
                    @endphp

                    @if(!$category || !is_object($category))
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Error:</strong> Fee category not loaded. 
                            Variable received: {{ isset($fee_category) ? gettype($fee_category) : 'none' }}
                            <br>
                            <a href="{{ route('fee-categories.index') }}" class="btn btn-sm btn-outline-danger mt-2">
                                <i class="bi bi-arrow-left"></i> Go Back
                            </a>
                        </div>
                    @else

                    <form action="{{ route('fee-categories.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Name --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $category->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" 
                                      class="form-control @error('description') is-invalid @enderror"
                                      rows="3">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Type --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Type <span class="text-danger">*</span></label>
                            <select name="type" 
                                    class="form-control @error('type') is-invalid @enderror"
                                    required>
                                <option value="Mandatory" {{ (old('type', $category->type) == 'Mandatory') ? 'selected' : '' }}>Mandatory</option>
                                <option value="Optional" {{ (old('type', $category->type) == 'Optional') ? 'selected' : '' }}>Optional</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Monthly --}}
                        <div class="form-check mb-3">
                            <input type="hidden" name="is_monthly" value="0">
                            <input type="checkbox" 
                                   name="is_monthly"
                                   class="form-check-input"
                                   value="1"
                                   {{ old('is_monthly', $category->is_monthly) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold">Monthly Fee</label>
                        </div>

                        {{-- Active --}}
                        <div class="form-check mb-3">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" 
                                   name="is_active"
                                   class="form-check-input"
                                   value="1"
                                   {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold">Active</label>
                        </div>

                        {{-- Submit --}}
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update
                            </button>

                            <a href="{{ route('fee-categories.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>

                    </form>

                    @endif

                </div>
            </div>

        </div>
    </div>

</div>
@endsection