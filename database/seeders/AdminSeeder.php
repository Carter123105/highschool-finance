<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            [
                'email' => 'admin@gmail.com',
            ],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'role' => 'Admin',
                'is_blocked' => false,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | ASSIGN SPATIE ROLE
        |--------------------------------------------------------------------------
        */
        if (!$admin->hasRole('Admin')) {
            $admin->assignRole('Admin');
        }
    }
}