@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm border-0">

        {{-- HEADER --}}
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Create Section</h4>
        </div>

        {{-- BODY --}}
        <div class="card-body">

            <form action="{{ route('sections.store') }}" method="POST">
                @csrf

                <div class="row">

                    {{-- SECTION NAME --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Section Name</label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="e.g. A, B, Science"
                            required
                        >
                    </div>

                    {{-- CLASS --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Class</label>

                        <select name="class_id" class="form-control" required>
                            <option value="">-- Select Class --</option>

                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">
                                    {{ $class->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- CAPACITY (FIX FOR YOUR ERROR) --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            Capacity
                        </label>

                        <input
                            type="number"
                            name="capacity"
                            class="form-control"
                            placeholder="e.g. 40"
                            value="0"
                        >
                    </div>

                </div>

                {{-- BUTTON --}}
                <div class="mt-3">
                    <button class="btn btn-success">
                        Save Section
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>

@endsection