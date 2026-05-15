<div class="sidebar">

    <div class="brand mb-4">
        High School Finance
    </div>

    <ul class="nav flex-column">

        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link">
                Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('students.index') }}" class="nav-link">
                Students
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('classes.index') }}" class="nav-link">
                Classes
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('sections.index') }}" class="nav-link">
                Sections
            </a>
        </li>

        <!-- ✅ ADDED: Academic Years -->
        <li class="nav-item">
            <a href="{{ route('academic-years.index') }}" class="nav-link">
                Academic Years
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('fee-categories.index') }}" class="nav-link">
                Fee Categories
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('invoices.index') }}" class="nav-link">
                Invoices
            </a>
        </li>

    </ul>

</div>