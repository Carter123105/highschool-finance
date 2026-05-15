<?php $__env->startSection('content'); ?>

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

    body{
        background:#f4f7fb;
    }

    .payments-dashboard{
        max-width:1200px;
        margin:auto;
    }

    /* =====================================================
        HEADER
    ===================================================== */

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

    .page-title-wrap{
        display:flex;
        align-items:center;
        gap:.8rem;
    }

    .title-icon{
        width:48px;
        height:48px;
        border-radius:14px;

        background:var(--primary-light);
        color:var(--primary);

        display:flex;
        align-items:center;
        justify-content:center;

        font-size:1.2rem;
    }

    .page-title{
        margin:0;
        font-size:1.3rem;
        font-weight:800;
        color:var(--gray-900);
    }

    .page-subtitle{
        margin:0;
        margin-top:.2rem;
        color:var(--gray-500);
        font-size:.82rem;
    }

    .btn-add-payment{
        display:inline-flex;
        align-items:center;
        gap:.5rem;

        background:linear-gradient(135deg,var(--primary),#6366f1);
        color:#fff;
        text-decoration:none;

        padding:.8rem 1.2rem;
        border-radius:12px;

        font-weight:700;
        font-size:.88rem;
        transition:.25s ease;

        box-shadow:var(--shadow-sm);
    }

    .btn-add-payment:hover{
        transform:translateY(-2px);
        color:#fff;
    }

    /* =====================================================
        ALERT
    ===================================================== */

    .alert-modern{
        border:none;
        border-radius:12px;
        padding:.85rem 1.1rem;
        margin-bottom:1.2rem;

        display:flex;
        align-items:center;
        gap:.6rem;

        font-weight:700;
        font-size:.9rem;
    }

    .alert-success-modern{
        background:var(--success-light);
        color:var(--success);
        border-left:4px solid var(--success);
    }

    /* =====================================================
        STATS
    ===================================================== */

    .stats-grid{
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
        gap:.8rem;
        margin-bottom:1.2rem;
    }

    .stat-card{
        background:#fff;
        border-radius:16px;
        padding:1rem;

        border:1px solid var(--gray-100);
        box-shadow:var(--shadow-sm);

        display:flex;
        align-items:center;
        gap:.8rem;

        transition:.25s ease;
    }

    .stat-card:hover{
        transform:translateY(-2px);
        box-shadow:var(--shadow);
    }

    .stat-icon{
        width:44px;
        height:44px;
        border-radius:12px;

        display:flex;
        align-items:center;
        justify-content:center;

        font-size:1.1rem;
    }

    .stat-icon.blue{
        background:var(--primary-light);
        color:var(--primary);
    }

    .stat-icon.green{
        background:var(--success-light);
        color:var(--success);
    }

    .stat-icon.orange{
        background:var(--warning-light);
        color:var(--warning);
    }

    .stat-icon.red{
        background:var(--danger-light);
        color:var(--danger);
    }

    .stat-label{
        font-size:.7rem;
        font-weight:700;
        color:var(--gray-500);
        text-transform:uppercase;
        margin-bottom:.15rem;
    }

    .stat-value{
        font-size:1.25rem;
        font-weight:800;
        color:var(--gray-900);
    }

    /* =====================================================
        MAIN CARD
    ===================================================== */

    .main-card{
        background:#fff;
        border-radius:20px;
        overflow:hidden;

        border:1px solid var(--gray-100);
        box-shadow:var(--shadow);
    }

    .card-header-custom{
        padding:1rem 1.3rem;

        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:1rem;
        flex-wrap:wrap;

        border-bottom:1px solid var(--gray-100);
    }

    .card-header-title{
        display:flex;
        align-items:center;
        gap:.5rem;

        font-size:1rem;
        font-weight:800;
        color:var(--gray-800);
    }

    .header-badge{
        background:var(--primary-light);
        color:var(--primary);

        padding:.3rem .7rem;
        border-radius:999px;

        font-size:.72rem;
        font-weight:700;
    }

    /* =====================================================
        SEARCH
    ===================================================== */

    .search-box{
        position:relative;
        width:280px;
        max-width:100%;
    }

    .search-box i{
        position:absolute;
        left:.85rem;
        top:50%;
        transform:translateY(-50%);
        color:var(--gray-400);
        font-size:.85rem;
    }

    .search-box input{
        width:100%;
        background:var(--gray-50);

        border:1px solid var(--gray-200);
        border-radius:12px;

        padding:.7rem .9rem .7rem 2.4rem;

        font-size:.85rem;
        transition:.2s;
    }

    .search-box input:focus{
        outline:none;
        border-color:var(--primary);
        background:#fff;
        box-shadow:0 0 0 3px var(--primary-light);
    }

    /* =====================================================
        PAYMENTS LIST
    ===================================================== */

    .payments-list{
        padding:1.2rem;
        display:flex;
        flex-direction:column;
        gap:.9rem;
    }

    .payment-record-card{
        border:1px solid var(--gray-100);
        border-radius:16px;
        background:#fff;
        overflow:hidden;

        box-shadow:var(--shadow-sm);
        transition:.25s ease;
    }

    .payment-record-card:hover{
        transform:translateY(-1px);
        box-shadow:var(--shadow);
    }

    /* =====================================================
        TOP SECTION
    ===================================================== */

    .record-top{
        padding:1rem 1.2rem;

        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:1rem;
        flex-wrap:wrap;

        border-bottom:1px solid var(--gray-100);
        background:linear-gradient(to right,#ffffff,#fafbff);
    }

    .student-section{
        display:flex;
        align-items:center;
        gap:.8rem;
    }

    .student-avatar{
        width:42px;
        height:42px;
        border-radius:50%;

        background:linear-gradient(135deg,var(--primary),#6366f1);

        color:#fff;
        font-weight:800;
        font-size:.85rem;

        display:flex;
        align-items:center;
        justify-content:center;
    }

    .student-name{
        margin:0;
        font-size:.95rem;
        font-weight:800;
        color:var(--gray-900);
    }

    .student-meta{
        margin-top:.1rem;
        color:var(--gray-500);
        font-size:.75rem;
    }

    /* =====================================================
        STATUS
    ===================================================== */

    .status-pill{
        display:inline-flex;
        align-items:center;
        gap:.35rem;

        padding:.45rem .75rem;
        border-radius:999px;

        font-size:.72rem;
        font-weight:800;
    }

    .status-paid{
        background:var(--success-light);
        color:var(--success);
    }

    .status-partial{
        background:var(--warning-light);
        color:var(--warning);
    }

    .status-not-paid{
        background:var(--gray-100);
        color:var(--gray-600);
    }

    .status-dot{
        width:6px;
        height:6px;
        border-radius:50%;
    }

    .status-dot.paid{
        background:var(--success);
    }

    .status-dot.partial{
        background:var(--warning);
    }

    .status-dot.not-paid{
        background:var(--gray-500);
    }

    /* =====================================================
        BODY
    ===================================================== */

    .record-body{
        padding:1rem 1.2rem;
    }

    /* =====================================================
        QUICK STATS
    ===================================================== */

    .record-stats{
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(160px,1fr));
        gap:.7rem;
    }

    .mini-stat{
        background:var(--gray-50);
        border:1px solid var(--gray-100);
        border-radius:12px;

        padding:.75rem;
    }

    .mini-stat small{
        display:block;
        font-size:.68rem;
        font-weight:700;
        color:var(--gray-500);
        margin-bottom:.3rem;
        text-transform:uppercase;
    }

    .mini-stat h5{
        margin:0;
        font-size:1rem;
        font-weight:800;
    }

    .amount-invoice{
        color:var(--gray-800);
    }

    .amount-paid{
        color:var(--success);
    }

    .amount-balance{
        color:var(--danger);
    }

    /* =====================================================
        PROGRESS
    ===================================================== */

    .progress-bar-bg{
        width:100%;
        height:6px;

        background:var(--gray-200);
        border-radius:999px;
        overflow:hidden;
    }

    .progress-bar-fill{
        height:100%;
        border-radius:999px;
    }

    .progress-bar-fill.success{
        background:var(--success);
    }

    .progress-bar-fill.warning{
        background:var(--warning);
    }

    .progress-bar-fill.danger{
        background:var(--danger);
    }

    .progress-text{
        margin-top:.3rem;
        font-size:.7rem;
        font-weight:700;
        color:var(--gray-500);
    }

    /* =====================================================
        VIEW BUTTON
    ===================================================== */

    .view-details-wrap{
        margin-top:1rem;
    }

    .btn-toggle{
        width:100%;

        border:none;
        border-radius:12px;

        background:var(--primary-light);
        color:var(--primary);

        padding:.75rem 1rem;

        font-weight:800;
        font-size:.82rem;

        display:flex;
        justify-content:center;
        align-items:center;
        gap:.5rem;

        transition:.2s ease;
    }

    .btn-toggle:hover{
        background:var(--primary);
        color:#fff;
    }

    /* =====================================================
        DETAILS PANEL
    ===================================================== */

    .payment-details{
        display:none;
        margin-top:1rem;

        background:#fafcff;
        border:1px solid var(--gray-100);
        border-radius:14px;

        padding:1rem;
    }

    .payment-details.active{
        display:block;
    }

    .details-grid{
        display:grid;
        grid-template-columns:1fr 260px;
        gap:1rem;
    }

    /* =====================================================
        SECTIONS
    ===================================================== */

    .record-section{
        margin-bottom:1rem;
    }

    .record-section:last-child{
        margin-bottom:0;
    }

    .section-title{
        display:flex;
        align-items:center;
        gap:.4rem;

        margin-bottom:.7rem;

        font-size:.78rem;
        font-weight:800;
        color:var(--gray-700);
    }

    /* =====================================================
        FEES
    ===================================================== */

    .fees-grid{
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
        gap:.6rem;
    }

    .fee-item{
        background:#fff;
        border:1px solid var(--gray-100);

        border-radius:12px;
        padding:.7rem .9rem;

        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:.8rem;
    }

    .fee-item span{
        font-size:.78rem;
        font-weight:700;
        color:var(--gray-700);
    }

    .fee-item strong{
        color:var(--success);
        font-size:.8rem;
    }

    /* =====================================================
        RECEIVED BY
    ===================================================== */

    .user-tags{
        display:flex;
        flex-wrap:wrap;
        gap:.4rem;
    }

    .user-tag{
        background:var(--gray-100);
        color:var(--gray-700);

        padding:.4rem .7rem;
        border-radius:999px;

        font-size:.72rem;
        font-weight:700;
    }

    /* =====================================================
        ACTIONS
    ===================================================== */

    .record-actions{
        display:flex;
        flex-wrap:wrap;
        gap:.5rem;
    }

    .record-actions form{
        margin:0;
    }

    .btn-action{
        border:none;
        border-radius:10px;

        padding:.6rem .85rem;

        display:inline-flex;
        align-items:center;
        gap:.35rem;

        font-size:.72rem;
        font-weight:700;

        text-decoration:none;
        transition:.2s;
        cursor:pointer;
    }

    .btn-view{
        background:var(--info-light);
        color:var(--info);
    }

    .btn-view:hover{
        background:var(--info);
        color:#fff;
    }

    .btn-edit{
        background:var(--warning-light);
        color:var(--warning);
    }

    .btn-edit:hover{
        background:var(--warning);
        color:#fff;
    }

    .btn-delete{
        background:var(--danger-light);
        color:var(--danger);
    }

    .btn-delete:hover{
        background:var(--danger);
        color:#fff;
    }

    /* =====================================================
        EMPTY
    ===================================================== */

    .empty-state{
        text-align:center;
        padding:3rem 1rem;
    }

    .empty-state i{
        font-size:2.5rem;
        color:var(--gray-300);
        margin-bottom:.8rem;
        display:block;
    }

    .empty-state h5{
        font-weight:800;
        color:var(--gray-700);
        font-size:1.1rem;
    }

    .empty-state p{
        color:var(--gray-500);
        font-size:.85rem;
    }

    /* =====================================================
        PAGINATION
    ===================================================== */

    .pagination-wrap{
        padding:1.2rem;
        border-top:1px solid var(--gray-100);
        display:flex;
        flex-direction:column;
        align-items:center;
        gap:.5rem;
    }

    .pagination-modern{
        display:flex;
        align-items:center;
        gap:.3rem;
        flex-wrap:wrap;
        justify-content:center;
    }

    .pagination-modern a,
    .pagination-modern span{
        display:inline-flex;
        align-items:center;
        justify-content:center;

        min-width:36px;
        height:36px;

        padding:.4rem .7rem;
        border-radius:10px;

        font-size:.8rem;
        font-weight:700;
        text-decoration:none;

        transition:.2s ease;
    }

    .pagination-modern a{
        background:#fff;
        color:var(--gray-600);
        border:1px solid var(--gray-200);
    }

    .pagination-modern a:hover{
        background:var(--primary-light);
        color:var(--primary);
        border-color:var(--primary-light);
        transform:translateY(-1px);
    }

    .pagination-modern .active span{
        background:linear-gradient(135deg,var(--primary),#6366f1);
        color:#fff;
        border:1px solid var(--primary);
        box-shadow:var(--shadow-sm);
    }

    .pagination-modern .disabled span{
        background:var(--gray-50);
        color:var(--gray-300);
        border:1px solid var(--gray-100);
        cursor:not-allowed;
    }

    .pagination-info{
        font-size:.75rem;
        color:var(--gray-500);
        font-weight:600;
    }

    /* =====================================================
        MOBILE
    ===================================================== */

    @media(max-width:992px){

        .details-grid{
            grid-template-columns:1fr;
        }
    }

    @media(max-width:768px){

        .page-header{
            flex-direction:column;
            align-items:flex-start;
        }

        .card-header-custom{
            flex-direction:column;
            align-items:flex-start;
        }

        .search-box{
            width:100%;
        }

        .stats-grid{
            grid-template-columns:repeat(2,1fr);
        }

        .record-stats{
            grid-template-columns:repeat(2,1fr);
        }

        .fees-grid{
            grid-template-columns:1fr;
        }

        .record-actions{
            flex-direction:column;
        }

        .btn-action{
            justify-content:center;
        }

        .pagination-modern a,
        .pagination-modern span{
            min-width:32px;
            height:32px;
            font-size:.75rem;
        }
    }

    @media(max-width:480px){
        .stats-grid{
            grid-template-columns:1fr;
        }
        .record-stats{
            grid-template-columns:1fr;
        }
    }

</style>

<div class="container-fluid py-3 payments-dashboard">

    
    <div class="page-header">

        <div class="page-title-wrap">

            <div class="title-icon">
                <i class="bi bi-wallet2"></i>
            </div>

            <div>

                <h4 class="page-title">
                    Student Payments Summary
                </h4>

                <p class="page-subtitle">
                    Manage student invoices, balances and receipts professionally
                </p>

            </div>

        </div>

        <a href="<?php echo e(route('payments.create')); ?>"
           class="btn-add-payment">

            <i class="bi bi-plus-lg"></i>
            Add Payment

        </a>

    </div>

    
    <?php if(session('success')): ?>

        <div class="alert-modern alert-success-modern">

            <i class="bi bi-check-circle-fill"></i>

            <?php echo e(session('success')); ?>


        </div>

    <?php endif; ?>

    <?php

        $groupedPayments = $payments->groupBy('student_id');

        $totalStudents = $groupedPayments->count();

        $totalPaidAll = 0;

        $partialCount = 0;

        $notPaidCount = 0;

        foreach($groupedPayments as $studentPayments){

            $allocations = $studentPayments->flatMap->allocations;

            $totalPaid = $allocations->sum('amount');

            $invoices = $studentPayments
                        ->pluck('invoice')
                        ->filter()
                        ->unique('id');

            $totalInvoice = $invoices->sum('total_amount');

            $balance = max(0, $totalInvoice - $totalPaid);

            $totalPaidAll += $totalPaid;

            if($totalPaid <= 0){

                $notPaidCount++;

            }elseif($balance > 0){

                $partialCount++;
            }
        }

    ?>

    
    <div class="stats-grid">

        <div class="stat-card">

            <div class="stat-icon blue">
                <i class="bi bi-people-fill"></i>
            </div>

            <div>

                <div class="stat-label">
                    Total Students
                </div>

                <div class="stat-value">
                    <?php echo e($totalStudents); ?>

                </div>

            </div>

        </div>

        <div class="stat-card">

            <div class="stat-icon green">
                <i class="bi bi-cash-stack"></i>
            </div>

            <div>

                <div class="stat-label">
                    Total Collected
                </div>

                <div class="stat-value">
                    <?php echo e(number_format($totalPaidAll,2)); ?>

                </div>

            </div>

        </div>

        <div class="stat-card">

            <div class="stat-icon orange">
                <i class="bi bi-hourglass-split"></i>
            </div>

            <div>

                <div class="stat-label">
                    Partial Payments
                </div>

                <div class="stat-value">
                    <?php echo e($partialCount); ?>

                </div>

            </div>

        </div>

        <div class="stat-card">

            <div class="stat-icon red">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>

            <div>

                <div class="stat-label">
                    Not Paid
                </div>

                <div class="stat-value">
                    <?php echo e($notPaidCount); ?>

                </div>

            </div>

        </div>

    </div>

    
    <div class="main-card">

        <div class="card-header-custom">

            <div class="d-flex align-items-center gap-2 flex-wrap">

                <div class="card-header-title">

                    <i class="bi bi-list-check"></i>

                    Payment Records

                </div>

                <span class="header-badge">
                    <?php echo e($groupedPayments->count()); ?> Students
                </span>

            </div>

            <div class="search-box">

                <i class="bi bi-search"></i>

                <input type="text"
                       id="studentSearch"
                       placeholder="Search by student name..."
                       onkeyup="filterTable()">

            </div>

        </div>

        
        <div class="payments-list" id="paymentsList">

            <?php $__empty_1 = true; $__currentLoopData = $groupedPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $studentId => $studentPayments): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                <?php

                    $student = optional(
                        $studentPayments->first()->student
                    );

                    $studentName = trim(
                        ($student->first_name ?? '') . ' ' .
                        ($student->last_name ?? '')
                    );

                    $initials =
                        strtoupper(substr($student->first_name ?? '',0,1)) .
                        strtoupper(substr($student->last_name ?? '',0,1));

                    $allocations = $studentPayments->flatMap->allocations;

                    $totalPaid = $allocations->sum('amount');

                    $invoices = $studentPayments
                                ->pluck('invoice')
                                ->filter()
                                ->unique('id');

                    $totalInvoice = $invoices->sum('total_amount');

                    $balance = max(0, $totalInvoice - $totalPaid);

                    $progressPercent = $totalInvoice > 0
                        ? min(100, ($totalPaid / $totalInvoice) * 100)
                        : 0;

                    $feeBreakdown = [];

                    foreach($allocations as $alloc){

                        $feeName = optional(
                            $alloc->invoiceItem->feeCategory
                        )->name ?? 'Other Fee';

                        $feeBreakdown[$feeName] =
                            ($feeBreakdown[$feeName] ?? 0)
                            + $alloc->amount;
                    }

                    $receivedUsers = $studentPayments
                                    ->pluck('receiver.name')
                                    ->filter()
                                    ->unique();

                    if($totalPaid <= 0){

                        $status = 'Not Paid';
                        $statusClass = 'not-paid';
                        $progressClass = 'danger';

                    }elseif($balance > 0){

                        $status = 'Partially Paid';
                        $statusClass = 'partial';
                        $progressClass = 'warning';

                    }else{

                        $status = 'Fully Paid';
                        $statusClass = 'paid';
                        $progressClass = 'success';
                    }

                    $latestPayment = $studentPayments
                                    ->sortByDesc('created_at')
                                    ->first();

                ?>

                <div class="payment-record-card"
                     data-student-name="<?php echo e(strtolower($studentName)); ?>">

                    
                    <div class="record-top">

                        <div class="student-section">

                            <div class="student-avatar">
                                <?php echo e($initials ?: 'ST'); ?>

                            </div>

                            <div>

                                <h5 class="student-name">
                                    <?php echo e($studentName ?: 'Unknown Student'); ?>

                                </h5>

                                <div class="student-meta">
                                    <?php echo e($studentPayments->count()); ?> payment(s)
                                </div>

                            </div>

                        </div>

                        <span class="status-pill status-<?php echo e($statusClass); ?>">

                            <span class="status-dot <?php echo e($statusClass); ?>"></span>

                            <?php echo e($status); ?>


                        </span>

                    </div>

                    
                    <div class="record-body">

                        
                        <div class="record-stats">

                            <div class="mini-stat">

                                <small>Total Invoice</small>

                                <h5 class="amount-invoice">
                                    <?php echo e(number_format($totalInvoice,2)); ?>

                                </h5>

                            </div>

                            <div class="mini-stat">

                                <small>Total Paid</small>

                                <h5 class="amount-paid">
                                    <?php echo e(number_format($totalPaid,2)); ?>

                                </h5>

                            </div>

                            <div class="mini-stat">

                                <small>Balance</small>

                                <h5 class="amount-balance">
                                    <?php echo e(number_format($balance,2)); ?>

                                </h5>

                            </div>

                            <div class="mini-stat">

                                <small>Progress</small>

                                <div class="progress-bar-bg mt-2">

                                    <div class="progress-bar-fill <?php echo e($progressClass); ?>"
                                         style="width: <?php echo e($progressPercent); ?>%;">
                                    </div>

                                </div>

                                <div class="progress-text">
                                    <?php echo e(number_format($progressPercent,1)); ?>%
                                </div>

                            </div>

                        </div>

                        
                        <div class="view-details-wrap">

                            <button class="btn-toggle"
                                    onclick="toggleDetails('details-<?php echo e($studentId); ?>')">

                                <i class="bi bi-eye-fill"></i>

                                View Payment Details

                            </button>

                        </div>

                        
                        <div class="payment-details"
                             id="details-<?php echo e($studentId); ?>">

                            <div class="details-grid">

                                
                                <div>

                                    
                                    <div class="record-section">

                                        <div class="section-title">

                                            <i class="bi bi-receipt"></i>

                                            Fee Breakdown

                                        </div>

                                        <div class="fees-grid">

                                            <?php $__currentLoopData = $feeBreakdown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feeName => $amount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                <div class="fee-item">

                                                    <span>
                                                        <?php echo e($feeName); ?>

                                                    </span>

                                                    <strong>
                                                        <?php echo e(number_format($amount,2)); ?>

                                                    </strong>

                                                </div>

                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        </div>

                                    </div>

                                </div>

                                
                                <div>

                                    
                                    <div class="record-section">

                                        <div class="section-title">

                                            <i class="bi bi-person-check"></i>

                                            Received By

                                        </div>

                                        <div class="user-tags">

                                            <?php $__currentLoopData = $receivedUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                <span class="user-tag">
                                                    <?php echo e($user); ?>

                                                </span>

                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        </div>

                                    </div>

                                    
                                    <?php if($latestPayment): ?>

                                        <div class="record-section">

                                            <div class="section-title">

                                                <i class="bi bi-gear"></i>

                                                Actions

                                            </div>

                                            <div class="record-actions">

                                                <a href="<?php echo e(route('payments.receipt', $latestPayment->id)); ?>"
                                                   class="btn-action btn-view">

                                                    <i class="bi bi-eye"></i>
                                                    View

                                                </a>

                                                <a href="<?php echo e(route('payments.receipt', $latestPayment->id)); ?>"
                                                   target="_blank"
                                                   class="btn-action btn-view"
                                                   onclick="printReceipt(this.href); return false;">

                                                    <i class="bi bi-printer"></i>
                                                    Print

                                                </a>

                                                <a href="<?php echo e(route('payments.edit', $latestPayment->id)); ?>"
                                                   class="btn-action btn-edit">

                                                    <i class="bi bi-pencil"></i>
                                                    Edit

                                                </a>

                                                <form action="<?php echo e(route('payments.destroy', $latestPayment->id)); ?>"
                                                      method="POST">

                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>

                                                    <button type="submit"
                                                            class="btn-action btn-delete"
                                                            onclick="return confirm('Delete this payment?')">

                                                        <i class="bi bi-trash"></i>
                                                        Delete

                                                    </button>

                                                </form>

                                            </div>

                                        </div>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                <div class="empty-state">

                    <i class="bi bi-inbox"></i>

                    <h5>No Payments Found</h5>

                    <p>
                        There are no payment records available.
                    </p>

                </div>

            <?php endif; ?>

        </div>

        
        <div class="pagination-wrap">
            <div class="pagination-modern" id="paginationControls">
                
            </div>
            <div class="pagination-info" id="paginationInfo">
                
            </div>
        </div>

    </div>

</div>

<script>

    // Pagination Configuration
    const ITEMS_PER_PAGE = 6;
    let currentPage = 1;
    let filteredCards = [];

    function initPagination(){
        const allCards = document.querySelectorAll('.payment-record-card');
        filteredCards = Array.from(allCards);
        renderPage(1);
    }

    function renderPage(page){
        currentPage = page;
        const totalItems = filteredCards.length;
        const totalPages = Math.ceil(totalItems / ITEMS_PER_PAGE);

        // Hide all cards first
        filteredCards.forEach(card => card.style.display = 'none');

        // Show cards for current page
        const start = (page - 1) * ITEMS_PER_PAGE;
        const end = start + ITEMS_PER_PAGE;
        const pageCards = filteredCards.slice(start, end);
        pageCards.forEach(card => card.style.display = '');

        // Generate pagination controls
        generatePaginationControls(totalPages, totalItems);
    }

    function generatePaginationControls(totalPages, totalItems){
        const container = document.getElementById('paginationControls');
        const info = document.getElementById('paginationInfo');

        if(totalPages <= 1){
            container.innerHTML = '';
            info.innerHTML = totalItems > 0 ? `Showing all ${totalItems} students` : '';
            return;
        }

        let html = '';

        // Previous button
        if(currentPage > 1){
            html += `<a href="javascript:void(0)" onclick="renderPage(${currentPage - 1})"><i class="bi bi-chevron-left"></i></a>`;
        } else {
            html += `<span class="disabled"><i class="bi bi-chevron-left"></i></span>`;
        }

        // Page numbers with ellipsis
        const maxVisible = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
        let endPage = Math.min(totalPages, startPage + maxVisible - 1);

        if(endPage - startPage < maxVisible - 1){
            startPage = Math.max(1, endPage - maxVisible + 1);
        }

        if(startPage > 1){
            html += `<a href="javascript:void(0)" onclick="renderPage(1)">1</a>`;
            if(startPage > 2) html += `<span class="disabled">...</span>`;
        }

        for(let i = startPage; i <= endPage; i++){
            if(i === currentPage){
                html += `<span class="active">${i}</span>`;
            } else {
                html += `<a href="javascript:void(0)" onclick="renderPage(${i})">${i}</a>`;
            }
        }

        if(endPage < totalPages){
            if(endPage < totalPages - 1) html += `<span class="disabled">...</span>`;
            html += `<a href="javascript:void(0)" onclick="renderPage(${totalPages})">${totalPages}</a>`;
        }

        // Next button
        if(currentPage < totalPages){
            html += `<a href="javascript:void(0)" onclick="renderPage(${currentPage + 1})"><i class="bi bi-chevron-right"></i></a>`;
        } else {
            html += `<span class="disabled"><i class="bi bi-chevron-right"></i></span>`;
        }

        container.innerHTML = html;

        // Info text
        const startItem = (currentPage - 1) * ITEMS_PER_PAGE + 1;
        const endItem = Math.min(currentPage * ITEMS_PER_PAGE, totalItems);
        info.innerHTML = `Showing ${startItem} to ${endItem} of ${totalItems} students`;
    }

    function filterTable(){
        const input = document.getElementById('studentSearch');
        const filter = input.value.toLowerCase();
        const allCards = document.querySelectorAll('.payment-record-card');

        filteredCards = [];
        allCards.forEach(card => {
            const studentName = card.getAttribute('data-student-name');
            if(studentName.includes(filter)){
                filteredCards.push(card);
            }
        });

        renderPage(1);
    }

    function toggleDetails(id){
        const element = document.getElementById(id);
        element.classList.toggle('active');
    }

    function printReceipt(url){
        let printWindow = window.open(url, '_blank');
        printWindow.onload = function(){
            printWindow.print();
        };
    }

    // Initialize pagination on load
    document.addEventListener('DOMContentLoaded', initPagination);

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/payments/index.blade.php ENDPATH**/ ?>