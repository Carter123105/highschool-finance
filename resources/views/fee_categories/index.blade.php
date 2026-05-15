@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Fee Categories</h4>

        <a href="{{ route('fee-categories.create') }}" class="btn btn-primary">
            + Create Fee Category
        </a>
    </div>

    {{-- TABLE --}}
    <div class="card shadow-sm">

        <div class="card-header bg-dark text-white d-flex justify-content-between">
            <span>All Fee Categories</span>
            <span>Total: {{ $feeCategories->total() }}</span>
        </div>

        <div class="card-body table-responsive">

            <table class="table table-hover table-striped align-middle">

                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Monthly</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($feeCategories as $key => $category)

                    <tr>
                        <td>
                            {{ $feeCategories->firstItem() + $key }}
                        </td>

                        {{-- NAME (PRIMARY FIELD) --}}
                        <td class="fw-semibold">
                            {{ $category->name }}
                        </td>

                        {{-- TYPE --}}
                        <td>
                            <span class="badge bg-info text-dark">
                                {{ $category->type }}
                            </span>
                        </td>

                        {{-- DESCRIPTION --}}
                        <td>
                            {{ $category->description ?? '-' }}
                        </td>

                        {{-- MONTHLY --}}
                        <td>
                            @if($category->is_monthly)
                                <span class="badge bg-primary">Monthly</span>
                            @else
                                <span class="badge bg-secondary">One-Time</span>
                            @endif
                        </td>

                        {{-- STATUS --}}
                        <td>
                            @if($category->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>

                        {{-- CREATED --}}
                        <td>
                            {{ $category->created_at?->format('d M Y') }}
                            <br>
                            <small class="text-muted">
                                {{ $category->created_at?->diffForHumans() }}
                            </small>
                        </td>

                        {{-- ACTIONS --}}
                        <td class="d-flex gap-1">

                            <a href="{{ route('fee-categories.edit', $category->id) }}"
                               class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <form action="{{ route('fee-categories.destroy', $category->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this category?')">

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
                        <td colspan="8" class="text-center text-muted py-4">
                            No fee categories found
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

            {{-- PAGINATION --}}
            <div class="mt-3">
                {{ $feeCategories->links() }}
            </div>

        </div>
    </div>

</div>

@endsection