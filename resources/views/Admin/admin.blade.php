@extends('layouts.app')

@section('content')

@php
    use App\Models\AcademicYear;

    $selectedYearId = session('academic_year_id');

    $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();

    // Default fallback selection
    if (!$selectedYearId && $academicYears->isNotEmpty()) {

        $selectedYear = $academicYears->firstWhere('is_active', 1)
            ?? $academicYears->first();

        $selectedYearId = $selectedYear?->id;

        session(['academic_year_id' => $selectedYearId]);

    } else {

        $selectedYear = $academicYears->firstWhere('id', $selectedYearId);
    }

    // FIXED: correct DB columns (start_date + end_date)
    $selectedYearName = $selectedYear
        ? $selectedYear->name . ' (' . $selectedYear->start_date . ' - ' . $selectedYear->end_date . ')'
        : 'All Years';

    // Safe defaults
    $totalStudents = $totalStudents ?? 0;
    $totalRevenue = $totalRevenue ?? 0;
    $outstandingBalance = $outstandingBalance ?? 0;
    $totalUsers = $totalUsers ?? 0;
    $recentInvoices = $recentInvoices ?? collect();
    $recentPayments = $recentPayments ?? collect();
@endphp

<style>
    :root {
        --primary: #6366f1;
        --primary-dark: #4f46e5;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #3b82f6;
        --dark: #1e293b;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-300: #cbd5e1;
        --gray-400: #94a3b8;
        --gray-500: #64748b;
        --gray-600: #475569;
        --gray-700: #334155;
        --gray-800: #1e293b;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        --radius-sm: 6px;
        --radius: 10px;
        --radius-lg: 16px;
        --radius-xl: 24px;
    }

    body {
        background: var(--gray-50);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .dashboard-wrapper {
        min-height: 100vh;
        padding-bottom: 3rem;
    }

    /* Header Section - Enhanced Mobile Presentation */
    .page-header {
        background: linear-gradient(135deg, var(--dark) 0%, #0f172a 100%);
        border-radius: var(--radius-lg);
        padding: 1.25rem 1.25rem;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    @media (min-width: 768px) {
        .page-header {
            border-radius: var(--radius-xl);
            padding: 2rem 2.5rem;
            margin-bottom: 2rem;
        }
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
        border-radius: 50%;
    }

    .page-header h2 {
        color: #fff;
        font-weight: 700;
        font-size: 1.4rem;
        margin-bottom: 0.25rem;
        position: relative;
        z-index: 1;
    }

    @media (min-width: 768px) {
        .page-header h2 { font-size: 1.75rem; }
    }

    .page-header p {
        color: var(--gray-400);
        margin: 0;
        position: relative;
        z-index: 1;
        font-size: 0.85rem;
    }
    
    @media (min-width: 768px) {
        .page-header p { font-size: 1rem; }
    }

    .header-actions {
        position: relative;
        z-index: 1;
    }

    .btn-primary-modern {
        background: var(--primary);
        border: none;
        padding: 0.625rem 1.25rem;
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.4);
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary-modern:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.5);
    }

    /* Academic Year Switcher Dropdown styling */
    .year-switcher {
        position: relative;
        z-index: 1;
    }

    .year-switcher-btn {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .year-switcher-btn:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .year-switcher-btn i {
        font-size: 0.75rem;
    }

    .year-switcher-dropdown {
        position: absolute;
        top: calc(100% + 0.5rem);
        right: 0;
        background: #fff;
        border-radius: var(--radius);
        box-shadow: var(--shadow-xl);
        min-width: 220px;
        overflow: hidden;
        z-index: 1000;
        display: none;
    }

    .year-switcher-dropdown.show {
        display: block;
    }

    .year-switcher-header {
        padding: 0.75rem 1rem;
        background: var(--gray-50);
        border-bottom: 1px solid var(--gray-100);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--gray-500);
        letter-spacing: 0.05em;
    }

    .year-switcher-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 1rem;
        color: var(--gray-700);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: background 0.15s ease;
        border-bottom: 1px solid var(--gray-50);
    }

    .year-switcher-item:last-child {
        border-bottom: none;
    }

    .year-switcher-item:hover {
        background: var(--gray-50);
    }

    .year-switcher-item.active {
        background: rgba(99, 102, 241, 0.05);
        color: var(--primary);
        font-weight: 700;
    }

    .year-switcher-item .year-status {
        font-size: 0.625rem;
        font-weight: 700;
        padding: 0.125rem 0.5rem;
        border-radius: 9999px;
        text-transform: uppercase;
    }

    .year-status.active {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .year-status.past {
        background: var(--gray-100);
        color: var(--gray-500);
    }

    .year-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        background: rgba(16, 185, 129, 0.15);
        color: #047857;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        margin-left: 0.5rem;
    }

    /* KPI Cards - Structured Mobile Layout */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    @media (min-width: 992px) {
        .kpi-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
    }

    .kpi-card {
        background: #fff;
        border-radius: var(--radius-lg);
        padding: 1.25rem;
        box-shadow: var(--shadow);
        border: 1px solid var(--gray-100);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .kpi-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: var(--primary);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease;
    }

    .kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl);
    }

    .kpi-card:hover::after {
        transform: scaleX(1);
    }

    .kpi-card.success::after { background: var(--success); }
    .kpi-card.danger::after { background: var(--danger); }
    .kpi-card.warning::after { background: var(--warning); }
    .kpi-card.info::after { background: var(--info); }

    .kpi-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .kpi-icon {
        width: 38px;
        height: 38px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    @media (min-width: 768px) {
        .kpi-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius);
            font-size: 1.25rem;
        }
    }

    .kpi-icon.primary {
        background: rgba(99, 102, 241, 0.1);
        color: var(--primary);
    }

    .kpi-icon.success {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .kpi-icon.danger {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }

    .kpi-icon.info {
        background: rgba(59, 130, 246, 0.1);
        color: var(--info);
    }

    .kpi-trend {
        display: inline-flex;
        align-items: center;
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.15rem 0.4rem;
        border-radius: var(--radius-sm);
    }

    @media (min-width: 768px) {
        .kpi-trend {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
    }

    .kpi-trend.up {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .kpi-trend.down {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }

    .kpi-value {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--gray-800);
        line-height: 1.2;
        margin-bottom: 0.25rem;
        letter-spacing: -0.025em;
    }

    @media (min-width: 768px) {
        .kpi-value { font-size: 1.875rem; }
    }

    .kpi-label {
        font-size: 0.75rem;
        color: var(--gray-500);
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    @media (min-width: 768px) {
        .kpi-label { font-size: 0.875rem; }
    }

    /* Cards Layout */
    .modern-card {
        background: #fff;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow);
        border: 1px solid var(--gray-100);
        overflow: hidden;
        height: 100%;
    }

    .modern-card-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--gray-100);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(to right, #fff, var(--gray-50));
    }

    .modern-card-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--gray-800);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    @media (min-width: 768px) {
        .modern-card-title { font-size: 1rem; }
    }

    .modern-card-title .icon {
        width: 28px;
        height: 28px;
        border-radius: var(--radius-sm);
        background: rgba(99, 102, 241, 0.1);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }

    .badge-count {
        background: var(--gray-100);
        color: var(--gray-600);
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
    }

    /* Table & Optimization For Touch Targets */
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .modern-table thead th {
        background: var(--gray-50);
        color: var(--gray-500);
        font-size: 0.6875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.875rem 1rem;
        border-bottom: 1px solid var(--gray-200);
        white-space: nowrap;
    }

    .modern-table tbody tr {
        transition: background 0.15s ease;
    }

    .modern-table tbody tr:hover {
        background: rgba(99, 102, 241, 0.02);
    }

    .modern-table tbody td {
        padding: 0.875rem 1rem;
        border-bottom: 1px solid var(--gray-100);
        color: var(--gray-700);
        font-size: 0.85rem;
        vertical-align: middle;
    }

    .invoice-id {
        font-weight: 700;
        color: var(--gray-800);
    }

    .student-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .student-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    .student-name {
        font-weight: 600;
        color: var(--gray-800);
        white-space: nowrap;
    }

    .student-meta {
        font-size: 0.7rem;
        color: var(--gray-400);
    }

    .amount {
        font-weight: 700;
        font-size: 0.875rem;
        font-variant-numeric: tabular-nums;
    }

    .amount.total { color: var(--gray-800); }
    .amount.paid { color: var(--success); }
    .amount.balance { color: var(--danger); }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.625rem;
        border-radius: 9999px;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .status-badge::before {
        content: '';
        width: 5px;
        height: 5px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-paid {
        background: rgba(16, 185, 129, 0.1);
        color: #047857;
    }
    .status-paid::before { background: var(--success); }

    .status-unpaid {
        background: rgba(239, 68, 68, 0.1);
        color: #b91c1c;
    }
    .status-unpaid::before { background: var(--danger); }

    .status-partial {
        background: rgba(245, 158, 11, 0.1);
        color: #b45309;
    }
    .status-partial::before { background: var(--warning); }

    /* Payment Items */
    .payment-list {
        padding: 0.25rem 0;
    }

    .payment-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.875rem 1.25rem;
        border-bottom: 1px solid var(--gray-100);
        transition: all 0.2s ease;
    }

    .payment-item:last-child {
        border-bottom: none;
    }

    .payment-left {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .payment-icon {
        width: 34px;
        height: 34px;
        border-radius: var(--radius-sm);
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    .payment-details {
        display: flex;
        flex-direction: column;
    }

    .payment-id {
        font-weight: 700;
        color: var(--gray-800);
        font-size: 0.85rem;
    }

    .payment-time {
        font-size: 0.7rem;
        color: var(--gray-400);
        margin-top: 0.125rem;
    }

    .payment-amount {
        font-weight: 800;
        color: var(--success);
        font-size: 0.9rem;
        font-variant-numeric: tabular-nums;
    }

    .view-all {
        display: block;
        text-align: center;
        padding: 0.875rem;
        color: var(--primary);
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        border-top: 1px solid var(--gray-100);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 2.5rem 1.25rem;
        color: var(--gray-400);
    }

    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
        color: var(--gray-300);
    }

    .empty-state p {
        font-size: 0.85rem;
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(12px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-in {
        animation: fadeInUp 0.4s ease-out forwards;
    }

    .delay-1 { animation-delay: 0.05s; }
    .delay-2 { animation-delay: 0.1s; }
    .delay-3 { animation-delay: 0.15s; }
    .delay-4 { animation-delay: 0.2s; }
</style>

<div class="container-fluid px-3 py-3 py-md-4 dashboard-wrapper">

    {{-- HEADER --}}
    <div class="page-header animate-in">
        <div class="row align-items-center g-3">
            <div class="col-12 col-md-6">
                <h2>Finance Dashboard</h2>
                <p>School Accounting & Financial Management System</p>
            </div>
            <div class="col-12 col-md-6 header-actions">
                <div class="d-flex justify-content-start justify-content-md-end align-items-center gap-2 flex-wrap">
                    
                    {{-- ACADEMIC YEAR SWITCHER --}}
                    <form action="{{ route('dashboard.set-year') }}" method="POST" class="d-flex align-items-center gap-2 flex-grow-1 flex-md-grow-0">
                        @csrf
                        <select name="academic_year_id" class="form-select form-select-sm" style="min-width: 140px;">
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}"
                                    {{ session('academic_year_id') == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn btn-primary btn-sm text-nowrap">
                            Set Year
                        </button>
                    </form>

                    <a href="{{ route('invoices.create') }}" class="btn btn-primary-modern btn-sm text-white flex-grow-1 flex-md-grow-0 text-center">
                        <span>+ New Invoice</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- KPI CARDS --}}
    <div class="kpi-grid">
        <div class="kpi-card info animate-in delay-1">
            <div class="kpi-header">
                <div class="kpi-icon primary">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <span class="kpi-trend up">
                    <i class="fas fa-arrow-up" style="font-size: 0.625rem;"></i> 12%
                </span>
            </div>
            <div>
                <div class="kpi-value">{{ number_format($totalStudents) }}</div>
                <div class="kpi-label">Total Students</div>
            </div>
        </div>

        <div class="kpi-card success animate-in delay-2">
            <div class="kpi-header">
                <div class="kpi-icon success">
                    <i class="fas fa-chart-line"></i>
                </div>
                <span class="kpi-trend up">
                    <i class="fas fa-arrow-up" style="font-size: 0.625rem;"></i> 8.5%
                </span>
            </div>
            <div>
                <div class="kpi-value">${{ number_format($totalRevenue, 2) }}</div>
                <div class="kpi-label">Total Revenue</div>
            </div>
        </div>

        <div class="kpi-card danger animate-in delay-3">
            <div class="kpi-header">
                <div class="kpi-icon danger">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <span class="kpi-trend down">
                    <i class="fas fa-arrow-down" style="font-size: 0.625rem;"></i> 3.2%
                </span>
            </div>
            <div>
                <div class="kpi-value">${{ number_format($outstandingBalance, 2) }}</div>
                <div class="kpi-label">Outstanding Balance</div>
            </div>
        </div>

        <div class="kpi-card warning animate-in delay-4">
            <div class="kpi-header">
                <div class="kpi-icon info">
                    <i class="fas fa-users-cog"></i>
                </div>
                <span class="kpi-trend up">
                    <i class="fas fa-arrow-up" style="font-size: 0.625rem;"></i> 2.1%
                </span>
            </div>
            <div>
                <div class="kpi-value">{{ number_format($totalUsers) }}</div>
                <div class="kpi-label">System Users</div>
            </div>
        </div>
    </div>

    {{-- MAIN SECTION --}}
    <div class="row g-4">
        
        {{-- INVOICES TABLE --}}
        <div class="col-12 col-xl-8 animate-in delay-2">
            <div class="modern-card">
                <div class="modern-card-header flex-column flex-sm-row align-items-start align-items-sm-center gap-2">
                    <div class="modern-card-title flex-wrap">
                        <span class="icon"><i class="fas fa-file-invoice-dollar"></i></span>
                        Recent Invoices
                        @if($selectedYear)
                            <span class="year-badge mt-1 mt-sm-0">
                                <i class="fas fa-calendar"></i> {{ $selectedYearName }}
                            </span>
                        @endif
                    </div>
                    <span class="badge-count">{{ count($recentInvoices) }} records</span>
                </div>
                
                <div class="table-responsive">
                    @if(count($recentInvoices) > 0)
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>Invoice ID</th>
                                    <th>Student</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">Paid</th>
                                    <th class="text-end">Balance</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentInvoices as $invoice)
                                    @php
                                        $student = $invoice->student ?? null;
                                        $total = floatval($invoice->total_amount ?? 0);
                                        $paid = floatval($invoice->payments->sum('amount_paid') ?? 0);
                                        $balance = $total - $paid;
                                        
                                        if ($paid == 0) {
                                            $status = 'Unpaid';
                                            $statusClass = 'status-unpaid';
                                        } elseif ($balance <= 0) {
                                            $status = 'Paid';
                                            $statusClass = 'status-paid';
                                        } else {
                                            $status = 'Partial';
                                            $statusClass = 'status-partial';
                                        }
                                        
                                        $firstName = $student->first_name ?? 'Unknown';
                                        $lastName = $student->last_name ?? '';
                                        $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                                        if (strlen($initials) < 2) $initials = 'ST';
                                    @endphp
                                    
                                    <tr>
                                        <td>
                                            <span class="invoice-id">#{{ $invoice->invoice_no ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <div class="student-info">
                                                <div class="student-avatar">{{ $initials }}</div>
                                                <div>
                                                    <div class="student-name">
                                                        {{ $firstName }} {{ $lastName }}
                                                    </div>
                                                    <div class="student-meta">
                                                        ID: {{ $student->id ?? 'N/A' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <span class="amount total">${{ number_format($total, 2) }}</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="amount paid">${{ number_format($paid, 2) }}</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="amount balance">${{ number_format(max($balance, 0), 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="status-badge {{ $statusClass }}">
                                                {{ $status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-file-invoice"></i>
                            <p>No recent invoices found for {{ $selectedYearName }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- RECENT PAYMENTS --}}
        <div class="col-12 col-xl-4 animate-in delay-3">
            <div class="modern-card">
                <div class="modern-card-header">
                    <div class="modern-card-title">
                        <span class="icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                            <i class="fas fa-credit-card"></i>
                        </span>
                        Recent Payments
                    </div>
                    <span class="badge-count">{{ count($recentPayments) }} new</span>
                </div>
                
                <div class="payment-list">
                    @if(count($recentPayments) > 0)
                        @foreach($recentPayments as $payment)
                            <div class="payment-item">
                                <div class="payment-left">
                                    <div class="payment-icon">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div class="payment-details">
                                        <div class="payment-id">{{ $payment->receipt_no ?? 'N/A' }}</div>
                                        <div class="payment-time">
                                            <i class="far fa-clock" style="font-size: 0.625rem; margin-right: 0.25rem;"></i>
                                            {{ optional($payment->created_at)->diffForHumans() ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="payment-amount">
                                    +${{ number_format(floatval($payment->amount_paid ?? 0), 2) }}
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-credit-card"></i>
                            <p>No recent payments found for {{ $selectedYearName }}</p>
                        </div>
                    @endif
                </div>
                
                @if(count($recentPayments) > 0)
                    <a href="{{ route('payments.index') }}" class="view-all">
                        View All Payments <i class="fas fa-arrow-right ms-1" style="font-size: 0.75rem;"></i>
                    </a>
                @endif
            </div>
        </div>

    </div>

</div>

<script>
    function toggleYearDropdown() {
        const dropdown = document.getElementById('yearDropdown');
        dropdown.classList.toggle('show');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const switcher = document.querySelector('.year-switcher');
        const dropdown = document.getElementById('yearDropdown');
        if (switcher && dropdown && !switcher.contains(event.target)) {
            dropdown.classList.remove('show');
        }
    });
</script>

@endsection