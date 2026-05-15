@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">
            <h4>Edit Section</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('sections.update', $section->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- SECTION NAME --}}
                <div class="mb-3">
                    <label>Section Name</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name', $section->name) }}"
                           class="form-control"
                           required>
                </div>

                {{-- CLASS SELECT --}}
                <div class="mb-3">
                    <label>Class</label>
                    <select name="class_id" class="form-control" required>

                        <option value="">-- Select Class --</option>

                        @foreach($classes as $class)
                            <option value="{{ $class->id }}"
                                {{ old('class_id', $section->class_id) == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- CAPACITY (ADDED FOR CONSISTENCY) --}}
                <div class="mb-3">
                    <label>Capacity</label>
                    <input type="number"
                           name="capacity"
                           value="{{ old('capacity', $section->capacity) }}"
                           class="form-control">
                </div>

                <button class="btn btn-primary">
                    Update
                </button>

            </form>

        </div>

    </div>

</div>

@endsection