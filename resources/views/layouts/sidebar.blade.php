@php
    $user = auth()->user();
@endphp

<div class="sidebar">

    <div class="brand mb-4">
        🎓 High School Finance
    </div>

    <ul class="nav flex-column">

        {{-- ================= DASHBOARD ================= --}}
        @if($user && $user->can('view dashboard'))
            <li class="nav-item">
                <a href="{{ route('dashboard') }}"
                   class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </a>
            </li>
        @endif


        {{-- ================= USER MANAGEMENT ================= --}}
        @if($user && ($user->can('view users') || $user->can('manage permissions')))

            <li class="nav-title mt-3 px-3 text-uppercase text-muted small">
                User Management
            </li>

            @if($user->can('view users'))
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}"
                       class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="bi bi-people me-2"></i>
                        Users
                    </a>
                </li>
            @endif

            @if($user->can('manage permissions'))
                <li class="nav-item">
                    <a href="{{ route('permissions.index') }}"
                       class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                        <i class="bi bi-shield-lock me-2"></i>
                        Roles & Permissions
                    </a>
                </li>
            @endif

        @endif


        {{-- ================= ACADEMIC ================= --}}
        @if($user && ($user->can('view students') || $user->can('view classes')))

            <li class="nav-title mt-3 px-3 text-uppercase text-muted small">
                Academic
            </li>

            @if($user->can('view students'))
                <li class="nav-item">
                    <a href="{{ route('students.index') }}"
                       class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                        <i class="bi bi-mortarboard me-2"></i>
                        Students
                    </a>
                </li>
            @endif

            @if($user->can('view classes'))
                <li class="nav-item">
                    <a href="{{ route('classes.index') }}"
                       class="nav-link {{ request()->routeIs('classes.*') ? 'active' : '' }}">
                        <i class="bi bi-building me-2"></i>
                        Classes
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('sections.index') }}"
                       class="nav-link {{ request()->routeIs('sections.*') ? 'active' : '' }}">
                        <i class="bi bi-diagram-3 me-2"></i>
                        Sections
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('academic-years.index') }}"
                       class="nav-link {{ request()->routeIs('academic-years.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-event me-2"></i>
                        Academic Years
                    </a>
                </li>
            @endif

        @endif


        {{-- ================= FINANCE ================= --}}
        @if($user && ($user->can('view invoices') || $user->can('view payments')))

            <li class="nav-title mt-3 px-3 text-uppercase text-muted small">
                Finance
            </li>

            @if($user->can('view invoices'))
                <li class="nav-item">
                    <a href="{{ route('fee-categories.index') }}"
                       class="nav-link {{ request()->routeIs('fee-categories.*') ? 'active' : '' }}">
                        <i class="bi bi-tags me-2"></i>
                        Fee Categories
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('invoices.index') }}"
                       class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                        <i class="bi bi-receipt me-2"></i>
                        Invoices
                    </a>
                </li>
            @endif

            @if($user->can('view payments'))
                <li class="nav-item">
                    <a href="{{ route('payments.index') }}"
                       class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                        <i class="bi bi-cash-stack me-2"></i>
                        Payments
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('finance.summary') }}"
                       class="nav-link {{ request()->routeIs('finance.*') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart me-2"></i>
                        Reports
                    </a>
                </li>
            @endif

        @endif


        {{-- ================= SYSTEM ================= --}}
        @if($user && $user->can('manage permissions'))

            <li class="nav-title mt-3 px-3 text-uppercase text-muted small">
                System
            </li>

            <li class="nav-item">
                <a href="{{ route('settings.index') }}"
                   class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="bi bi-gear me-2"></i>
                    Settings
                </a>
            </li>

            {{-- ✅ FIXED BACKUP (THIS IS YOUR MAIN FIX) --}}
            <li class="nav-item">
                <form method="POST"
                      action="{{ route('system.backup.run') }}"
                      onsubmit="return confirm('Run system backup now?')">
                    @csrf

                    <button type="submit"
                            class="nav-link border-0 bg-transparent w-100 text-start text-warning">
                        <i class="bi bi-cloud-arrow-down me-2"></i>
                        Run Backup
                    </button>
                </form>
            </li>

            <li class="nav-item">
                <a href="{{ route('system.backup.download') }}"
                   class="nav-link text-warning">
                    <i class="bi bi-download me-2"></i>
                    Download Backup
                </a>
            </li>

        @endif


        {{-- ================= ACCOUNT ================= --}}
        <li class="nav-title mt-3 px-3 text-uppercase text-muted small">
            Account
        </li>

        <li class="nav-item">
            <a href="{{ route('profile.edit') }}" class="nav-link">
                <i class="bi bi-person-circle me-2"></i>
                Profile
            </a>
        </li>

        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="nav-link border-0 bg-transparent w-100 text-start">
                    <i class="bi bi-box-arrow-right me-2"></i>
                    Logout
                </button>
            </form>
        </li>

    </ul>
</div>