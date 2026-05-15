<?php $__env->startSection('content'); ?>

<style>
    :root {
        --primary: #4f46e5;
        --primary-light: #e0e7ff;
        --primary-dark: #4338ca;
        --success: #059669;
        --success-light: #d1fae5;
        --warning: #d97706;
        --warning-light: #fef3c7;
        --danger: #dc2626;
        --danger-light: #fee2e2;
        --info: #0891b2;
        --info-light: #cffafe;
        --dark: #0f172a;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-300: #cbd5e1;
        --gray-400: #94a3b8;
        --gray-500: #64748b;
        --gray-600: #475569;
        --gray-700: #334155;
        --gray-800: #1e293b;
        --shadow-xs: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-md: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        --radius-sm: 6px;
        --radius: 10px;
        --radius-lg: 16px;
    }

    .invoice-page {
        background: var(--gray-50);
        min-height: 100vh;
        padding-bottom: 3rem;
    }

    /* Header */
    .page-header {
        background: linear-gradient(135deg, var(--dark) 0%, #1e293b 100%);
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -5%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(79, 70, 229, 0.2) 0%, transparent 70%);
        border-radius: 50%;
    }

    .page-header h1 {
        color: #fff;
        font-size: 1.75rem;
        font-weight: 800;
        margin-bottom: 0.25rem;
        position: relative;
        letter-spacing: -0.025em;
    }

    .page-header p {
        color: var(--gray-400);
        font-size: 0.9375rem;
        margin: 0;
        position: relative;
    }

    .btn-modern {
        padding: 0.625rem 1.25rem;
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .btn-modern-primary {
        background: var(--primary);
        color: #fff;
        box-shadow: 0 4px 14px rgba(79, 70, 229, 0.4);
    }

    .btn-modern-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(79, 70, 229, 0.5);
        color: #fff;
    }

    .btn-modern-outline {
        background: rgba(255,255,255,0.1);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
    }

    .btn-modern-outline:hover {
        background: rgba(255,255,255,0.2);
        color: #fff;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 1200px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 576px) { .stats-grid { grid-template-columns: 1fr; } }

    .stat-card {
        background: #fff;
        border-radius: var(--radius);
        padding: 1.25rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-100);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary);
    }

    .stat-card.success::before { background: var(--success); }
    .stat-card.info::before { background: var(--info); }
    .stat-card.danger::before { background: var(--danger); }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.125rem;
    }

    .stat-icon.primary { background: var(--primary-light); color: var(--primary); }
    .stat-icon.success { background: var(--success-light); color: var(--success); }
    .stat-icon.info { background: var(--info-light); color: var(--info); }
    .stat-icon.danger { background: var(--danger-light); color: var(--danger); }

    .stat-trend {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.125rem 0.5rem;
        border-radius: 9999px;
    }

    .stat-trend.up { background: var(--success-light); color: var(--success); }
    .stat-trend.down { background: var(--danger-light); color: var(--danger); }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--gray-800);
        line-height: 1.2;
        letter-spacing: -0.025em;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.8125rem;
        color: var(--gray-500);
        font-weight: 500;
    }

    /* Toolbar */
    .toolbar {
        background: #fff;
        border-radius: var(--radius);
        padding: 1rem 1.25rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-100);
        margin-bottom: 1rem;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
        justify-content: space-between;
    }

    .search-box {
        position: relative;
        min-width: 280px;
        flex: 1;
        max-width: 400px;
    }

    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-400);
        font-size: 0.875rem;
    }

    .search-box input {
        width: 100%;
        padding: 0.625rem 1rem 0.625rem 2.5rem;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-sm);
        font-size: 0.875rem;
        transition: all 0.2s;
        background: var(--gray-50);
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        background: #fff;
    }

    .filter-group {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .filter-select {
        padding: 0.625rem 2rem 0.625rem 1rem;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-sm);
        font-size: 0.875rem;
        background: var(--gray-50);
        color: var(--gray-700);
        cursor: pointer;
        min-width: 140px;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .results-badge {
        background: var(--gray-800);
        color: #fff;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.375rem 0.875rem;
        border-radius: 9999px;
        white-space: nowrap;
    }

    /* Table Container */
    .table-container {
        background: #fff;
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-100);
        overflow: hidden;
    }

    /* Modern Table */
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.875rem;
    }

    .modern-table thead th {
        background: var(--gray-50);
        color: var(--gray-500);
        font-size: 0.6875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.875rem 1.25rem;
        border-bottom: 1px solid var(--gray-200);
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .modern-table thead th.sortable {
        cursor: pointer;
        user-select: none;
    }

    .modern-table thead th.sortable:hover {
        color: var(--gray-700);
    }

    .modern-table tbody tr {
        transition: all 0.15s ease;
    }

    .modern-table tbody tr:hover {
        background: rgba(79, 70, 229, 0.02);
    }

    .modern-table tbody td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--gray-100);
        color: var(--gray-700);
        vertical-align: middle;
    }

    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Cell Content */
    .cell-primary {
        font-weight: 700;
        color: var(--gray-800);
        font-size: 0.9375rem;
    }

    .cell-secondary {
        font-size: 0.75rem;
        color: var(--gray-400);
        margin-top: 0.125rem;
    }

    .student-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .student-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    .amount {
        font-weight: 700;
        font-variant-numeric: tabular-nums;
        font-size: 0.9375rem;
    }

    .amount-total { color: var(--gray-800); }
    .amount-paid { color: var(--success); }
    .amount-balance { color: var(--danger); }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.875rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.025em;
        white-space: nowrap;
    }

    .status-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-paid { background: var(--success-light); color: #065f46; }
    .status-paid::before { background: var(--success); }

    .status-partial { background: var(--warning-light); color: #92400e; }
    .status-partial::before { background: var(--warning); }

    .status-unpaid { background: var(--danger-light); color: #991b1b; }
    .status-unpaid::before { background: var(--danger); }

    /* Student Type Badge */
    .type-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.625rem;
        border-radius: var(--radius-sm);
        font-size: 0.6875rem;
        font-weight: 700;
        letter-spacing: 0.025em;
        white-space: nowrap;
    }

    .type-new { background: var(--success-light); color: var(--success); }
    .type-old { background: var(--warning-light); color: var(--warning); }

    /* Fee Tags */
    .fee-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.375rem;
        max-width: 220px;
    }

    .fee-tag {
        background: var(--primary-light);
        color: var(--primary-dark);
        font-size: 0.6875rem;
        font-weight: 600;
        padding: 0.25rem 0.625rem;
        border-radius: var(--radius-sm);
        white-space: nowrap;
    }

    .fee-more {
        background: var(--gray-200);
        color: var(--gray-600);
        font-size: 0.6875rem;
        font-weight: 700;
        padding: 0.25rem 0.5rem;
        border-radius: var(--radius-sm);
    }

    /* Actions */
    .action-group {
        display: flex;
        gap: 0.375rem;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--gray-200);
        background: #fff;
        color: var(--gray-500);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        transition: all 0.2s;
        cursor: pointer;
    }

    .btn-action:hover {
        background: var(--gray-50);
        border-color: var(--gray-300);
        color: var(--gray-700);
    }

    .btn-action.view:hover { color: var(--info); border-color: var(--info); background: var(--info-light); }
    .btn-action.edit:hover { color: var(--warning); border-color: var(--warning); background: var(--warning-light); }
    .btn-action.delete:hover { color: var(--danger); border-color: var(--danger); background: var(--danger-light); }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: var(--gray-100);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: var(--gray-400);
        margin-bottom: 1.5rem;
    }

    .empty-state h4 {
        color: var(--gray-700);
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--gray-500);
        font-size: 0.9375rem;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.25rem;
        border-top: 1px solid var(--gray-100);
        background: var(--gray-50);
    }

    .pagination-info {
        font-size: 0.875rem;
        color: var(--gray-500);
    }

    .pagination-info strong {
        color: var(--gray-800);
    }

    /* Bulk Actions Bar */
    .bulk-bar {
        display: none;
        background: var(--primary);
        color: #fff;
        padding: 0.75rem 1.25rem;
        border-radius: var(--radius-sm);
        margin-bottom: 1rem;
        align-items: center;
        justify-content: space-between;
        animation: slideDown 0.3s ease;
    }

    .bulk-bar.active {
        display: flex;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Checkbox */
    .custom-checkbox {
        width: 18px;
        height: 18px;
        border: 2px solid var(--gray-300);
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
        appearance: none;
        position: relative;
    }

    .custom-checkbox:checked {
        background: var(--primary);
        border-color: var(--primary);
    }

    .custom-checkbox:checked::after {
        content: '';
        position: absolute;
        left: 4px;
        top: 1px;
        width: 5px;
        height: 9px;
        border: solid #fff;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .toolbar {
            flex-direction: column;
            align-items: stretch;
        }
        .search-box {
            max-width: 100%;
            min-width: auto;
        }
        .filter-group {
            justify-content: stretch;
        }
        .filter-group .filter-select {
            flex: 1;
        }
        .modern-table {
            font-size: 0.8125rem;
        }
        .modern-table tbody td {
            padding: 0.875rem 1rem;
        }
        .action-group {
            flex-direction: column;
            gap: 0.25rem;
        }
    }
</style>

<div class="container-fluid py-4 invoice-page">

    
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1><i class="bi bi-receipt-cutoff me-2"></i>Invoice Management</h1>
                <p>Manage student invoices, payments, balances, and fee categories efficiently.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="#" class="btn-modern btn-modern-outline me-2">
                    <i class="bi bi-download"></i>
                    Export CSV
                </a>
                <a href="<?php echo e(route('invoices.create')); ?>" class="btn-modern btn-modern-primary">
                    <i class="bi bi-plus-lg"></i>
                    Create Invoice
                </a>
            </div>
        </div>
    </div>

    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon primary">
                    <i class="bi bi-receipt"></i>
                </div>
                <span class="stat-trend up">+12%</span>
            </div>
            <div class="stat-value"><?php echo e(number_format($invoices->total())); ?></div>
            <div class="stat-label">Total Invoices</div>
        </div>

        <div class="stat-card success">
            <div class="stat-header">
                <div class="stat-icon success">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <span class="stat-trend up">+8.5%</span>
            </div>
            <div class="stat-value"><?php echo e(number_format($invoices->sum('total_amount'), 2)); ?></div>
            <div class="stat-label">Total Amount</div>
        </div>

        <div class="stat-card info">
            <div class="stat-header">
                <div class="stat-icon info">
                    <i class="bi bi-wallet2"></i>
                </div>
                <span class="stat-trend up">+5.2%</span>
            </div>
            <div class="stat-value"><?php echo e(number_format($invoices->sum('paid_amount'), 2)); ?></div>
            <div class="stat-label">Paid Amount</div>
        </div>

        <div class="stat-card danger">
            <div class="stat-header">
                <div class="stat-icon danger">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <span class="stat-trend down">-2.1%</span>
            </div>
            <div class="stat-value"><?php echo e(number_format($invoices->sum('balance'), 2)); ?></div>
            <div class="stat-label">Outstanding Balance</div>
        </div>
    </div>

    
    <div class="bulk-bar" id="bulkBar">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-square"></i>
            <span class="fw-bold"><span id="selectedCount">0</span> selected</span>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-light" onclick="bulkExport()">
                <i class="bi bi-download"></i> Export
            </button>
            <button class="btn btn-sm btn-danger" onclick="bulkDelete()">
                <i class="bi bi-trash"></i> Delete
            </button>
        </div>
    </div>

    
    <div class="toolbar">
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" 
                   name="search" 
                   value="<?php echo e(request('search')); ?>"
                   placeholder="Search by invoice #, student name, or admission..."
                   onkeyup="debounceSearch(this.value)">
        </div>
        
        <div class="filter-group">
            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="paid" <?php echo e(request('status') == 'paid' ? 'selected' : ''); ?>>Paid</option>
                <option value="partial" <?php echo e(request('status') == 'partial' ? 'selected' : ''); ?>>Partial</option>
                <option value="unpaid" <?php echo e(request('status') == 'unpaid' ? 'selected' : ''); ?>>Unpaid</option>
            </select>
            
            <select name="student_type" class="filter-select" onchange="this.form.submit()">
                <option value="">All Types</option>
                <option value="New" <?php echo e(request('student_type') == 'New' ? 'selected' : ''); ?>>New Students</option>
                <option value="Old" <?php echo e(request('student_type') == 'Old' ? 'selected' : ''); ?>>Old Students</option>
            </select>
            
            <select name="date_range" class="filter-select" onchange="this.form.submit()">
                <option value="">All Time</option>
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
                <option value="year">This Year</option>
            </select>
            
            <span class="results-badge">
                <?php echo e($invoices->count()); ?> / <?php echo e(number_format($invoices->total())); ?>

            </span>
        </div>
    </div>

    
    <div class="table-container">
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th width="40">
                            <input type="checkbox" class="custom-checkbox" id="selectAll" onclick="toggleSelectAll()">
                        </th>
                        <th class="sortable" onclick="sortBy('invoice_no')">
                            Invoice # <i class="bi bi-arrow-down-up ms-1"></i>
                        </th>
                        <th class="sortable" onclick="sortBy('student')">
                            Student <i class="bi bi-arrow-down-up ms-1"></i>
                        </th>
                        <th>Type</th>
                        <th>Fee Categories</th>
                        <th class="sortable text-end" onclick="sortBy('total')">
                            Total <i class="bi bi-arrow-down-up ms-1"></i>
                        </th>
                        <th class="sortable text-end" onclick="sortBy('paid')">
                            Paid <i class="bi bi-arrow-down-up ms-1"></i>
                        </th>
                        <th class="sortable text-end" onclick="sortBy('balance')">
                            Balance <i class="bi bi-arrow-down-up ms-1"></i>
                        </th>
                        <th class="sortable" onclick="sortBy('status')">
                            Status <i class="bi bi-arrow-down-up ms-1"></i>
                        </th>
                        <th class="sortable" onclick="sortBy('date')">
                            Date <i class="bi bi-arrow-down-up ms-1"></i>
                        </th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $status = strtolower($invoice->status ?? '');
                            $statusClass = match($status) {
                                'paid' => 'status-paid',
                                'partial' => 'status-partial',
                                'unpaid' => 'status-unpaid',
                                default => 'bg-secondary',
                            };
                            
                            $studentType = $invoice->student->student_type ?? '';
                            $typeClass = match($studentType) {
                                'New' => 'type-new',
                                'Old' => 'type-old',
                                default => 'bg-secondary',
                            };
                            
                            $initials = strtoupper(
                                substr($invoice->student->first_name ?? 'S', 0, 1) . 
                                substr($invoice->student->last_name ?? 'T', 0, 1)
                            );
                        ?>
                        
                        <tr>
                            <td>
                                <input type="checkbox" class="custom-checkbox row-checkbox" 
                                       value="<?php echo e($invoice->id); ?>" 
                                       onchange="updateSelection()">
                            </td>
                            
                            <td>
                                <div class="cell-primary">#<?php echo e($invoice->invoice_no); ?></div>
                                <div class="cell-secondary">ID: <?php echo e($invoice->id); ?></div>
                            </td>
                            
                            <td>
                                <div class="student-cell">
                                    <div class="student-avatar"><?php echo e($initials); ?></div>
                                    <div>
                                        <div class="fw-semibold text-dark">
                                            <?php echo e($invoice->student->first_name ?? 'N/A'); ?>

                                            <?php echo e($invoice->student->last_name ?? ''); ?>

                                        </div>
                                        <div class="cell-secondary">
                                            <?php echo e($invoice->student->admission_no ?? 'No Admission No'); ?>

                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            
                            <td>
                                <?php if($studentType): ?>
                                    <span class="type-badge <?php echo e($typeClass); ?>">
                                        <i class="bi bi-person-<?php echo e($studentType == 'New' ? 'plus' : 'check'); ?>"></i>
                                        <?php echo e($studentType); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            
                            <td>
                                <div class="fee-tags">
                                    <?php $__currentLoopData = $invoice->invoiceItems->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="fee-tag"><?php echo e($item->feeCategory->name ?? 'N/A'); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($invoice->invoiceItems->count() > 2): ?>
                                        <span class="fee-more">+<?php echo e($invoice->invoiceItems->count() - 2); ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            
                            <td class="text-end">
                                <span class="amount amount-total"><?php echo e(number_format($invoice->total_amount ?? 0, 2)); ?></span>
                            </td>
                            
                            <td class="text-end">
                                <span class="amount amount-paid"><?php echo e(number_format($invoice->paid_amount ?? 0, 2)); ?></span>
                            </td>
                            
                            <td class="text-end">
                                <span class="amount amount-balance"><?php echo e(number_format($invoice->balance ?? 0, 2)); ?></span>
                            </td>
                            
                            <td>
                                <span class="status-badge <?php echo e($statusClass); ?>">
                                    <?php echo e(ucfirst($status ?: 'Unknown')); ?>

                                </span>
                            </td>
                            
                            <td>
                                <div class="fw-semibold"><?php echo e(optional($invoice->created_at)->format('d M Y')); ?></div>
                                <div class="cell-secondary"><?php echo e(optional($invoice->created_at)->diffForHumans()); ?></div>
                            </td>
                            
                            <td>
                                <div class="action-group">
                                    <a href="<?php echo e(route('invoices.show', $invoice->id)); ?>" 
                                       class="btn-action view" 
                                       title="View Invoice">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('invoices.edit', $invoice->id)); ?>" 
                                       class="btn-action edit" 
                                       title="Edit Invoice">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?php echo e(route('invoices.destroy', $invoice->id)); ?>" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Delete this invoice permanently?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn-action delete" title="Delete Invoice">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="11">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-receipt-cutoff"></i>
                                    </div>
                                    <h4>No Invoices Found</h4>
                                    <p>No invoice records match your current filters. Try adjusting your search criteria.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if($invoices->hasPages()): ?>
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    Showing <strong><?php echo e($invoices->firstItem() ?? 0); ?></strong> 
                    to <strong><?php echo e($invoices->lastItem() ?? 0); ?></strong> 
                    of <strong><?php echo e(number_format($invoices->total())); ?></strong> invoices
                </div>
                <div>
                    <?php echo e($invoices->withQueryString()->links('pagination::bootstrap-5')); ?>

                </div>
            </div>
        <?php endif; ?>
    </div>

</div>

<script>
    // Debounce search for performance with large datasets
    let searchTimeout;
    function debounceSearch(value) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const url = new URL(window.location);
            if (value) {
                url.searchParams.set('search', value);
            } else {
                url.searchParams.delete('search');
            }
            window.location.href = url.toString();
        }, 500);
    }

    // Bulk selection
    function toggleSelectAll() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateSelection();
    }

    function updateSelection() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        const bulkBar = document.getElementById('bulkBar');
        const countSpan = document.getElementById('selectedCount');
        
        countSpan.textContent = checked.length;
        
        if (checked.length > 0) {
            bulkBar.classList.add('active');
        } else {
            bulkBar.classList.remove('active');
            document.getElementById('selectAll').checked = false;
        }
    }

    function bulkDelete() {
        if (!confirm('Delete selected invoices? This action cannot be undone.')) return;
        // Implement bulk delete logic
    }

    function bulkExport() {
        // Implement bulk export logic
    }

    function sortBy(column) {
        const url = new URL(window.location);
        const currentSort = url.searchParams.get('sort');
        const currentDir = url.searchParams.get('direction');
        
        let newDir = 'asc';
        if (currentSort === column && currentDir === 'asc') {
            newDir = 'desc';
        }
        
        url.searchParams.set('sort', column);
        url.searchParams.set('direction', newDir);
        window.location.href = url.toString();
    }
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/invoices/index.blade.php ENDPATH**/ ?>