<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * Spatie permission guard
     */
    protected string $guard_name = 'web';

    /**
     * Mass assignable attributes
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_blocked',
    ];

    /**
     * Hidden attributes
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_blocked'        => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE HELPERS
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return strtolower((string) $this->role) === 'admin'
            || $this->hasRole('Admin');
    }

    public function isAccountant(): bool
    {
        return strtolower((string) $this->role) === 'accountant'
            || $this->hasRole('Accountant');
    }

    public function isRegistrar(): bool
    {
        return strtolower((string) $this->role) === 'registrar'
            || $this->hasRole('Registrar');
    }

    public function isTeacher(): bool
    {
        return strtolower((string) $this->role) === 'teacher'
            || $this->hasRole('Teacher');
    }

    public function isStudent(): bool
    {
        return strtolower((string) $this->role) === 'student'
            || $this->hasRole('Student');
    }

    /*
    |--------------------------------------------------------------------------
    | BLOCK STATUS
    |--------------------------------------------------------------------------
    */

    public function isBlocked(): bool
    {
        return (bool) $this->is_blocked;
    }

    /*
    |--------------------------------------------------------------------------
    | AUTO ASSIGN SPATIE ROLE
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::created(function ($user) {

            /*
            |--------------------------------------------------------------------------
            | Assign Role Automatically
            |--------------------------------------------------------------------------
            */
            if (!empty($user->role)) {

                /*
                |--------------------------------------------------------------------------
                | Prevent Duplicate Role Assignment
                |--------------------------------------------------------------------------
                */
                if (!$user->hasRole($user->role)) {

                    $user->assignRole($user->role);
                }
            }
        });

        static::updated(function ($user) {

            /*
            |--------------------------------------------------------------------------
            | Sync Updated Role
            |--------------------------------------------------------------------------
            */
            if (!empty($user->role)) {

                $user->syncRoles([$user->role]);
            }
        });
    }
}