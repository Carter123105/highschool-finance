@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Classes</h3>

    <div class="card">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Class Name</th>
                        <th>Description</th>
                        <th>Total Students</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($classes as $class)
                        <tr>
                            <td>{{ $class->id }}</td>
                            <td>{{ $class->name }}</td>
                            <td>{{ $class->description ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $class->students_count ?? 0 }}</span>
                            </td>
                            <td>{{ $class->created_at?->format('Y-m-d') ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('finance.classes.students', $class->id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> View Students
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                No classes found
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>
</div>
@endsection