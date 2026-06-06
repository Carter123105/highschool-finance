<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Gate;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_blocked',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_blocked' => 'boolean',
    ];

    /*
    |--------------------------------------------
    | ROLE HELPERS (SPATIE ONLY)
    |--------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->hasRole('Admin');
    }

    public function isAccountant(): bool
    {
        return $this->hasRole('Accountant');
    }

    public function isRegistrar(): bool
    {
        return $this->hasRole('Registrar');
    }

    public function isTeacher(): bool
    {
        return $this->hasRole('Teacher');
    }

    public function isStudent(): bool
    {
        return $this->hasRole('Student');
    }

    public function isBlocked(): bool
    {
        return (bool) $this->is_blocked;
    }

    /*
    |--------------------------------------------
    | SUPER ADMIN OVERRIDE (IMPORTANT FIX)
    |--------------------------------------------
    |
    | This ensures Admin ALWAYS has full access
    | regardless of permissions assigned.
    |
    */

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('Admin');
    }

    /*
    |--------------------------------------------
    | GLOBAL ACCESS OVERRIDE HOOK
    |--------------------------------------------
    */

    public static function booted(): void
    {
        Gate::before(function ($user, $ability) {
            if ($user?->hasRole('Admin')) {
                return true;
            }
        });
    }
}