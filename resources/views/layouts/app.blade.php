<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'High School Finance System') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body { margin:0; font-family:'Inter', sans-serif; background:#f1f5f9; }
        .sidebar { width:270px; height:100vh; position:fixed; background:#0f172a; overflow-y:auto; }
        .sidebar-brand { padding:20px; text-align:center; color:#fff; font-weight:800; border-bottom:1px solid rgba(255,255,255,.1); }
        .sidebar-section { padding:12px 18px 4px; color:#94a3b8; font-size:11px; text-transform:uppercase; }

        .sidebar a,
        .sidebar form button {
            display:flex; align-items:center; gap:10px;
            width:100%; padding:12px 18px;
            color:#cbd5e1; text-decoration:none;
            border:none; background:none; text-align:left;
        }

        .sidebar a:hover { background:#1e293b; color:#fff; }
        .sidebar a.active { background:#2563eb; color:#fff; }

        .main { margin-left:270px; }

        .topbar {
            background:#fff; padding:15px 25px;
            display:flex; justify-content:space-between;
            border-bottom:1px solid #e5e7eb;
        }

        .content { padding:25px; }
    </style>
</head>

<body>

{{-- SIDEBAR --}}
<div class="sidebar">

    <div class="sidebar-brand">
        🎓 School Finance
    </div>

    {{-- DASHBOARD --}}
    <div class="sidebar-section">Main</div>

    <a href="{{ route('dashboard') }}"
       class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    {{-- ================= ADMIN ONLY ================= --}}
    @role('Admin')

    <div class="sidebar-section">Admin</div>

    <a href="{{ route('admin.users.index') }}"
       class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i> Users
    </a>

    <a href="{{ route('admin.users.create') }}"
       class="{{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
        <i class="bi bi-person-plus-fill"></i> Create User
    </a>

    @endrole


    {{-- ================= STUDENTS (ADMIN + REGISTRAR) ================= --}}
    @hasanyrole('Admin|Registrar')

    <div class="sidebar-section">Students</div>

    <a href="{{ route('students.index') }}"
       class="{{ request()->routeIs('students.*') ? 'active' : '' }}">
        Students
    </a>

    <a href="{{ route('classes.index') }}"
       class="{{ request()->routeIs('classes.*') ? 'active' : '' }}">
        Classes
    </a>

    <a href="{{ route('sections.index') }}"
       class="{{ request()->routeIs('sections.*') ? 'active' : '' }}">
        Sections
    </a>

    <a href="{{ route('academic-years.index') }}"
       class="{{ request()->routeIs('academic-years.*') ? 'active' : '' }}">
        Academic Years
    </a>

    @endhasanyrole


    {{-- ================= FINANCE (ADMIN + ACCOUNTANT) ================= --}}
    @hasanyrole('Admin|Accountant')

    <div class="sidebar-section">Finance</div>

    <a href="{{ route('fee-categories.index') }}"
       class="{{ request()->routeIs('fee-categories.*') ? 'active' : '' }}">
        Fee Categories
    </a>

    <a href="{{ route('invoices.index') }}"
       class="{{ request()->routeIs('invoices.*') ? 'active' : '' }}">
        Invoices
    </a>

    <a href="{{ route('payments.index') }}"
       class="{{ request()->routeIs('payments.*') ? 'active' : '' }}">
        Payments
    </a>

    <a href="{{ route('finance.summary') }}"
       class="{{ request()->routeIs('finance.*') ? 'active' : '' }}">
        Finance Reports
    </a>

    @endhasanyrole


    {{-- ================= SETTINGS (ADMIN ONLY) ================= --}}
    @role('Admin')

    <div class="sidebar-section">System</div>

    <a href="{{ route('settings.index') }}"
       class="{{ request()->routeIs('settings.*') ? 'active' : '' }}">
        Settings
    </a>

    @endrole


    {{-- ================= ACCOUNT ================= --}}
    <div class="sidebar-section">Account</div>

    <a href="{{ route('profile.edit') }}"
       class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
        Profile
    </a>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">
            <i class="bi bi-box-arrow-right"></i> Logout
        </button>
    </form>

</div>

{{-- MAIN CONTENT --}}
<div class="main">

    <div class="topbar">
        <h5 class="mb-0">
            Welcome, {{ auth()->user()->name }}
        </h5>

        <span>
            {{ auth()->user()->roles->first()->name ?? 'User' }}
        </span>
    </div>

    <div class="content">
        @yield('content')
    </div>

</div>

</body>
</html>