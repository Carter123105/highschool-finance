@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">
            <h4>Edit Academic Year</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('academic-years.update', $academicYear->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Academic Year Name</label>
                    <input type="text"
                           name="name"
                           value="{{ $academicYear->name }}"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label>Start Date</label>
                    <input type="date"
                           name="start_date"
                           value="{{ $academicYear->start_date }}"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label>End Date</label>
                    <input type="date"
                           name="end_date"
                           value="{{ $academicYear->end_date }}"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label>Status</label>
                    <select name="is_active" class="form-control">

                        <option value="1" {{ $academicYear->is_active ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0" {{ !$academicYear->is_active ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>
                </div>

                <button class="btn btn-primary">
                    Update
                </button>

            </form>

        </div>

    </div>

</div>

@endsection