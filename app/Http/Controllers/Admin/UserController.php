<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display all users
     */
    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('Admin.users.index', compact('users'));
    }

    /**
     * Show create user form
     */
    public function create()
    {
        return view('Admin.users.create');
    }

    /**
     * Store new user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'              => ['required', 'string', 'min:6', 'confirmed'],
            'role'                  => ['required', 'string'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create User
        |--------------------------------------------------------------------------
        */
        $user = User::create([
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'password'    => Hash::make($validated['password']),
            'role'        => $validated['role'],
            'is_blocked'  => $request->boolean('is_blocked'),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Show single user
     */
    public function show(User $user)
    {
        return view('Admin.users.show', compact('user'));
    }

    /**
     * Show edit form
     */
    public function edit(User $user)
    {
        return view('Admin.users.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password'              => ['nullable', 'string', 'min:6', 'confirmed'],
            'role'                  => ['required', 'string'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Update User Data
        |--------------------------------------------------------------------------
        */
        $data = [
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'role'        => $validated['role'],
            'is_blocked'  => $request->boolean('is_blocked'),
        ];

        /*
        |--------------------------------------------------------------------------
        | Update Password Only If Entered
        |--------------------------------------------------------------------------
        */
        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        /*
        |--------------------------------------------------------------------------
        | Prevent Self Delete
        |--------------------------------------------------------------------------
        */
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Block user
     */
    public function block(User $user)
    {
        /*
        |--------------------------------------------------------------------------
        | Prevent Self Block
        |--------------------------------------------------------------------------
        */
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot block yourself.');
        }

        $user->update([
            'is_blocked' => true,
        ]);

        return back()->with('success', 'User blocked successfully.');
    }

    /**
     * Unblock user
     */
    public function unblock(User $user)
    {
        $user->update([
            'is_blocked' => false,
        ]);

        return back()->with('success', 'User unblocked successfully.');
    }
}