@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header d-flex justify-content-between">
            <h4>Academic Years</h4>

            <a href="{{ route('academic-years.create') }}" class="btn btn-primary">
                + Add Year
            </a>
        </div>

        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table table-bordered">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Academic Year</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($years as $year)

                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $year->name }}</td>

                            <td>{{ $year->start_date }}</td>

                            <td>{{ $year->end_date }}</td>

                            <td>
                                <span class="badge bg-{{ $year->is_active ? 'success' : 'secondary' }}">
                                    {{ $year->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <td class="d-flex gap-2">

                                <a href="{{ route('academic-years.edit', $year->id) }}"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('academic-years.destroy', $year->id) }}"
                                      method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm">
                                        Delete
                                    </button>

                                </form>

                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center">
                                No Academic Years Found
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection