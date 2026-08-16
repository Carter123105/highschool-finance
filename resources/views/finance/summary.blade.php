@extends('layouts.app')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
/* ============================================================
   FINANCE DASHBOARD — PROFESSIONAL COLOR SYSTEM
   ============================================================ */

:root {
    /* Primary Palette */
    --primary-50:  #eff6ff;   --primary-100: #dbeafe;   --primary-200: #bfdbfe;
    --primary-300: #93c5fd;   --primary-400: #60a5fa;   --primary-500: #3b82f6;
    --primary-600: #2563eb;   --primary-700: #1d4ed8;   --primary-800: #1e40af;   --primary-900: #1e3a8a;

    /* Success Palette */
    --success-50:  #f0fdf4;   --success-100: #dcfce7;   --success-200: #bbf7d0;
    --success-300: #86efac;   --success-400: #4ade80;   --success-500: #22c55e;
    --success-600: #16a34a;   --success-700: #15803d;   --success-800: #166534;   --success-900: #14532d;

    /* Danger Palette */
    --danger-50:   #fef2f2;   --danger-100: #fee2e2;   --danger-200: #fecaca;
    --danger-300:  #fca5a5;   --danger-400: #f87171;   --danger-500: #ef4444;
    --danger-600:  #dc2626;   --danger-700: #b91c1c;   --danger-800: #991b1b;   --danger-900: #7f1d1d;

    /* Warning Palette */
    --warning-50:  #fffbeb;   --warning-100: #fef3c7;   --warning-200: #fde68a;
    --warning-300: #fcd34d;   --warning-400: #fbbf24;   --warning-500: #f59e0b;
    --warning-600: #d97706;   --warning-700: #b45309;   --warning-800: #92400e;   --warning-900: #78350f;

    /* Slate Palette */
    --slate-50:  #f8fafc;   --slate-100: #f1f5f9;   --slate-200: #e2e8f0;
    --slate-300: #cbd5e1;   --slate-400: #94a3b8;   --slate-500: #64748b;
    --slate-600: #475569;   --slate-700: #334155;   --slate-800: #1e293b;   --slate-900: #0f172a;

    /* Shadows */
    --shadow-sm:  0 1px 2px 0 rgba(0,0,0,0.05);
    --shadow-md:  0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -2px rgba(0,0,0,0.05);
    --shadow-lg:  0 10px 15px -3px rgba(0,0,0,0.08), 0 4px 6px -4px rgba(0,0,0,0.04);
    --shadow-xl:  0 20px 25px -5px rgba(0,0,0,0.08), 0 8px 10px -6px rgba(0,0,0,0.03);
}

.finance-dashboard {
    background: var(--slate-50);
    min-height: 100vh;
    padding-bottom: 40px;
}

/* ── HEADER ─────────────────────────────────────────────── */
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 28px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--slate-200);
}

.dashboard-title {
    font-size: 30px;
    font-weight: 800;
    color: var(--slate-900);
    margin-bottom: 6px;
    letter-spacing: -0.5px;
}

.dashboard-subtitle {
    color: var(--slate-500);
    margin: 0;
    font-size: 14px;
    font-weight: 500;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

/* Search Box */
.search-box {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fff;
    padding: 10px 16px;
    border-radius: 12px;
    border: 1px solid var(--slate-200);
    box-shadow: var(--shadow-sm);
    transition: all 0.2s ease;
}

.search-box:focus-within {
    border-color: var(--primary-400);
    box-shadow: 0 0 0 3px var(--primary-100);
}

.search-box input {
    border: none;
    outline: none;
    background: transparent;
    min-width: 220px;
    font-size: 14px;
    color: var(--slate-800);
    font-weight: 500;
}

.search-box input::placeholder {
    color: var(--slate-400);
}

.search-box i {
    color: var(--slate-400);
    font-size: 16px;
}

/* Filter Button */
.btn-filter {
    width: 44px;
    height: 44px;
    border: 1px solid var(--slate-200);
    border-radius: 12px;
    background: #fff;
    color: var(--slate-600);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: var(--shadow-sm);
}

.btn-filter:hover {
    background: var(--slate-100);
    border-color: var(--slate-300);
    color: var(--slate-800);
}

/* Daily Transactions Button */
.btn-daily {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 11px 18px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--primary-600), var(--primary-500));
    color: #fff;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.25s ease;
    text-decoration: none;
    box-shadow: var(--shadow-md);
}

.btn-daily:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(37,99,235,0.35);
    color: #fff;
}

.btn-daily .badge {
    font-size: 11px;
    padding: 3px 8px;
    border-radius: 20px;
    background: rgba(255,255,255,0.25);
    color: #fff;
    font-weight: 700;
}

/* Add Expense Button */
.btn-expense {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 11px 18px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--danger-600), var(--danger-500));
    color: #fff;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.25s ease;
    box-shadow: var(--shadow-md);
}

.btn-expense:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(220,38,38,0.35);
    color: #fff;
}

/* ── STAT CARDS (PROFESSIONAL) ──────────────────────────── */
.stat-card {
    background: #fff;
    border-radius: 16px;
    padding: 24px;
    display: flex;
    align-items: flex-start;
    gap: 16px;
    box-shadow: var(--shadow-md);
    transition: all 0.3s ease;
    border: 1px solid var(--slate-100);
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    border-radius: 16px 16px 0 0;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-xl);
}

/* Top border colors per card type */
.stat-card.income::before  { background: linear-gradient(90deg, var(--success-500), var(--success-400)); }
.stat-card.expense::before { background: linear-gradient(90deg, var(--danger-500),  var(--danger-400)); }
.stat-card.warning::before { background: linear-gradient(90deg, var(--warning-500), var(--warning-400)); }
.stat-card.profit::before  { background: linear-gradient(90deg, var(--primary-500), var(--primary-400)); }

.stat-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    flex-shrink: 0;
    font-size: 22px;
    box-shadow: var(--shadow-sm);
}

.income  .stat-icon { background: linear-gradient(135deg, var(--success-600), var(--success-400)); }
.expense .stat-icon { background: linear-gradient(135deg, var(--danger-600),  var(--danger-400)); }
.warning .stat-icon { background: linear-gradient(135deg, var(--warning-600), var(--warning-400)); }
.profit  .stat-icon { background: linear-gradient(135deg, var(--primary-600), var(--primary-400)); }

.stat-body {
    display: flex;
    flex-direction: column;
    gap: 6px;
    flex: 1;
    min-width: 0;
}

.stat-label {
    font-size: 13px;
    color: var(--slate-500);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.6px;
}

.stat-value {
    font-size: 28px;
    font-weight: 800;
    margin: 0;
    line-height: 1.1;
    letter-spacing: -0.5px;
}

.stat-card.income  .stat-value { color: var(--success-700); }
.stat-card.expense .stat-value { color: var(--danger-700); }
.stat-card.warning .stat-value { color: var(--warning-700); }
.stat-card.profit  .stat-value { color: var(--primary-700); }

.stat-footer {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 4px;
}

.stat-trend {
    font-size: 12px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 3px;
}

.stat-trend.up {
    background: var(--success-100);
    color: var(--success-700);
}

.stat-trend.down {
    background: var(--danger-100);
    color: var(--danger-700);
}

.stat-trend.neutral {
    background: var(--slate-100);
    color: var(--slate-600);
}

.stat-trend-text {
    font-size: 12px;
    color: var(--slate-500);
    font-weight: 500;
}

/* ── DAILY ALERT BANNER ─────────────────────────────────── */
.daily-alert {
    border: none;
    border-radius: 16px;
    background: linear-gradient(135deg, var(--primary-50), var(--primary-100));
    color: var(--primary-800);
    padding: 18px 24px;
    border: 1px solid var(--primary-200);
    display: flex;
    align-items: center;
    gap: 16px;
}

.daily-alert-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: linear-gradient(135deg, var(--primary-500), var(--primary-400));
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
    box-shadow: var(--shadow-sm);
}

.daily-alert-content strong {
    color: var(--primary-900);
    font-weight: 700;
}

.daily-alert-content small {
    color: var(--primary-600);
    font-weight: 500;
}

.daily-alert-content small strong {
    font-weight: 700;
}

/* ── REPORT CARDS (PROFESSIONAL) ────────────────────────── */
.report-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 22px 24px;
    border-radius: 16px;
    color: #fff;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-md);
    border: none;
    position: relative;
    overflow: hidden;
}

.report-card::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: rgba(255,255,255,0.08);
    pointer-events: none;
}

.report-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
    color: #fff;
}

.report-left {
    display: flex;
    align-items: center;
    gap: 16px;
    z-index: 1;
}

.report-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: rgba(255,255,255,0.18);
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 22px;
    color: #fff;
    flex-shrink: 0;
    backdrop-filter: blur(4px);
}

.report-text {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.report-title {
    font-weight: 700;
    font-size: 15px;
    color: #fff;
    letter-spacing: -0.2px;
}

.report-subtitle {
    font-size: 12px;
    color: rgba(255,255,255,0.75);
    font-weight: 500;
}

.report-arrow {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: rgba(255,255,255,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.25s ease;
    z-index: 1;
}

.report-card:hover .report-arrow {
    background: rgba(255,255,255,0.3);
    transform: translateX(3px);
}

.report-arrow i {
    color: rgba(255,255,255,0.9);
    font-size: 16px;
}

/* Report Card Gradients */
.report-card.success   { background: linear-gradient(135deg, var(--success-600), var(--success-400)); }
.report-card.danger    { background: linear-gradient(135deg, var(--danger-600),  var(--danger-400)); }
.report-card.warning   { background: linear-gradient(135deg, var(--warning-600), var(--warning-400)); }
.report-card.primary   { background: linear-gradient(135deg, var(--primary-600), var(--primary-400)); }
.report-card.dark      { background: linear-gradient(135deg, var(--slate-800), var(--slate-700)); }
.report-card.info      { background: linear-gradient(135deg, #0ea5e9, #38bdf8); }
.report-card.secondary { background: linear-gradient(135deg, var(--slate-600), var(--slate-500)); }

/* ── MODAL STYLES ───────────────────────────────────────── */
.date-selector {
    background: var(--slate-50) !important;
    padding: 24px;
    border-bottom: 1px solid var(--slate-200);
}

.date-selector .form-label {
    color: var(--slate-800);
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.date-selector .form-label i {
    color: var(--primary-500);
}

.date-selector .form-control-lg {
    border-radius: 12px;
    border: 2px solid var(--slate-200);
    font-weight: 600;
    color: var(--slate-800);
    background: #fff;
    font-size: 15px;
    padding: 12px 16px;
}

.date-selector .form-control-lg:focus {
    border-color: var(--primary-400);
    box-shadow: 0 0 0 4px var(--primary-100);
}

/* Summary Cards */
.summary-card {
    background: #fff;
    padding: 22px;
    border-radius: 16px;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--slate-100);
    transition: all 0.2s ease;
}

.summary-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.summary-card small {
    font-size: 12px;
    color: var(--slate-500) !important;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.summary-card h4 {
    font-weight: 800;
    font-size: 24px;
    margin-top: 6px;
    letter-spacing: -0.5px;
}

.summary-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}

.summary-icon.bg-success { background: var(--success-100) !important; color: var(--success-600) !important; }
.summary-icon.bg-danger  { background: var(--danger-100) !important;  color: var(--danger-600) !important; }
.summary-icon.bg-dark    { background: var(--slate-100) !important;   color: var(--slate-700) !important; }

/* ── TABS ───────────────────────────────────────────────── */
.nav-tabs {
    border-bottom: 2px solid var(--slate-200);
    padding: 0 24px;
    gap: 4px;
}

.nav-tabs .nav-link {
    font-weight: 600;
    color: var(--slate-500);
    border: none;
    border-bottom: 3px solid transparent;
    padding: 14px 20px;
    margin-bottom: -2px;
    border-radius: 8px 8px 0 0;
    transition: all 0.2s ease;
}

.nav-tabs .nav-link:hover {
    color: var(--slate-700);
    background: var(--slate-50);
}

.nav-tabs .nav-link.active {
    color: var(--primary-700);
    border-bottom: 3px solid var(--primary-500);
    background: var(--primary-50);
    font-weight: 700;
}

.nav-tabs .nav-link i {
    color: var(--slate-400);
    transition: color 0.2s;
}

.nav-tabs .nav-link.active i {
    color: var(--primary-500);
}

.nav-tabs .badge {
    font-size: 11px;
    padding: 3px 8px;
    border-radius: 20px;
    font-weight: 700;
}

.nav-tabs .badge.bg-success { background: var(--success-500) !important; }
.nav-tabs .badge.bg-danger  { background: var(--danger-500) !important; }

/* ── TABLES ─────────────────────────────────────────────── */
.table {
    border-collapse: separate;
    border-spacing: 0;
}

.table thead th {
    color: var(--slate-600);
    font-weight: 700;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 14px 16px;
    background: var(--slate-50);
    border-bottom: 2px solid var(--slate-200);
    border-top: none;
}

.table tbody td {
    color: var(--slate-700);
    vertical-align: middle;
    padding: 14px 16px;
    border-bottom: 1px solid var(--slate-100);
    font-size: 14px;
    font-weight: 500;
}

.table tbody tr:hover {
    background: var(--slate-50);
}

.table tbody tr:last-child td {
    border-bottom: none;
}

/* Table Footer */
.table tfoot td {
    color: var(--slate-900);
    font-weight: 700;
    padding: 14px 16px;
    background: var(--slate-50);
    border-top: 2px solid var(--slate-200);
    font-size: 14px;
}

/* ── BADGES ─────────────────────────────────────────────── */
.badge {
    font-weight: 600;
    font-size: 12px;
    padding: 5px 10px;
    border-radius: 8px;
}

.badge.bg-secondary { background: var(--slate-400) !important; color: #fff; }
.badge.bg-info      { background: #0ea5e9 !important; color: #fff; }
.badge.bg-success   { background: var(--success-500) !important; color: #fff; }
.badge.bg-danger    { background: var(--danger-500) !important; color: #fff; }
.badge.bg-warning   { background: var(--warning-400) !important; color: var(--warning-900); }
.badge.bg-primary   { background: var(--primary-500) !important; color: #fff; }

/* ── TEXT UTILITIES ─────────────────────────────────────── */
.text-success { color: var(--success-600) !important; }
.text-danger  { color: var(--danger-600) !important; }
.text-warning { color: var(--warning-700) !important; }
.text-primary { color: var(--primary-600) !important; }
.text-info    { color: #0ea5e9 !important; }
.text-dark    { color: var(--slate-900) !important; }
.text-muted   { color: var(--slate-500) !important; }
.text-white   { color: #fff !important; }
.text-secondary-custom { color: var(--slate-600) !important; }

/* ── MODAL ──────────────────────────────────────────────── */
.modal-header.bg-primary {
    background: linear-gradient(135deg, var(--primary-600), var(--primary-500)) !important;
    padding: 20px 24px;
    border: none;
}

.modal-header .modal-title {
    color: #fff;
    font-weight: 700;
    font-size: 18px;
}

.modal-header .btn-close-white {
    filter: brightness(0) invert(1);
    opacity: 0.8;
}

.modal-header .btn-close-white:hover {
    opacity: 1;
}

.modal-footer {
    background: var(--slate-50) !important;
    border-top: 1px solid var(--slate-200);
    padding: 16px 24px;
}

.modal-footer small {
    color: var(--slate-500);
    font-weight: 500;
}

/* ── EMPTY STATE ────────────────────────────────────────── */
.empty-state-icon {
    color: var(--slate-300);
}

/* ── SPINNER ────────────────────────────────────────────── */
.spinner-border.text-primary {
    color: var(--primary-500) !important;
    width: 2.5rem;
    height: 2.5rem;
}

/* ── SECTION HEADERS ────────────────────────────────────── */
.section-label {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--slate-400);
    margin-bottom: 16px;
}

/* ── RESPONSIVE ─────────────────────────────────────────── */
@media (max-width: 768px) {
    .dashboard-title { font-size: 24px; }
    .search-box input { min-width: 140px; }
    .btn-expense, .btn-daily { width: 100%; justify-content: center; }
    .header-actions { width: 100%; }
    .header-actions > * { flex: 1; }
    .stat-value { font-size: 22px; }
    .stat-card { padding: 18px; }
    .report-card { padding: 18px 20px; }
    .daily-alert { flex-direction: column; text-align: center; }
}
</style>

@php
    // ============================================================
    // SAFE FALLBACKS
    // ============================================================
    $totalIncome      = $totalIncome      ?? 0;
    $totalExpenses    = $totalExpenses    ?? 0;
    $totalExpected    = $totalExpected    ?? 0;
    $totalOutstanding = $totalOutstanding ?? 0;
    $netProfit        = $netProfit        ?? ($totalIncome - $totalExpenses);

    $todayPayments = $todayPayments ?? collect();
    $todayExpenses = $todayExpenses ?? collect();

    $totalTodayPayments     = (float) $todayPayments->sum('amount_paid');
    $totalTodayExpenses     = (float) $todayExpenses->sum('amount');
    $totalTodayTransactions = $totalTodayPayments + $totalTodayExpenses;
    $todayCount             = $todayPayments->count() + $todayExpenses->count();
@endphp

<div class="finance-dashboard container-fluid py-4">

    {{-- HEADER --}}
    <div class="dashboard-header">
        <div>
            <h2 class="dashboard-title">Finance Dashboard</h2>
            <p class="dashboard-subtitle">Overview of income, expenses, balances and daily financial activities</p>
        </div>

        <div class="header-actions">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchReport" placeholder="Search reports...">
            </div>

            <button class="btn-filter" title="Filter">
                <i class="bi bi-funnel"></i>
            </button>

            <button type="button" class="btn-daily" onclick="openDailyModal()">
                <i class="bi bi-calendar-check"></i>
                Daily
                @if($todayCount > 0)
                    <span class="badge">{{ $todayCount }}</span>
                @endif
            </button>

            <a href="{{ route('expenses.create') }}" class="btn-expense">
                <i class="bi bi-plus-circle"></i>
                Add Expense
            </a>
        </div>
    </div>

    {{-- STATS ROW --}}
    <div class="row g-4 mb-4">
        {{-- INCOME --}}
        <div class="col-xl-3 col-md-6">
            <div class="stat-card income">
                <div class="stat-icon"><i class="bi bi-cash-coin"></i></div>
                <div class="stat-body">
                    <span class="stat-label">Total Income</span>
                    <h3 class="stat-value">${{ number_format($totalIncome, 2) }}</h3>
                    <div class="stat-footer">
                        <span class="stat-trend up"><i class="bi bi-arrow-up-short"></i> 12%</span>
                        <span class="stat-trend-text">vs last month</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- EXPENSES --}}
        <div class="col-xl-3 col-md-6">
            <div class="stat-card expense">
                <div class="stat-icon"><i class="bi bi-receipt"></i></div>
                <div class="stat-body">
                    <span class="stat-label">Total Expenses</span>
                    <h3 class="stat-value">${{ number_format($totalExpenses, 2) }}</h3>
                    <div class="stat-footer">
                        <span class="stat-trend down"><i class="bi bi-arrow-down-short"></i> 5%</span>
                        <span class="stat-trend-text">vs last month</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- OUTSTANDING FEES --}}
        <div class="col-xl-3 col-md-6">
            <div class="stat-card warning">
                <div class="stat-icon"><i class="bi bi-graph-up-arrow"></i></div>
                <div class="stat-body">
                    <span class="stat-label">Outstanding Fees</span>
                    <h3 class="stat-value {{ $totalOutstanding > 0 ? 'text-warning' : 'text-success' }}">
                        ${{ number_format($totalOutstanding, 2) }}
                    </h3>
                    <div class="stat-footer">
                        <span class="stat-trend {{ $totalOutstanding > 0 ? 'down' : 'up' }}">
                            <i class="bi bi-{{ $totalOutstanding > 0 ? 'arrow-up-short' : 'arrow-down-short' }}"></i>
                            {{ $totalOutstanding > 0 ? number_format(($totalOutstanding / max($totalExpected, 1)) * 100, 1) : '0' }}%
                        </span>
                        <span class="stat-trend-text">of expected</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- NET PROFIT --}}
        <div class="col-xl-3 col-md-6">
            <div class="stat-card profit">
                <div class="stat-icon"><i class="bi bi-bar-chart-line"></i></div>
                <div class="stat-body">
                    <span class="stat-label">Net Profit</span>
                    <h3 class="stat-value {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">
                        ${{ number_format($netProfit, 2) }}
                    </h3>
                    <div class="stat-footer">
                        <span class="stat-trend {{ $netProfit >= 0 ? 'up' : 'down' }}">
                            <i class="bi bi-{{ $netProfit >= 0 ? 'arrow-up-short' : 'arrow-down-short' }}"></i>
                            {{ $totalIncome > 0 ? number_format(($netProfit / $totalIncome) * 100, 1) : '0' }}%
                        </span>
                        <span class="stat-trend-text">profit margin</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DAILY ALERT --}}
    @if($todayCount > 0)
        <div class="alert daily-alert mb-4" role="alert">
            <div class="daily-alert-icon">
                <i class="bi bi-calendar2-check-fill"></i>
            </div>
            <div class="daily-alert-content">
                <div><strong>Today's Transactions</strong> — {{ $todayCount }} recorded totaling <strong>${{ number_format($totalTodayTransactions, 2) }}</strong></div>
                <small>
                    <span class="text-success"><i class="bi bi-cash-coin me-1"></i>Payments: ${{ number_format($totalTodayPayments, 2) }}</span>
                    <span class="mx-2">|</span>
                    <span class="text-danger"><i class="bi bi-receipt me-1"></i>Expenses: ${{ number_format($totalTodayExpenses, 2) }}</span>
                </small>
            </div>
        </div>
    @endif

    {{-- REPORTS GRID --}}
    <div class="mb-3">
        <span class="section-label"><i class="bi bi-grid-3x3-gap me-2"></i>Quick Reports</span>
    </div>
    <div class="row g-4" id="reportGrid">
        @php
            $reports = [
                ['name'=>'Income Report',     'route'=>'finance.income',    'icon'=>'cash-stack',       'color'=>'success',   'desc'=>'Track all income sources'],
                ['name'=>'Expenses Report',   'route'=>'finance.expenses',  'icon'=>'receipt',          'color'=>'danger',    'desc'=>'Monitor spending patterns'],
                ['name'=>'Balance Report',    'route'=>'finance.balance',   'icon'=>'graph-up',         'color'=>'warning',   'desc'=>'View financial balance'],
                ['name'=>'Payments Report',   'route'=>'finance.payments',  'icon'=>'person-check',     'color'=>'primary',   'desc'=>'Student payment records'],
                ['name'=>'Class Report',      'route'=>'finance.classes',   'icon'=>'building',         'color'=>'dark',      'desc'=>'Class-wise financials'],
                ['name'=>'Student Report',    'route'=>'finance.students',  'icon'=>'people',           'color'=>'info',      'desc'=>'Individual student data'],
                ['name'=>'Invoice Report',    'route'=>'finance.invoices',  'icon'=>'file-earmark-text','color'=>'secondary', 'desc'=>'Invoice generation & tracking'],
            ];
        @endphp

        @foreach($reports as $report)
            <div class="col-xl-3 col-lg-4 col-md-6 report-item">
                <a href="{{ route($report['route']) }}" class="report-card {{ $report['color'] }}">
                    <div class="report-left">
                        <div class="report-icon">
                            <i class="bi bi-{{ $report['icon'] }}"></i>
                        </div>
                        <div class="report-text">
                            <div class="report-title">{{ $report['name'] }}</div>
                            <div class="report-subtitle">{{ $report['desc'] }}</div>
                        </div>
                    </div>
                    <div class="report-arrow">
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

</div>

{{-- DAILY TRANSACTIONS MODAL --}}
<div class="modal fade" id="dailyTransactionsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-calendar-check me-2"></i>
                    Daily Transactions Tracker
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-0">
                {{-- Date Selector --}}
                <div class="date-selector">
                    <div class="row align-items-end g-3">
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="bi bi-calendar3 me-1"></i>Select Date
                            </label>
                            <input type="date" id="transactionDate" class="form-control form-control-lg"
                                   value="{{ now()->format('Y-m-d') }}" max="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-outline-primary" onclick="loadToday()">
                                <i class="bi bi-calendar-day me-1"></i>Today
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="loadYesterday()">
                                <i class="bi bi-calendar-minus me-1"></i>Yesterday
                            </button>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <span class="badge bg-primary fs-6 px-3 py-2" id="selectedDateBadge">
                                <i class="bi bi-calendar-date me-1"></i>
                                {{ now()->format('l, F d, Y') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Loading --}}
                <div id="loadingSpinner" class="text-center py-5 d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Loading transactions...</p>
                </div>

                {{-- Content --}}
                <div id="dailyContent">
                    {{-- Summary --}}
                    <div class="p-4 bg-light">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="summary-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small>Payments Received</small>
                                            <h4 class="text-success mb-0" id="totalPayments">
                                                ${{ number_format($totalTodayPayments, 2) }}
                                            </h4>
                                            <small id="countPayments">{{ $todayPayments->count() }} payment(s)</small>
                                        </div>
                                        <div class="summary-icon bg-success">
                                            <i class="bi bi-cash-coin"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="summary-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small>Expenses Made</small>
                                            <h4 class="text-danger mb-0" id="totalExpenses">
                                                ${{ number_format($totalTodayExpenses, 2) }}
                                            </h4>
                                            <small id="countExpenses">{{ $todayExpenses->count() }} expense(s)</small>
                                        </div>
                                        <div class="summary-icon bg-danger">
                                            <i class="bi bi-receipt"></i>
                                        </div>
                                        </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="summary-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small>Net Flow</small>
                                            <h4 class="text-dark mb-0" id="totalNet">
                                                ${{ number_format($totalTodayTransactions, 2) }}
                                            </h4>
                                            <small id="countTotal">{{ $todayCount }} total transaction(s)</small>
                                        </div>
                                        <div class="summary-icon bg-dark">
                                            <i class="bi bi-bar-chart"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tabs --}}
                    <ul class="nav nav-tabs pt-3" id="dailyTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="payments-tab" data-bs-toggle="tab" data-bs-target="#paymentsPane" type="button" role="tab">
                                <i class="bi bi-cash-coin me-1"></i>Payments
                                <span class="badge bg-success ms-1" id="tabPayCount">{{ $todayPayments->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="expenses-tab" data-bs-toggle="tab" data-bs-target="#expensesPane" type="button" role="tab">
                                <i class="bi bi-receipt me-1"></i>Expenses
                                <span class="badge bg-danger ms-1" id="tabExpCount">{{ $todayExpenses->count() }}</span>
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content p-4" id="dailyTabContent">
                        {{-- Payments Tab --}}
                        <div class="tab-pane fade show active" id="paymentsPane" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Receipt No</th>
                                            <th>Student</th>
                                            <th>Amount Paid</th>
                                            <th>Method</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                        </tr>
                                    </thead>
                                    <tbody id="paymentsTableBody">
                                        @forelse($todayPayments as $index => $payment)
                                            <tr>
                                                <td class="text-secondary-custom">{{ $index + 1 }}</td>
                                                <td><span class="badge bg-secondary">{{ $payment->receipt_no ?? 'N/A' }}</span></td>
                                                <td>
                                                    <strong class="text-dark">{{ $payment->student->first_name ?? '' }} {{ $payment->student->last_name ?? '' }}</strong>
                                                    <br><small class="text-muted">{{ $payment->student->student_id ?? '' }}</small>
                                                </td>
                                                <td class="fw-bold text-success">${{ number_format($payment->amount_paid, 2) }}</td>
                                                <td><span class="badge bg-info">{{ $payment->payment_method ?? 'Cash' }}</span></td>
                                                <td class="text-secondary-custom">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</td>
                                                <td>
                                                    <span class="badge bg-success">
                                                        {{ \Carbon\Carbon::parse($payment->created_at)->format('h:i A') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-5">
                                                    <i class="bi bi-inbox fs-1 d-block mb-3 empty-state-icon"></i>
                                                    No payments recorded today
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if($todayPayments->count() > 0)
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="text-dark">Total Payments</td>
                                                <td class="text-success">${{ number_format($totalTodayPayments, 2) }}</td>
                                                <td colspan="3"></td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>

                        {{-- Expenses Tab --}}
                        <div class="tab-pane fade" id="expensesPane" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th>Amount</th>
                                            <th>Description</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                        </tr>
                                    </thead>
                                    <tbody id="expensesTableBody">
                                        @forelse($todayExpenses as $index => $expense)
                                            <tr>
                                                <td class="text-secondary-custom">{{ $index + 1 }}</td>
                                                <td><strong class="text-dark">{{ $expense->title ?? 'N/A' }}</strong></td>
                                                <td>
                                                    <span class="badge bg-{{ $expense->category ? 'info' : 'warning' }}">
                                                        {{ $expense->category ?? 'Uncategorized' }}
                                                    </span>
                                                </td>
                                                <td class="fw-bold text-danger">${{ number_format($expense->amount, 2) }}</td>
                                                <td><small class="text-muted">{{ \Illuminate\Support\Str::limit($expense->description, 50) }}</small></td>
                                                <td class="text-secondary-custom">{{ $expense->expense_date ? \Carbon\Carbon::parse($expense->expense_date)->format('M d, Y') : 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-danger">
                                                        {{ \Carbon\Carbon::parse($expense->created_at)->format('h:i A') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-5">
                                                    <i class="bi bi-inbox fs-1 d-block mb-3 empty-state-icon"></i>
                                                    No expenses recorded today
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if($todayExpenses->count() > 0)
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="text-dark">Total Expenses</td>
                                                <td class="text-danger">${{ number_format($totalTodayExpenses, 2) }}</td>
                                                <td colspan="3"></td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="d-flex justify-content-between w-100 align-items-center">
                    <small id="footerDateText">
                        <i class="bi bi-info-circle me-1"></i>
                        Showing transactions for {{ now()->format('l, F d, Y') }}
                    </small>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // Search reports
    const searchInput = document.getElementById('searchReport');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            let value = this.value.toLowerCase();
            document.querySelectorAll('.report-item').forEach(item => {
                item.style.display = item.innerText.toLowerCase().includes(value) ? 'block' : 'none';
            });
        });
    }

    // Modal
    const modalEl = document.getElementById('dailyTransactionsModal');
    if (!modalEl) return;

    const dailyModal = new bootstrap.Modal(modalEl);
    let currentDate = '{{ now()->format('Y-m-d') }}';

    window.openDailyModal = function() {
        dailyModal.show();
    };

    window.loadToday = function() {
        let today = new Date().toISOString().split('T')[0];
        document.getElementById('transactionDate').value = today;
        loadDailyData(today);
    };

    window.loadYesterday = function() {
        let yesterday = new Date();
        yesterday.setDate(yesterday.getDate() - 1);
        document.getElementById('transactionDate').value = yesterday.toISOString().split('T')[0];
        loadDailyData(yesterday.toISOString().split('T')[0]);
    };

    window.loadDailyData = function(date) {
        currentDate = date;
        document.getElementById('loadingSpinner').classList.remove('d-none');
        document.getElementById('dailyContent').classList.add('d-none');

        fetch('/finance/daily-transactions?date=' + encodeURIComponent(date), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) updateModalContent(data, date);
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Failed to load transactions');
        })
        .finally(() => {
            document.getElementById('loadingSpinner').classList.add('d-none');
            document.getElementById('dailyContent').classList.remove('d-none');
        });
    };

    window.updateModalContent = function(data, date) {
        let d = new Date(date + 'T00:00:00');
        let formatted = d.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

        document.getElementById('selectedDateBadge').innerHTML = '<i class="bi bi-calendar-date me-1"></i>' + formatted;
        document.getElementById('footerDateText').innerHTML = '<i class="bi bi-info-circle me-1"></i>Showing transactions for ' + formatted;

        let tp = parseFloat(data.totalPayments || 0);
        let te = parseFloat(data.totalExpenses || 0);
        let pc = data.payments ? data.payments.length : 0;
        let ec = data.expenses ? data.expenses.length : 0;

        document.getElementById('totalPayments').textContent = '$' + tp.toFixed(2);
        document.getElementById('countPayments').textContent = pc + ' payment(s)';
        document.getElementById('totalExpenses').textContent = '$' + te.toFixed(2);
        document.getElementById('countExpenses').textContent = ec + ' expense(s)';
        document.getElementById('totalNet').textContent = '$' + (tp + te).toFixed(2);
        document.getElementById('countTotal').textContent = (pc + ec) + ' total transaction(s)';
        document.getElementById('tabPayCount').textContent = pc;
        document.getElementById('tabExpCount').textContent = ec;

        // Payments table
        let pb = document.getElementById('paymentsTableBody');
        let pf = document.querySelector('#paymentsPane tfoot');
        if (pc === 0) {
            pb.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-5"><i class="bi bi-inbox fs-1 d-block mb-3 empty-state-icon"></i>No payments recorded</td></tr>';
            if (pf) pf.classList.add('d-none');
        } else {
            pb.innerHTML = data.payments.map((p, i) =>
                '<tr>' +
                    '<td class="text-secondary-custom">' + (i+1) + '</td>' +
                    '<td><span class="badge bg-secondary">' + (p.receipt_no||'N/A') + '</span></td>' +
                    '<td><strong class="text-dark">' + (p.student_name||'Unknown') + '</strong><br><small class="text-muted">' + (p.student_id||'') + '</small></td>' +
                    '<td class="fw-bold text-success">$' + parseFloat(p.amount_paid).toFixed(2) + '</td>' +
                    '<td><span class="badge bg-info">' + (p.payment_method||'Cash') + '</span></td>' +
                    '<td class="text-secondary-custom">' + (p.payment_date||'N/A') + '</td>' +
                    '<td><span class="badge bg-success">' + (p.time||'N/A') + '</span></td>' +
                '</tr>'
            ).join('');
            if (pf) {
                pf.classList.remove('d-none');
                pf.querySelector('td.text-success').textContent = '$' + tp.toFixed(2);
            }
        }

        // Expenses table
        let eb = document.getElementById('expensesTableBody');
        let ef = document.querySelector('#expensesPane tfoot');
        if (ec === 0) {
            eb.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-5"><i class="bi bi-inbox fs-1 d-block mb-3 empty-state-icon"></i>No expenses recorded</td></tr>';
            if (ef) ef.classList.add('d-none');
        } else {
            eb.innerHTML = data.expenses.map((e, i) => {
                let badgeClass = e.category && e.category !== 'N/A' ? 'bg-info' : 'bg-warning';
                return '<tr>' +
                    '<td class="text-secondary-custom">' + (i+1) + '</td>' +
                    '<td><strong class="text-dark">' + (e.title||'N/A') + '</strong></td>' +
                    '<td><span class="badge ' + badgeClass + '">' + (e.category||'Uncategorized') + '</span></td>' +
                    '<td class="fw-bold text-danger">$' + parseFloat(e.amount).toFixed(2) + '</td>' +
                    '<td><small class="text-muted">' + (e.description ? e.description.substring(0,50) : '') + '</small></td>' +
                    '<td class="text-secondary-custom">' + (e.expense_date||'N/A') + '</td>' +
                    '<td><span class="badge bg-danger">' + (e.time||'N/A') + '</span></td>' +
                '</tr>';
            }).join('');
            if (ef) {
                ef.classList.remove('d-none');
                ef.querySelector('td.text-danger').textContent = '$' + te.toFixed(2);
            }
        }
    };

    document.getElementById('transactionDate').addEventListener('change', function() {
        loadDailyData(this.value);
    });
});
</script>

@endsection