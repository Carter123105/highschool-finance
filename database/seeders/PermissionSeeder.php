<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------
        | RESET PERMISSION CACHE
        |--------------------------------------------------
        */
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /*
        |--------------------------------------------------
        | PERMISSIONS LIST
        |--------------------------------------------------
        */
        $permissions = [

            // Dashboard
            'view dashboard',

            // Students
            'view students',
            'create students',
            'edit students',
            'delete students',

            // Teachers
            'view teachers',
            'create teachers',
            'edit teachers',
            'delete teachers',

            // Classes
            'view classes',
            'create classes',
            'edit classes',
            'delete classes',

            // Invoices
            'view invoices',
            'create invoices',
            'edit invoices',
            'delete invoices',

            // Payments
            'view payments',
            'create payments',
            'edit payments',
            'delete payments',

            // Users
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Roles & Permissions
            'manage roles',
            'manage permissions',
        ];

        /*
        |--------------------------------------------------
        | CREATE PERMISSIONS
        |--------------------------------------------------
        */
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        /*
        |--------------------------------------------------
        | CREATE ROLES
        |--------------------------------------------------
        */
        $admin = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $registrar = Role::firstOrCreate([
            'name' => 'Registrar',
            'guard_name' => 'web',
        ]);

        $accountant = Role::firstOrCreate([
            'name' => 'Accountant',
            'guard_name' => 'web',
        ]);

        $teacher = Role::firstOrCreate([
            'name' => 'Teacher',
            'guard_name' => 'web',
        ]);

        /*
        |--------------------------------------------------
        | ASSIGN PERMISSIONS TO ROLES
        |--------------------------------------------------
        */

        // ✅ ADMIN = ALL PERMISSIONS
        $admin->syncPermissions(
            Permission::pluck('name')->toArray()
        );

        // Registrar
        $registrar->syncPermissions([
            'view dashboard',

            'view students',
            'create students',
            'edit students',

            'view classes',

            'view invoices',
        ]);

        // Accountant
        $accountant->syncPermissions([
            'view dashboard',

            'view invoices',
            'create invoices',
            'edit invoices',

            'view payments',
            'create payments',
            'edit payments',
        ]);

        // Teacher
        $teacher->syncPermissions([
            'view dashboard',
            'view students',
            'view classes',
        ]);
    }
}