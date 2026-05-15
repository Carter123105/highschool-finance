@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Classes</h4>

        <a href="{{ route('classes.create') }}" class="btn btn-primary">
            + Add Class
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover">

                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Class Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($classes as $class)

                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            {{-- FIXED HERE --}}
                            <td>{{ $class->name }}</td>

                            <td class="d-flex gap-2">

                                <a href="{{ route('classes.edit', $class->id) }}"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <form action="{{ route('classes.destroy', $class->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this class?')">

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
                            <td colspan="3" class="text-center text-muted">
                                No Classes Found
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection