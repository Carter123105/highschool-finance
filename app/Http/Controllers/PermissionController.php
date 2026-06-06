<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

class PermissionController extends Controller
{
    /*
    |--------------------------------------
    | INDEX PAGE
    |--------------------------------------
    */
    public function index()
    {
        return view('permissions.index', [
            'users' => User::with('roles')->get(),
            'roles' => Role::with('permissions')->get(),
            'permissions' => Permission::all(),
        ]);
    }

    /*
    |--------------------------------------
    | ASSIGN ROLE TO USER ONLY
    |--------------------------------------
    */
    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', 'exists:roles,name'],
        ]);

        DB::transaction(function () use ($request, $user) {

            // ONLY role assignment
            $user->syncRoles([$request->role]);
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return back()->with('success', 'Role assigned successfully.');
    }

    /*
    |--------------------------------------
    | UPDATE ROLE PERMISSIONS (MAIN LOGIC)
    |--------------------------------------
    */
    public function assignPermissions(Request $request, User $user)
    {
        $request->validate([
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        DB::transaction(function () use ($request, $user) {

            $role = $user->roles->first();

            if (!$role) {
                return;
            }

            // IMPORTANT: edit ROLE permissions, not user
            $role->syncPermissions($request->permissions ?? []);
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return back()->with('success', 'Role permissions updated successfully.');
    }
}