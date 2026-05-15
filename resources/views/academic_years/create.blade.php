@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">
            <h4>Create Academic Year</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('academic-years.store') }}" method="POST">
                @csrf

                {{-- ACADEMIC YEAR NAME --}}
                <div class="mb-3">
                    <label>Academic Year</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           placeholder="e.g 2025/2026"
                           required>
                </div>

                {{-- START DATE --}}
                <div class="mb-3">
                    <label>Start Date</label>
                    <input type="date"
                           name="start_date"
                           class="form-control"
                           required>
                </div>

                {{-- END DATE --}}
                <div class="mb-3">
                    <label>End Date</label>
                    <input type="date"
                           name="end_date"
                           class="form-control"
                           required>
                </div>

                {{-- ACTIVE STATUS --}}
                <div class="mb-3 form-check">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           class="form-check-input"
                           id="is_active">

                    <label class="form-check-label" for="is_active">
                        Set as Active
                    </label>
                </div>

                <button class="btn btn-primary">
                    Save Academic Year
                </button>

            </form>

        </div>

    </div>

</div>

@endsection