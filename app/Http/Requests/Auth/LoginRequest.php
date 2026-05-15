<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Authenticate user.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        /*
        |--------------------------------------------------------------------------
        | LOGIN ATTEMPT
        |--------------------------------------------------------------------------
        */
        if (! Auth::attempt(
            $this->only('email', 'password'),
            $this->boolean('remember')
        )) {

            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | REGENERATE SESSION
        |--------------------------------------------------------------------------
        */
        $this->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | CLEAR LIMITER
        |--------------------------------------------------------------------------
        */
        RateLimiter::clear($this->throttleKey());

        /*
        |--------------------------------------------------------------------------
        | CHECK IF USER IS BLOCKED
        |--------------------------------------------------------------------------
        */
        $user = Auth::user();

        if ($user && $user->is_blocked) {

            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'Your account has been blocked by the administrator.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CHECK USER ROLE
        |--------------------------------------------------------------------------
        */
        if (!$user->roles || $user->roles->isEmpty()) {

            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'No role assigned to this account.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | VALID ROLES
        |--------------------------------------------------------------------------
        */
        $allowedRoles = [
            'Admin',
            'Accountant',
            'Registrar',
        ];

        $hasValidRole = $user->roles->pluck('name')->intersect($allowedRoles)->isNotEmpty();

        if (! $hasValidRole) {

            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'Unauthorized role access.',
            ]);
        }
    }

    /**
     * Ensure request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Throttle key.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower($this->string('email')) . '|' . $this->ip()
        );
    }
}