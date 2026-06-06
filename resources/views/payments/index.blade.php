@extends('layouts.app')

@section('content')

<style>
    :root{
        --primary:#4f46e5;
        --primary-dark:#4338ca;
        --primary-light:#eef2ff;
        --success:#059669;
        --success-light:#ecfdf5;
        --warning:#d97706;
        --warning-light:#fffbeb;
        --danger:#dc2626;
        --danger-light:#fef2f2;
        --info:#0284c7;
        --info-light:#f0f9ff;
        --gray-50:#f8fafc;
        --gray-100:#f1f5f9;
        --gray-200:#e2e8f0;
        --gray-300:#cbd5e1;
        --gray-400:#94a3b8;
        --gray-500:#64748b;
        --gray-600:#475569;
        --gray-700:#334155;
        --gray-800:#1e293b;
        --gray-900:#0f172a;
        --radius:14px;
        --radius-sm:10px;
        --shadow-sm:0 2px 6px rgba(0,0,0,.04);
        --shadow:0 8px 24px rgba(15,23,42,.06);
        --shadow-lg:0 16px 40px rgba(15,23,42,.10);
    }

    body{ background:#f4f7fb; }

    .payments-dashboard{ max-width:1200px; margin:auto; }

    /* HEADER */
    .page-header{
        background:#fff;
        border-radius:18px;
        padding:1.2rem 1.5rem;
        margin-bottom:1.2rem;
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:1rem;
        flex-wrap:wrap;
        border:1px solid var(--gray-100);
        box-shadow:var(--shadow-sm);
    }

    .page-title-wrap{ display:flex; align-items:center; gap:.8rem; }

    .title-icon{
        width:48px; height:48px; border-radius:14px;
        background:var(--primary-light); color:var(--primary);
        display:flex; align-items:center; justify-content:center;
        font-size:1.2rem;
    }

    .page-title{ margin:0; font-size:1.3rem; font-weight:800; color:var(--gray-900); }
    .page-subtitle{ margin:0; margin-top:.2rem; color:var(--gray-500); font-size:.82rem; }

    .btn-add-payment{
        display:inline-flex; align-items:center; gap:.5rem;
        background:linear-gradient(135deg,var(--primary),#6366f1);
        color:#fff; text-decoration:none;
        padding:.8rem 1.2rem; border-radius:12px;
        font-weight:700; font-size:.88rem; transition:.25s ease;
        box-shadow:var(--shadow-sm);
    }
    .btn-add-payment:hover{ transform:translateY(-2px); color:#fff; }

    /* ALERT */
    .alert-modern{
        border:none; border-radius:12px; padding:.85rem 1.1rem;
        display:flex; align-items:center; gap:.6rem;
        font-weight:700; font-size:.9rem; margin-bottom:1.2rem;
    }
    .alert-success-modern{
        background:var(--success-light); color:var(--success);
        border-left:4px solid var(--success);
    }

    /* STATS - 3 CARDS */
    .stats-grid{
        display:grid;
        grid-template-columns:repeat(3, 1fr);
        gap:.8rem;
        margin-bottom:1.2rem;
    }
    @media(max-width:768px){ .stats-grid{ grid-template-columns:1fr; } }

    .stat-card{
        background:#fff; border-radius:16px; padding:1.2rem;
        border:1px solid var(--gray-100); box-shadow:var(--shadow-sm);
        display:flex; align-items:center; gap:.8rem;
        transition:.25s ease; position:relative; overflow:hidden;
    }
    .stat-card:hover{ transform:translateY(-2px); box-shadow:var(--shadow); }

    .stat-card::after{
        content:''; position:absolute; top:0; left:0;
        width:100%; height:3px; transform:scaleX(0);
        transform-origin:left; transition:transform 0.3s ease;
    }
    .stat-card:hover::after{ transform:scaleX(1); }

    .stat-card.blue::after{ background:var(--primary); }
    .stat-card.green::after{ background:var(--success); }
    .stat-card.orange::after{ background:var(--warning); }

    .stat-icon{
        width:48px; height:48px; border-radius:12px;
        display:flex; align-items:center; justify-content:center;
        font-size:1.2rem;
    }
    .stat-icon.blue{ background:var(--primary-light); color:var(--primary); }
    .stat-icon.green{ background:var(--success-light); color:var(--success); }
    .stat-icon.orange{ background:var(--warning-light); color:var(--warning); }

    .stat-info{ flex:1; }
    .stat-label{ font-size:.72rem; font-weight:700; color:var(--gray-500); text-transform:uppercase; margin-bottom:.15rem; }
    .stat-value{ font-size:1.4rem; font-weight:800; color:var(--gray-900); }

    .stat-trend{
        display:inline-flex; align-items:center; gap:.25rem;
        font-size:.75rem; font-weight:700; padding:.25rem .5rem;
        border-radius:999px;
    }
    .trend-up{ background:rgba(16,185,129,0.1); color:var(--success); }
    .trend-down{ background:rgba(239,68,68,0.1); color:var(--danger); }

    /* SUMMARY BAR */
    .summary-bar{
        background:#fff; border-radius:16px; padding:1.2rem 1.5rem;
        border:1px solid var(--gray-100); box-shadow:var(--shadow-sm);
        margin-bottom:1.2rem;
        display:flex; justify-content:space-between; align-items:center;
        flex-wrap:wrap; gap:1rem;
    }

    .summary-item{ text-align:center; }
    .summary-item small{ display:block; font-size:.68rem; font-weight:700; color:var(--gray-500); text-transform:uppercase; margin-bottom:.3rem; }
    .summary-item strong{ font-size:1.1rem; font-weight:800; }
    .summary-item .paid{ color:var(--success); }
    .summary-item .balance{ color:var(--danger); }
    .summary-item .expected{ color:var(--gray-800); }

    .summary-divider{ width:1px; height:40px; background:var(--gray-200); }

    /* MAIN CARD */
    .main-card{
        background:#fff; border-radius:20px; overflow:hidden;
        border:1px solid var(--gray-100); box-shadow:var(--shadow);
    }

    .card-header-custom{
        padding:1rem 1.3rem;
        display:flex; justify-content:space-between; align-items:center;
        gap:1rem; flex-wrap:wrap;
        border-bottom:1px solid var(--gray-100);
    }

    .card-header-title{
        display:flex; align-items:center; gap:.5rem;
        font-size:1rem; font-weight:800; color:var(--gray-800);
    }

    .header-badge{
        background:var(--primary-light); color:var(--primary);
        padding:.3rem .7rem; border-radius:999px;
        font-size:.72rem; font-weight:700;
    }

    /* SEARCH */
    .search-box{ position:relative; width:280px; max-width:100%; }
    .search-box i{ position:absolute; left:.85rem; top:50%; transform:translateY(-50%); color:var(--gray-400); font-size:.85rem; }
    .search-box input{
        width:100%; background:var(--gray-50);
        border:1px solid var(--gray-200); border-radius:12px;
        padding:.7rem .9rem .7rem 2.4rem; font-size:.85rem; transition:.2s;
    }
    .search-box input:focus{
        outline:none; border-color:var(--primary); background:#fff;
        box-shadow:0 0 0 3px var(--primary-light);
    }

    /* PAYMENTS LIST */
    .payments-list{ padding:1.2rem; display:flex; flex-direction:column; gap:.9rem; }

    .payment-record-card{
        border:1px solid var(--gray-100); border-radius:16px;
        background:#fff; overflow:hidden;
        box-shadow:var(--shadow-sm); transition:.25s ease;
    }
    .payment-record-card:hover{ transform:translateY(-1px); box-shadow:var(--shadow); }

    /* TOP */
    .record-top{
        padding:1rem 1.2rem;
        display:flex; justify-content:space-between; align-items:center;
        gap:1rem; flex-wrap:wrap;
        border-bottom:1px solid var(--gray-100);
        background:linear-gradient(to right,#ffffff,#fafbff);
    }

    .student-section{ display:flex; align-items:center; gap:.8rem; }

    .student-avatar{
        width:42px; height:42px; border-radius:50%;
        background:linear-gradient(135deg,var(--primary),#6366f1);
        color:#fff; font-weight:800; font-size:.85rem;
        display:flex; align-items:center; justify-content:center;
    }

    .student-name{ margin:0; font-size:.95rem; font-weight:800; color:var(--gray-900); }
    .student-meta{ margin-top:.1rem; color:var(--gray-500); font-size:.75rem; }

    /* STATUS */
    .status-pill{
        display:inline-flex; align-items:center; gap:.35rem;
        padding:.45rem .75rem; border-radius:999px;
        font-size:.72rem; font-weight:800;
    }
    .status-paid{ background:var(--success-light); color:var(--success); }
    .status-partial{ background:var(--warning-light); color:var(--warning); }
    .status-not-paid{ background:var(--danger-light); color:var(--danger); }
    .status-dot{ width:6px; height:6px; border-radius:50%; }
    .status-dot.paid{ background:var(--success); }
    .status-dot.partial{ background:var(--warning); }
    .status-dot.not-paid{ background:var(--danger); }

    /* BODY */
    .record-body{ padding:1rem 1.2rem; }

    .record-stats{
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(160px,1fr));
        gap:.7rem;
    }

    .mini-stat{
        background:var(--gray-50); border:1px solid var(--gray-100);
        border-radius:12px; padding:.75rem;
    }
    .mini-stat small{ display:block; font-size:.68rem; font-weight:700; color:var(--gray-500); margin-bottom:.3rem; text-transform:uppercase; }
    .mini-stat h5{ margin:0; font-size:1rem; font-weight:800; }
    .amount-invoice{ color:var(--gray-800); }
    .amount-paid{ color:var(--success); }
    .amount-balance{ color:var(--danger); }

    /* PROGRESS */
    .progress-bar-bg{ width:100%; height:6px; background:var(--gray-200); border-radius:999px; overflow:hidden; }
    .progress-bar-fill{ height:100%; border-radius:999px; }
    .progress-bar-fill.success{ background:var(--success); }
    .progress-bar-fill.warning{ background:var(--warning); }
    .progress-bar-fill.danger{ background:var(--danger); }
    .progress-text{ margin-top:.3rem; font-size:.7rem; font-weight:700; color:var(--gray-500); }

    /* VIEW BUTTON */
    .view-details-wrap{ margin-top:1rem; }
    .btn-toggle{
        width:100%; border:none; border-radius:12px;
        background:var(--primary-light); color:var(--primary);
        padding:.75rem 1rem; font-weight:800; font-size:.82rem;
        display:flex; justify-content:center; align-items:center; gap:.5rem;
        transition:.2s ease; cursor:pointer;
    }
    .btn-toggle:hover{ background:var(--primary); color:#fff; }

    /* DETAILS PANEL */
    .payment-details{
        display:none; margin-top:1rem;
        background:#fafcff; border:1px solid var(--gray-100);
        border-radius:14px; padding:1rem;
    }
    .payment-details.active{ display:block; }

    .details-grid{
        display:grid;
        grid-template-columns:1fr 260px;
        gap:1rem;
    }

    .record-section{ margin-bottom:1rem; }
    .record-section:last-child{ margin-bottom:0; }

    .section-title{
        display:flex; align-items:center; gap:.4rem;
        margin-bottom:.7rem; font-size:.78rem;
        font-weight:800; color:var(--gray-700);
    }

    .fees-grid{
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
        gap:.6rem;
    }

    .fee-item{
        background:#fff; border:1px solid var(--gray-100);
        border-radius:12px; padding:.7rem .9rem;
        display:flex; justify-content:space-between; align-items:center; gap:.8rem;
    }
    .fee-item span{ font-size:.78rem; font-weight:700; color:var(--gray-700); }
    .fee-item strong{ color:var(--success); font-size:.8rem; }

    .user-tags{ display:flex; flex-wrap:wrap; gap:.4rem; }
    .user-tag{
        background:var(--gray-100); color:var(--gray-700);
        padding:.4rem .7rem; border-radius:999px;
        font-size:.72rem; font-weight:700;
    }

    .record-actions{ display:flex; flex-wrap:wrap; gap:.5rem; }
    .record-actions form{ margin:0; }

    .btn-action{
        border:none; border-radius:10px;
        padding:.6rem .85rem;
        display:inline-flex; align-items:center; gap:.35rem;
        font-size:.72rem; font-weight:700;
        text-decoration:none; transition:.2s; cursor:pointer;
    }
    .btn-view{ background:var(--info-light); color:var(--info); }
    .btn-view:hover{ background:var(--info); color:#fff; }
    .btn-edit{ background:var(--warning-light); color:var(--warning); }
    .btn-edit:hover{ background:var(--warning); color:#fff; }
    .btn-delete{ background:var(--danger-light); color:var(--danger); }
    .btn-delete:hover{ background:var(--danger); color:#fff; }

    /* EMPTY */
    .empty-state{ text-align:center; padding:3rem 1rem; }
    .empty-state i{ font-size:2.5rem; color:var(--gray-300); margin-bottom:.8rem; display:block; }
    .empty-state h5{ font-weight:800; color:var(--gray-700); font-size:1.1rem; }
    .empty-state p{ color:var(--gray-500); font-size:.85rem; }

    /* ENHANCED PAGINATION */
    .pagination-container{
        padding:1.5rem 1.2rem;
        border-top:1px solid var(--gray-100);
        background:linear-gradient(to bottom, #fff, var(--gray-50));
    }

    .pagination-wrapper{
        display:flex;
        justify-content:space-between;
        align-items:center;
        flex-wrap:wrap;
        gap:1rem;
    }

    .pagination-info-text{
        font-size:.85rem;
        color:var(--gray-500);
        font-weight:600;
    }

    .pagination-info-text strong{
        color:var(--gray-800);
        font-weight:700;
    }

    .pagination-controls{
        display:flex;
        align-items:center;
        gap:.4rem;
    }

    .pagination-btn{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-width:40px;
        height:40px;
        padding:.5rem .9rem;
        border-radius:10px;
        font-size:.85rem;
        font-weight:700;
        text-decoration:none;
        transition:all .2s ease;
        border:1px solid var(--gray-200);
        background:#fff;
        color:var(--gray-600);
        cursor:pointer;
    }

    .pagination-btn:hover:not(:disabled){
        background:var(--primary-light);
        color:var(--primary);
        border-color:var(--primary-light);
        transform:translateY(-1px);
        box-shadow:var(--shadow-sm);
    }

    .pagination-btn:disabled{
        opacity:.5;
        cursor:not-allowed;
        background:var(--gray-50);
        color:var(--gray-400);
    }

    .pagination-btn.active{
        background:linear-gradient(135deg,var(--primary),#6366f1);
        color:#fff;
        border-color:var(--primary);
        box-shadow:0 4px 12px rgba(99,102,241,.3);
    }

    .pagination-btn.nav-btn{
        display:inline-flex;
        align-items:center;
        gap:.3rem;
        padding:.5rem 1rem;
    }

    .pagination-ellipsis{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-width:40px;
        height:40px;
        color:var(--gray-400);
        font-weight:800;
        font-size:1rem;
    }

    .items-per-page{
        display:flex;
        align-items:center;
        gap:.6rem;
    }

    .items-per-page label{
        font-size:.8rem;
        color:var(--gray-500);
        font-weight:600;
    }

    .items-per-page select{
        padding:.4rem .8rem;
        border-radius:8px;
        border:1px solid var(--gray-200);
        background:#fff;
        color:var(--gray-700);
        font-size:.85rem;
        font-weight:600;
        cursor:pointer;
        outline:none;
    }

    .items-per-page select:focus{
        border-color:var(--primary);
        box-shadow:0 0 0 3px var(--primary-light);
    }

    .go-to-page{
        display:flex;
        align-items:center;
        gap:.4rem;
    }

    .go-to-page label{
        font-size:.8rem;
        color:var(--gray-500);
        font-weight:600;
    }

    .go-to-page input{
        width:60px;
        padding:.4rem .6rem;
        border-radius:8px;
        border:1px solid var(--gray-200);
        text-align:center;
        font-size:.85rem;
        font-weight:700;
        color:var(--gray-800);
        outline:none;
    }

    .go-to-page input:focus{
        border-color:var(--primary);
        box-shadow:0 0 0 3px var(--primary-light);
    }

    .go-btn{
        padding:.4rem .8rem;
        border-radius:8px;
        border:none;
        background:var(--primary);
        color:#fff;
        font-size:.8rem;
        font-weight:700;
        cursor:pointer;
        transition:.2s;
    }

    .go-btn:hover{
        background:var(--primary-dark);
        transform:translateY(-1px);
    }

    @media(max-width:992px){ .details-grid{ grid-template-columns:1fr; } }
    @media(max-width:768px){
        .page-header{ flex-direction:column; align-items:flex-start; }
        .card-header-custom{ flex-direction:column; align-items:flex-start; }
        .search-box{ width:100%; }
        .summary-bar{ flex-direction:column; text-align:center; }
        .summary-divider{ display:none; }
        .record-stats{ grid-template-columns:1fr; }
        .pagination-wrapper{ flex-direction:column; align-items:center; }
        .pagination-controls{ order:-1; }
    }
</style>

<div class="container-fluid py-3 payments-dashboard">

    {{-- HEADER --}}
    <div class="page-header">
        <div class="page-title-wrap">
            <div class="title-icon">
                <i class="bi bi-wallet2"></i>
            </div>
            <div>
                <h4 class="page-title">Student Payments Summary</h4>
                <p class="page-subtitle">Manage student invoices, balances and receipts professionally</p>
            </div>
        </div>
        <a href="{{ route('payments.create') }}" class="btn-add-payment">
            <i class="bi bi-plus-lg"></i> Add Payment
        </a>
    </div>

    {{-- SUCCESS ALERT --}}
    @if(session('success'))
        <div class="alert-modern alert-success-modern">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
        </div>
    @endif

    @php
        $groupedPayments = $payments->groupBy('student_id');

        $totalStudents = $groupedPayments->count();
        $totalPaidAll = 0;
        $fullyPaidCount = 0;
        $partialCount = 0;
        $notPaidCount = 0;
        $totalExpected = 0;
        $totalBalance = 0;

        foreach($groupedPayments as $studentId => $studentPayments){
            $allocations = $studentPayments->flatMap->allocations;
            $totalPaid = $allocations->sum('amount');

            $invoices = $studentPayments->pluck('invoice')->filter()->unique('id');
            $studentInvoiceTotal = $invoices->sum('total_amount');

            $balance = max(0, $studentInvoiceTotal - $totalPaid);

            $totalPaidAll += $totalPaid;
            $totalExpected += $studentInvoiceTotal;
            $totalBalance += $balance;

            if($balance <= 0 && $totalPaid > 0){
                $fullyPaidCount++;
            } elseif($totalPaid > 0){
                $partialCount++;
            } else {
                $notPaidCount++;
            }
        }
    @endphp

    {{-- STATS ROW - 3 CARDS --}}
    <div class="stats-grid">
        {{-- Card 1: Total Students --}}
        <div class="stat-card blue">
            <div class="stat-icon blue">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Total Students</div>
                <div class="stat-value">{{ $totalStudents }}</div>
            </div>
            <span class="stat-trend trend-up">
                <i class="bi bi-arrow-up" style="font-size:0.625rem;"></i> Active
            </span>
        </div>

        {{-- Card 2: Total Collected --}}
        <div class="stat-card green">
            <div class="stat-icon green">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Total Collected</div>
                <div class="stat-value">LRD {{ number_format($totalPaidAll, 2) }}</div>
            </div>
            <span class="stat-trend trend-up">
                <i class="bi bi-arrow-up" style="font-size:0.625rem;"></i> Revenue
            </span>
        </div>

        {{-- Card 3: Outstanding Balance --}}
        <div class="stat-card orange">
            <div class="stat-icon orange">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Outstanding Balance</div>
                <div class="stat-value">LRD {{ number_format($totalBalance, 2) }}</div>
            </div>
            <span class="stat-trend trend-down">
                <i class="bi bi-arrow-down" style="font-size:0.625rem;"></i> Due
            </span>
        </div>
    </div>

    {{-- SUMMARY BAR --}}
    <div class="summary-bar">
        <div class="summary-item">
            <small>Expected Revenue</small>
            <strong class="expected">LRD {{ number_format($totalExpected, 2) }}</strong>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-item">
            <small>Total Collected</small>
            <strong class="paid">LRD {{ number_format($totalPaidAll, 2) }}</strong>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-item">
            <small>Outstanding</small>
            <strong class="balance">LRD {{ number_format($totalBalance, 2) }}</strong>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-item">
            <small>Fully Paid</small>
            <strong style="color:var(--success);">{{ $fullyPaidCount }}</strong>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-item">
            <small>Partially Paid</small>
            <strong style="color:var(--warning);">{{ $partialCount }}</strong>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-item">
            <small>Not Paid</small>
            <strong style="color:var(--danger);">{{ $notPaidCount }}</strong>
        </div>
    </div>

    {{-- MAIN CARD --}}
    <div class="main-card">

        <div class="card-header-custom">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="card-header-title">
                    <i class="bi bi-list-check"></i>
                    Payment Records
                </div>
                <span class="header-badge">{{ $groupedPayments->count() }} Students</span>
            </div>
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="studentSearch" placeholder="Search by student name..." onkeyup="filterTable()">
            </div>
        </div>

        {{-- PAYMENTS LIST --}}
        <div class="payments-list" id="paymentsList">

            @forelse($groupedPayments as $studentId => $studentPayments)

                @php
                    $student = optional($studentPayments->first()->student);
                    $studentName = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
                    $initials = strtoupper(substr($student->first_name ?? '', 0, 1)) . strtoupper(substr($student->last_name ?? '', 0, 1));

                    $allocations = $studentPayments->flatMap->allocations;
                    $totalPaid = $allocations->sum('amount');

                    $invoices = $studentPayments->pluck('invoice')->filter()->unique('id');
                    $totalInvoice = $invoices->sum('total_amount');
                    $balance = max(0, $totalInvoice - $totalPaid);

                    $progressPercent = $totalInvoice > 0 ? min(100, ($totalPaid / $totalInvoice) * 100) : 0;

                    $feeBreakdown = [];
                    foreach($allocations as $alloc){
                        $feeName = optional($alloc->invoiceItem->feeCategory)->name ?? 'Other Fee';
                        $feeBreakdown[$feeName] = ($feeBreakdown[$feeName] ?? 0) + $alloc->amount;
                    }

                    $receivedUsers = $studentPayments->pluck('receiver.name')->filter()->unique();

                    if($totalPaid <= 0){
                        $status = 'Not Paid';
                        $statusClass = 'not-paid';
                        $progressClass = 'danger';
                    } elseif($balance > 0){
                        $status = 'Partially Paid';
                        $statusClass = 'partial';
                        $progressClass = 'warning';
                    } else {
                        $status = 'Fully Paid';
                        $statusClass = 'paid';
                        $progressClass = 'success';
                    }
                @endphp

                <div class="payment-record-card" data-student-name="{{ strtolower($studentName) }}">

                    {{-- RECORD TOP --}}
                    <div class="record-top">
                        <div class="student-section">
                            <div class="student-avatar">{{ $initials ?: 'ST' }}</div>
                            <div>
                                <h6 class="student-name">{{ $studentName ?: 'Unknown Student' }}</h6>
                                <div class="student-meta">
                                    {{ $student->student_id ?? 'ID: N/A' }} &bull; {{ $studentPayments->count() }} payment record(s)
                                </div>
                            </div>
                        </div>
                        <div class="status-pill status-{{ $statusClass }}">
                            <span class="status-dot {{ $statusClass }}"></span>
                            {{ $status }}
                        </div>
                    </div>

                    {{-- RECORD BODY --}}
                    <div class="record-body">
                        <div class="record-stats">
                            <div class="mini-stat">
                                <small>Invoice Total</small>
                                <h5 class="amount-invoice">LRD {{ number_format($totalInvoice, 2) }}</h5>
                            </div>
                            <div class="mini-stat">
                                <small>Amount Paid</small>
                                <h5 class="amount-paid">LRD {{ number_format($totalPaid, 2) }}</h5>
                            </div>
                            <div class="mini-stat">
                                <small>Balance</small>
                                <h5 class="amount-balance">LRD {{ number_format($balance, 2) }}</h5>
                            </div>
                            <div class="mini-stat">
                                <small>Progress</small>
                                <div class="progress-bar-bg">
                                    <div class="progress-bar-fill {{ $progressClass }}" style="width:{{ $progressPercent }}%"></div>
                                </div>
                                <div class="progress-text">{{ number_format($progressPercent, 1) }}% completed</div>
                            </div>
                        </div>

                        {{-- VIEW DETAILS TOGGLE --}}
                        <div class="view-details-wrap">
                            <button class="btn-toggle" onclick="toggleDetails('details-{{ $studentId }}')">
                                <i class="bi bi-chevron-down"></i> View Details
                            </button>
                        </div>

                        {{-- DETAILS PANEL --}}
                        <div class="payment-details" id="details-{{ $studentId }}">
                            <div class="details-grid">

                                {{-- LEFT COLUMN --}}
                                <div>
                                    {{-- Fee Breakdown --}}
                                    <div class="record-section">
                                        <div class="section-title">
                                            <i class="bi bi-tags"></i> Fee Breakdown
                                        </div>
                                        <div class="fees-grid">
                                            @forelse($feeBreakdown as $feeName => $amount)
                                                <div class="fee-item">
                                                    <span>{{ $feeName }}</span>
                                                    <strong>LRD {{ number_format($amount, 2) }}</strong>
                                                </div>
                                            @empty
                                                <div class="fee-item">
                                                    <span>No fee allocations found</span>
                                                    <strong>-</strong>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>

                                    {{-- Payment History --}}
                                    <div class="record-section">
                                        <div class="section-title">
                                            <i class="bi bi-clock-history"></i> Payment History
                                        </div>
                                        <div class="fees-grid">
                                            @foreach($studentPayments as $payment)
                                                <div class="fee-item">
                                                    <span>
                                                        @if($payment->payment_date)
                                                            {{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}
                                                        @else
                                                            N/A
                                                        @endif
                                                        @if($payment->payment_method)
                                                            <span class="text-muted" style="font-size:0.7rem;">({{ $payment->payment_method }})</span>
                                                        @endif
                                                    </span>
                                                    <strong>LRD {{ number_format($payment->amount_paid ?? 0, 2) }}</strong>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                {{-- RIGHT COLUMN --}}
                                <div>
                                    {{-- Received By --}}
                                    <div class="record-section">
                                        <div class="section-title">
                                            <i class="bi bi-person-badge"></i> Received By
                                        </div>
                                        <div class="user-tags">
                                            @forelse($receivedUsers as $userName)
                                                <span class="user-tag">{{ $userName }}</span>
                                            @empty
                                                <span class="user-tag">System</span>
                                            @endforelse
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="record-section">
                                        <div class="section-title">
                                            <i class="bi bi-gear"></i> Actions
                                        </div>
                                        <div class="record-actions">
                                            @if($student)
                                                <a href="{{ route('students.show', $student->id) }}" class="btn-action btn-view">
                                                    <i class="bi bi-person"></i> Profile
                                                </a>
                                            @endif
                                            @if($invoices->isNotEmpty())
                                                <a href="{{ route('invoices.show', $invoices->first()->id) }}" class="btn-action btn-view">
                                                    <i class="bi bi-receipt"></i> Invoice
                                                </a>
                                            @endif
                                            {{-- View Receipt for each payment --}}
                                            @foreach($studentPayments as $payment)
                                                <a href="{{ route('payments.receipt', $payment->id) }}" class="btn-action btn-view">
                                                    <i class="bi bi-file-earmark-text"></i> Receipt {{ $payment->receipt_no }}
                                                </a>
                                            @endforeach
                                            <a href="{{ route('payments.create', ['student_id' => $studentId]) }}" class="btn-action btn-edit">
                                                <i class="bi bi-plus-lg"></i> Add Payment
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            @empty
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h5>No Payment Records Found</h5>
                    <p>There are no student payments recorded yet. Click "Add Payment" to get started.</p>
                </div>
            @endforelse

        </div>

        {{-- PAGINATION --}}
        @if(method_exists($payments, 'links') && $payments->hasPages())
            <div class="pagination-container">
                <div class="pagination-wrapper">
                    <div class="pagination-info-text">
                        Showing <strong>{{ $payments->firstItem() }}</strong> to <strong>{{ $payments->lastItem() }}</strong> of <strong>{{ $payments->total() }}</strong> records
                    </div>

                    <div class="pagination-controls">
                        {{-- Previous --}}
                        @if($payments->onFirstPage())
                            <button class="pagination-btn nav-btn" disabled>
                                <i class="bi bi-chevron-left"></i> Prev
                            </button>
                        @else
                            <a href="{{ $payments->previousPageUrl() }}" class="pagination-btn nav-btn">
                                <i class="bi bi-chevron-left"></i> Prev
                            </a>
                        @endif

                        {{-- Page Numbers --}}
                        @foreach($payments->getUrlRange(1, $payments->lastPage()) as $page => $url)
                            @if($page == $payments->currentPage())
                                <button class="pagination-btn active">{{ $page }}</button>
                            @else
                                <a href="{{ $url }}" class="pagination-btn">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if($payments->hasMorePages())
                            <a href="{{ $payments->nextPageUrl() }}" class="pagination-btn nav-btn">
                                Next <i class="bi bi-chevron-right"></i>
                            </a>
                        @else
                            <button class="pagination-btn nav-btn" disabled>
                                Next <i class="bi bi-chevron-right"></i>
                            </button>
                        @endif
                    </div>

                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="items-per-page">
                            <label>Per page:</label>
                            <select onchange="window.location.href=this.value">
                                @foreach([10, 25, 50, 100] as $perPage)
                                    <option value="{{ request()->fullUrlWithQuery(['per_page' => $perPage]) }}" {{ request('per_page', 10) == $perPage ? 'selected' : '' }}>
                                        {{ $perPage }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="go-to-page">
                            <label>Go to:</label>
                            <input type="number" id="goToPage" min="1" max="{{ $payments->lastPage() }}" placeholder="#">
                            <button class="go-btn" onclick="goToPage()">Go</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>

</div>

<script>
    // Toggle details panel
    function toggleDetails(id) {
        const panel = document.getElementById(id);
        const btn = panel.previousElementSibling.querySelector('.btn-toggle');
        const icon = btn.querySelector('i');

        if (panel.classList.contains('active')) {
            panel.classList.remove('active');
            btn.innerHTML = '<i class="bi bi-chevron-down"></i> View Details';
        } else {
            // Close all other panels
            document.querySelectorAll('.payment-details.active').forEach(p => {
                p.classList.remove('active');
                const otherBtn = p.previousElementSibling.querySelector('.btn-toggle');
                if(otherBtn) otherBtn.innerHTML = '<i class="bi bi-chevron-down"></i> View Details';
            });
            panel.classList.add('active');
            btn.innerHTML = '<i class="bi bi-chevron-up"></i> Hide Details';
        }
    }

    // Search filter
    function filterTable() {
        const input = document.getElementById('studentSearch');
        const filter = input.value.toLowerCase();
        const cards = document.querySelectorAll('.payment-record-card');

        cards.forEach(card => {
            const name = card.getAttribute('data-student-name');
            if (name && name.includes(filter)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Go to page
    function goToPage() {
        const input = document.getElementById('goToPage');
        const page = parseInt(input.value);
        const maxPage = parseInt(input.getAttribute('max'));

        if (page && page >= 1 && page <= maxPage) {
            const url = new URL(window.location.href);
            url.searchParams.set('page', page);
            window.location.href = url.toString();
        } else {
            alert('Please enter a valid page number between 1 and ' + maxPage);
        }
    }

    // Allow Enter key for go-to-page
    document.getElementById('goToPage')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') goToPage();
    });
</script>

@endsection