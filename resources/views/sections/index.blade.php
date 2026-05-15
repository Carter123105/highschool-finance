@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header d-flex justify-content-between">
            <h4>Sections</h4>

            <a href="{{ route('sections.create') }}" class="btn btn-primary">
                + Add Section
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
                        <th>Section Name</th>
                        <th>Class</th>
                        <th>Capacity</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($sections as $section)

                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $section->name }}</td>

                            {{-- FIXED RELATIONSHIP --}}
                            <td>{{ $section->schoolClass->name ?? 'N/A' }}</td>

                            <td>{{ $section->capacity ?? 'N/A' }}</td>

                            <td class="d-flex gap-2">

                                <a href="{{ route('sections.edit', $section->id) }}"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('sections.destroy', $section->id) }}"
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
                            <td colspan="5" class="text-center">
                                No Sections Found
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection