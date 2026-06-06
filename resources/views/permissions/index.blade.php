@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold">Roles & Permissions</h3>
            <p class="text-muted mb-0">Manage user access via roles only</p>
        </div>

    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- ERROR --}}
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Permissions (Role Based)</th>
                            <th style="width:320px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($users as $u)

                            @php
                                $role = $u->roles->first();

                                // 🔥 STRICT ROLE PERMISSIONS ONLY (no user-level mixing)
                                $rolePermissions = $role
                                    ? $role->permissions->pluck('name')->toArray()
                                    : [];
                            @endphp

                            <tr>

                                {{-- USER --}}
                                <td>{{ $u->name }}</td>

                                {{-- EMAIL --}}
                                <td>{{ $u->email }}</td>

                                {{-- ROLE --}}
                                <td>
                                    @forelse($u->roles as $r)
                                        <span class="badge bg-primary">
                                            {{ $r->name }}
                                        </span>
                                    @empty
                                        <span class="text-muted">No Role</span>
                                    @endforelse
                                </td>

                                {{-- PERMISSIONS --}}
                                <td>

                                    @if($role && !empty($rolePermissions))

                                        @foreach($rolePermissions as $perm)
                                            <span class="badge bg-success mb-1">
                                                {{ $perm }}
                                            </span>
                                        @endforeach

                                    @else
                                        <span class="text-muted">No Permissions</span>
                                    @endif

                                </td>

                                {{-- ACTIONS --}}
                                <td>

                                    {{-- ASSIGN ROLE --}}
                                    <form action="{{ route('permissions.assignRole', $u->id) }}"
                                          method="POST"
                                          class="mb-2">

                                        @csrf

                                        <div class="input-group">

                                            <select name="role" class="form-select">

                                                @foreach($roles as $r)

                                                    <option value="{{ $r->name }}"
                                                        @selected($u->roles->first()?->name === $r->name)>
                                                        {{ $r->name }}
                                                    </option>

                                                @endforeach

                                            </select>

                                            <button class="btn btn-dark">
                                                Assign Role
                                            </button>

                                        </div>

                                    </form>

                                    {{-- ASSIGN ROLE PERMISSIONS --}}
                                    <form action="{{ route('permissions.assignPermissions', $u->id) }}"
                                          method="POST">

                                        @csrf

                                        <div class="row">

                                            @foreach($permissions as $permission)

                                                <div class="col-6">

                                                    <div class="form-check">

                                                        <input type="checkbox"
                                                               name="permissions[]"
                                                               value="{{ $permission->name }}"
                                                               class="form-check-input"

                                                               {{-- 🔥 FIX: check ONLY role permissions --}}
                                                               @checked(in_array($permission->name, $rolePermissions))>

                                                        <label class="form-check-label">
                                                            {{ $permission->name }}
                                                        </label>

                                                    </div>

                                                </div>

                                            @endforeach

                                        </div>

                                        <button class="btn btn-success btn-sm mt-3 w-100">
                                            Update Role Permissions
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection