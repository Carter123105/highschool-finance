<?php $__env->startSection('content'); ?>

<?php
    // SAFE FALLBACKS
    $income = $totalIncome ?? 0;
    $expenses = $totalExpenses ?? 0;
    $expected = $totalExpected ?? 0;

    // CALCULATIONS
    $balanceFees = $expected - $income;
    $netProfit = $income - $expenses;

    // DAILY TRANSACTIONS
    $todayPayments = $todayPayments ?? collect();
    $todayExpenses = $todayExpenses ?? collect();

    $totalTodayPayments = $todayPayments->sum('amount_paid') ?? 0;
    $totalTodayExpenses = $todayExpenses->sum('amount') ?? 0;
    $totalTodayTransactions = $totalTodayPayments + $totalTodayExpenses;
    $todayCount = $todayPayments->count() + $todayExpenses->count();
?>

<div class="finance-dashboard container-fluid py-4">

    
    <div class="dashboard-header mb-4">
        <div>
            <h2 class="dashboard-title">Finance Dashboard</h2>
            <p class="dashboard-subtitle">Overview of income, expenses, balances and daily financial activities</p>
        </div>

        <div class="header-actions">
            
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchReport" placeholder="Search report...">
            </div>

            
            <button class="btn-filter" title="Filter">
                <i class="bi bi-funnel"></i>
            </button>

            
            <button type="button" class="btn-daily" onclick="openDailyModal()">
                <i class="bi bi-calendar-check"></i>
                Daily Transactions
                <?php if($todayCount > 0): ?>
                    <span class="badge bg-dark"><?php echo e($todayCount); ?></span>
                <?php endif; ?>
            </button>

            
            <a href="<?php echo e(route('expenses.create')); ?>" class="btn-expense">
                <i class="bi bi-plus-circle"></i>
                Add Expense
            </a>
        </div>
    </div>

    
    <div class="row g-4 mb-4">
        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card income">
                <div class="stat-icon"><i class="bi bi-cash-coin"></i></div>
                <div>
                    <small>Total Income</small>
                    <h3>$<?php echo e(number_format($income, 2)); ?></h3>
                </div>
            </div>
        </div>

        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card expense">
                <div class="stat-icon"><i class="bi bi-receipt"></i></div>
                <div>
                    <small>Total Expenses</small>
                    <h3>$<?php echo e(number_format($expenses, 2)); ?></h3>
                </div>
            </div>
        </div>

        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card warning">
                <div class="stat-icon"><i class="bi bi-graph-up-arrow"></i></div>
                <div>
                    <small>Outstanding Fees</small>
                    <h3>$<?php echo e(number_format($balanceFees, 2)); ?></h3>
                </div>
            </div>
        </div>

        
        <div class="col-xl-3 col-md-6">
            <div class="stat-card profit">
                <div class="stat-icon"><i class="bi bi-bar-chart-line"></i></div>
                <div>
                    <small>Net Profit</small>
                    <h3 class="<?php echo e($netProfit >= 0 ? 'text-success' : 'text-danger'); ?>">
                        $<?php echo e(number_format($netProfit, 2)); ?>

                    </h3>
                </div>
            </div>
        </div>
    </div>

    
    <?php if($todayCount > 0): ?>
        <div class="alert alert-info daily-alert mb-4">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-calendar2-check-fill fs-3"></i>
                <div>
                    <strong>Today's Transactions:</strong>
                    <?php echo e($todayCount); ?> transaction(s) recorded today totaling
                    <strong>$<?php echo e(number_format($totalTodayTransactions, 2)); ?></strong>
                </div>
            </div>
        </div>
    <?php endif; ?>

    
    <div class="row g-4" id="reportGrid">
        <?php
            $reports = [
                ['name'=>'Income Report','route'=>'finance.income','icon'=>'cash-stack','color'=>'success'],
                ['name'=>'Expenses Report','route'=>'finance.expenses','icon'=>'receipt','color'=>'danger'],
                ['name'=>'Balance Report','route'=>'finance.balance','icon'=>'graph-up','color'=>'warning'],
                ['name'=>'Payments Report','route'=>'finance.payments','icon'=>'person-check','color'=>'primary'],
                ['name'=>'Class Report','route'=>'finance.classes','icon'=>'building','color'=>'dark'],
                ['name'=>'Student Report','route'=>'finance.students','icon'=>'people','color'=>'info'],
                ['name'=>'Invoice Report','route'=>'finance.invoices','icon'=>'file-earmark-text','color'=>'secondary'],
            ];
        ?>

        <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-xl-3 col-lg-4 col-md-6 report-item">
                <a href="<?php echo e(route($report['route'])); ?>" class="report-card <?php echo e($report['color']); ?>">
                    <div class="report-left">
                        <div class="report-icon">
                            <i class="bi bi-<?php echo e($report['icon']); ?>"></i>
                        </div>
                        <div>
                            <div class="report-title"><?php echo e($report['name']); ?></div>
                            <div class="report-subtitle">Open detailed analytics</div>
                        </div>
                    </div>
                    <div class="report-arrow">
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </a>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

</div>


<div class="modal fade" id="dailyTransactionsModal" tabindex="-1" aria-labelledby="dailyTransactionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="dailyTransactionsModalLabel">
                    <i class="bi bi-calendar-check me-2"></i>
                    Daily Transactions Tracker
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            
            <div class="modal-body p-0">
                
                <div class="date-selector p-4 bg-light border-bottom">
                    <div class="row align-items-end g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">
                                <i class="bi bi-calendar3 me-1"></i>Select Date
                            </label>
                            <input type="date"
                                   id="transactionDate"
                                   class="form-control form-control-lg"
                                   value="<?php echo e(now()->format('Y-m-d')); ?>"
                                   max="<?php echo e(now()->format('Y-m-d')); ?>">
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
                                <?php echo e(now()->format('l, F d, Y')); ?>

                            </span>
                        </div>
                    </div>
                </div>

                
                <div id="loadingSpinner" class="text-center py-5 d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Loading transactions...</p>
                </div>

                
                <div id="dailyContent">
                    
                    <div class="daily-summary p-4 bg-light">
                        <div class="row g-3">
                            
                            <div class="col-md-4">
                                <div class="summary-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted">Payments Received</small>
                                            <h4 class="text-success mb-0" id="totalPayments">
                                                $<?php echo e(number_format($totalTodayPayments, 2)); ?>

                                            </h4>
                                            <small class="text-muted" id="countPayments"><?php echo e($todayPayments->count()); ?> payment(s)</small>
                                        </div>
                                        <div class="summary-icon bg-success bg-opacity-10 text-success">
                                            <i class="bi bi-cash-coin"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="col-md-4">
                                <div class="summary-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted">Expenses Made</small>
                                            <h4 class="text-danger mb-0" id="totalExpenses">
                                                $<?php echo e(number_format($totalTodayExpenses, 2)); ?>

                                            </h4>
                                            <small class="text-muted" id="countExpenses"><?php echo e($todayExpenses->count()); ?> expense(s)</small>
                                        </div>
                                        <div class="summary-icon bg-danger bg-opacity-10 text-danger">
                                            <i class="bi bi-receipt"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="col-md-4">
                                <div class="summary-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted">Net Flow</small>
                                            <h4 class="text-dark mb-0" id="totalNet">
                                                $<?php echo e(number_format($totalTodayTransactions, 2)); ?>

                                            </h4>
                                            <small class="text-muted" id="countTotal"><?php echo e($todayCount); ?> total transaction(s)</small>
                                        </div>
                                        <div class="summary-icon bg-dark bg-opacity-10 text-dark">
                                            <i class="bi bi-bar-chart"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <ul class="nav nav-tabs px-4 pt-3" id="dailyTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="payments-tab" data-bs-toggle="tab" data-bs-target="#paymentsPane" type="button" role="tab">
                                <i class="bi bi-cash-coin me-1"></i>Payments
                                <span class="badge bg-success ms-1" id="tabPayCount"><?php echo e($todayPayments->count()); ?></span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="expenses-tab" data-bs-toggle="tab" data-bs-target="#expensesPane" type="button" role="tab">
                                <i class="bi bi-receipt me-1"></i>Expenses
                                <span class="badge bg-danger ms-1" id="tabExpCount"><?php echo e($todayExpenses->count()); ?></span>
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content p-4" id="dailyTabContent">
                        
                        <div class="tab-pane fade show active" id="paymentsPane" role="tabpanel">
                            <div class="table-responsive" id="paymentsTableContainer">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
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
                                        <?php $__empty_1 = true; $__currentLoopData = $todayPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td><?php echo e($index + 1); ?></td>
                                                <td><span class="badge bg-secondary"><?php echo e($payment->receipt_no); ?></span></td>
                                                <td>
                                                    <strong><?php echo e($payment->student->first_name ?? ''); ?> <?php echo e($payment->student->last_name ?? ''); ?></strong>
                                                    <br><small class="text-muted"><?php echo e($payment->student->student_id ?? ''); ?></small>
                                                </td>
                                                <td class="fw-bold text-success">$<?php echo e(number_format($payment->amount_paid, 2)); ?></td>
                                                <td><span class="badge bg-info"><?php echo e($payment->payment_method); ?></span></td>
                                                <td><?php echo e(\Carbon\Carbon::parse($payment->payment_date)->format('M d, Y')); ?></td>
                                                <td>
                                                    <span class="badge bg-success">
                                                        <?php echo e(\Carbon\Carbon::parse($payment->created_at)->format('h:i A')); ?>

                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr id="noPaymentsRow">
                                                <td colspan="7" class="text-center text-muted py-5">
                                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                    No payments recorded today
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot id="paymentsTableFoot" class="table-group-divider <?php echo e($todayPayments->count() == 0 ? 'd-none' : ''); ?>">
                                        <tr class="table-light fw-bold">
                                            <td colspan="3">Total Payments</td>
                                            <td class="text-success" id="footPayTotal">$<?php echo e(number_format($totalTodayPayments, 2)); ?></td>
                                            <td colspan="3"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        
                        <div class="tab-pane fade" id="expensesPane" role="tabpanel">
                            <div class="table-responsive" id="expensesTableContainer">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
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
                                        <?php $__empty_1 = true; $__currentLoopData = $todayExpenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td><?php echo e($index + 1); ?></td>
                                                <td><strong><?php echo e($expense->title ?? 'N/A'); ?></strong></td>
                                                <td>
                                                    <span class="badge bg-<?php echo e($expense->category ? 'info' : 'warning text-dark'); ?>">
                                                        <?php echo e($expense->category ?? 'Uncategorized'); ?>

                                                    </span>
                                                </td>
                                                <td class="fw-bold text-danger">$<?php echo e(number_format($expense->amount, 2)); ?></td>
                                                <td><small class="text-muted"><?php echo e(Str::limit($expense->description, 50)); ?></small></td>
                                                <td><?php echo e($expense->expense_date ? \Carbon\Carbon::parse($expense->expense_date)->format('M d, Y') : 'N/A'); ?></td>
                                                <td>
                                                    <span class="badge bg-danger">
                                                        <?php echo e(\Carbon\Carbon::parse($expense->created_at)->format('h:i A')); ?>

                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr id="noExpensesRow">
                                                <td colspan="7" class="text-center text-muted py-5">
                                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                    No expenses recorded today
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot id="expensesTableFoot" class="table-group-divider <?php echo e($todayExpenses->count() == 0 ? 'd-none' : ''); ?>">
                                        <tr class="table-light fw-bold">
                                            <td colspan="3">Total Expenses</td>
                                            <td class="text-danger" id="footExpTotal">$<?php echo e(number_format($totalTodayExpenses, 2)); ?></td>
                                            <td colspan="3"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="modal-footer bg-light">
                <div class="d-flex justify-content-between w-100 align-items-center">
                    <small class="text-muted" id="footerDateText">
                        <i class="bi bi-info-circle me-1"></i>
                        Showing transactions for <?php echo e(now()->format('l, F d, Y')); ?>

                    </small>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
.finance-dashboard {
    background: #f4f7fb;
    min-height: 100vh;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}

.dashboard-title {
    font-size: 28px;
    font-weight: 800;
    color: #0f172a;
}

.dashboard-subtitle {
    color: #64748b;
    margin: 0;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.search-box {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fff;
    padding: 10px 14px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,.05);
}

.search-box input {
    border: none;
    outline: none;
    background: transparent;
    min-width: 200px;
}

.btn-filter {
    width: 45px;
    height: 45px;
    border: none;
    border-radius: 12px;
    background: #111827;
    color: #fff;
}

.btn-daily {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    color: #fff;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-daily:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(37,99,235,0.4);
}

.btn-daily .badge {
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 20px;
}

.btn-expense {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 12px;
    background: linear-gradient(135deg, #dc2626, #ef4444);
    color: #fff;
    text-decoration: none;
    font-weight: 700;
}

.daily-alert {
    border: none;
    border-radius: 16px;
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #1e40af;
}

.stat-card {
    background: #fff;
    border-radius: 16px;
    padding: 20px;
    display: flex;
    gap: -6px;
    align-items: center;
    box-shadow: 0 5px 18px rgba(0,0,0,.05);
}

.stat-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
}

.income .stat-icon { background: #16a34a; }
.expense .stat-icon { background: #dc2626; }
.warning .stat-icon { background: #f59e0b; }
.profit .stat-icon { background: #2563eb; }

.report-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px;
    border-radius: 16px;
    color: #fff;
    text-decoration: none;
    transition: .3s;
}

.report-card:hover {
    transform: translateY(-5px);
}

.report-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.report-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(255,255,255,.2);
    display: flex;
    justify-content: center;
    align-items: center;
}

.report-title { font-weight: 700; }
.report-subtitle { font-size: 12px; opacity: .8; }

.success { background: linear-gradient(135deg, #16a34a, #22c55e); }
.danger { background: linear-gradient(135deg, #dc2626, #ef4444); }
.warning { background: linear-gradient(135deg, #f59e0b, #facc15); }
.primary { background: linear-gradient(135deg, #2563eb, #3b82f6); }
.dark { background: linear-gradient(135deg, #0f172a, #1e293b); }
.info { background: linear-gradient(135deg, #0ea5e9, #38bdf8); }
.secondary { background: linear-gradient(135deg, #475569, #64748b); }

.date-selector { background: #f8fafc !important; }
.date-selector .form-control-lg {
    border-radius: 12px;
    border: 2px solid #e2e8f0;
    font-weight: 600;
}
.date-selector .form-control-lg:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}

.summary-card {
    background: #fff;
    padding: 20px;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.summary-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.nav-tabs .nav-link {
    font-weight: 600;
    color: #6b7280;
    border: none;
    border-bottom: 3px solid transparent;
    padding: 12px 20px;
}

.nav-tabs .nav-link.active {
    color: #0f172a;
    border-bottom: 3px solid #2563eb;
    background: transparent;
}

.table-hover tbody tr:hover {
    background-color: rgba(37,99,235,0.03);
}

@media (max-width: 768px) {
    .dashboard-title { font-size: 22px; }
    .search-box input { min-width: 140px; }
    .btn-expense, .btn-daily { width: 100%; justify-content: center; }
    .header-actions { justify-content: stretch; }
    .header-actions > * { flex: 1; }
}
</style>


<script>
document.addEventListener('DOMContentLoaded', function() {

    // SEARCH REPORTS
    const searchInput = document.getElementById('searchReport');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            let value = this.value.toLowerCase();
            document.querySelectorAll('.report-item').forEach(item => {
                item.style.display = item.innerText.toLowerCase().includes(value) ? 'block' : 'none';
            });
        });
    }

    // MODAL SETUP
    const modalEl = document.getElementById('dailyTransactionsModal');
    if (!modalEl) {
        console.error('Modal element #dailyTransactionsModal not found!');
        return;
    }

    const dailyModal = new bootstrap.Modal(modalEl);
    let currentDate = '<?php echo e(now()->format('Y-m-d')); ?>';

    // OPEN DAILY MODAL
    window.openDailyModal = function() {
        dailyModal.show();
        modalEl.addEventListener('shown.bs.modal', function handler() {
            loadDailyData(currentDate);
            modalEl.removeEventListener('shown.bs.modal', handler);
        }, { once: true });
    };

    // LOAD TODAY
    window.loadToday = function() {
        let today = new Date().toISOString().split('T')[0];
        const dateInput = document.getElementById('transactionDate');
        if (dateInput) dateInput.value = today;
        loadDailyData(today);
    };

    // LOAD YESTERDAY
    window.loadYesterday = function() {
        let yesterday = new Date();
        yesterday.setDate(yesterday.getDate() - 1);
        let dateStr = yesterday.toISOString().split('T')[0];
        const dateInput = document.getElementById('transactionDate');
        if (dateInput) dateInput.value = dateStr;
        loadDailyData(dateStr);
    };

    // LOAD DAILY DATA VIA AJAX
    window.loadDailyData = function(date) {
        currentDate = date;
        const spinner = document.getElementById('loadingSpinner');
        const content = document.getElementById('dailyContent');

        if (spinner) spinner.classList.remove('d-none');
        if (content) content.classList.add('d-none');

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        fetch('/finance/daily-transactions?date=' + encodeURIComponent(date), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error('HTTP ' + response.status + ': ' + text.substring(0, 200));
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                updateModalContent(data, date);
            } else {
                alert('Error: ' + (data.message || 'Failed to load data'));
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            alert('Failed to load transactions. Error: ' + error.message);
        })
        .finally(() => {
            if (spinner) spinner.classList.add('d-none');
            if (content) content.classList.remove('d-none');
        });
    };

    // UPDATE MODAL CONTENT
    window.updateModalContent = function(data, date) {
        let dateObj = new Date(date + 'T00:00:00');
        let options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        let formattedDate = dateObj.toLocaleDateString('en-US', options);

        const badge = document.getElementById('selectedDateBadge');
        const footerText = document.getElementById('footerDateText');
        if (badge) badge.innerHTML = '<i class="bi bi-calendar-date me-1"></i>' + formattedDate;
        if (footerText) footerText.innerHTML = '<i class="bi bi-info-circle me-1"></i>Showing transactions for ' + formattedDate;

        const totalPayments = parseFloat(data.totalPayments || 0);
        const totalExpenses = parseFloat(data.totalExpenses || 0);
        const totalNet = totalPayments + totalExpenses;
        const payCount = data.payments ? data.payments.length : 0;
        const expCount = data.expenses ? data.expenses.length : 0;

        const elTotalPay = document.getElementById('totalPayments');
        const elCountPay = document.getElementById('countPayments');
        const elTotalExp = document.getElementById('totalExpenses');
        const elCountExp = document.getElementById('countExpenses');
        const elTotalNet = document.getElementById('totalNet');
        const elCountTotal = document.getElementById('countTotal');

        if (elTotalPay) elTotalPay.textContent = '$' + totalPayments.toFixed(2);
        if (elCountPay) elCountPay.textContent = payCount + ' payment(s)';
        if (elTotalExp) elTotalExp.textContent = '$' + totalExpenses.toFixed(2);
        if (elCountExp) elCountExp.textContent = expCount + ' expense(s)';
        if (elTotalNet) elTotalNet.textContent = '$' + totalNet.toFixed(2);
        if (elCountTotal) elCountTotal.textContent = (payCount + expCount) + ' total transaction(s)';

        const tabPayCount = document.getElementById('tabPayCount');
        const tabExpCount = document.getElementById('tabExpCount');
        if (tabPayCount) tabPayCount.textContent = payCount;
        if (tabExpCount) tabExpCount.textContent = expCount;

        // Update payments table
        const payBody = document.getElementById('paymentsTableBody');
        const payFoot = document.getElementById('paymentsTableFoot');
        if (payBody) {
            if (payCount === 0) {
                payBody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-5"><i class="bi bi-inbox fs-1 d-block mb-2"></i>No payments recorded on ' + formattedDate + '</td></tr>';
                if (payFoot) payFoot.classList.add('d-none');
            } else {
                let html = '';
                data.payments.forEach((p, i) => {
                    html += '<tr>';
                    html += '<td>' + (i + 1) + '</td>';
                    html += '<td><span class="badge bg-secondary">' + (p.receipt_no || 'N/A') + '</span></td>';
                    html += '<td><strong>' + (p.student_name || 'Unknown') + '</strong><br><small class="text-muted">' + (p.student_id || '') + '</small></td>';
                    html += '<td class="fw-bold text-success">$' + parseFloat(p.amount_paid || 0).toFixed(2) + '</td>';
                    html += '<td><span class="badge bg-info">' + (p.payment_method || 'Cash') + '</span></td>';
                    html += '<td>' + (p.payment_date || 'N/A') + '</td>';
                    html += '<td><span class="badge bg-success">' + (p.time || 'N/A') + '</span></td>';
                    html += '</tr>';
                });
                payBody.innerHTML = html;
                if (payFoot) {
                    payFoot.classList.remove('d-none');
                    const footPayTotal = document.getElementById('footPayTotal');
                    if (footPayTotal) footPayTotal.textContent = '$' + totalPayments.toFixed(2);
                }
            }
        }

        // Update expenses table
        const expBody = document.getElementById('expensesTableBody');
        const expFoot = document.getElementById('expensesTableFoot');
        if (expBody) {
            if (expCount === 0) {
                expBody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-5"><i class="bi bi-inbox fs-1 d-block mb-2"></i>No expenses recorded on ' + formattedDate + '</td></tr>';
                if (expFoot) expFoot.classList.add('d-none');
            } else {
                let html = '';
                data.expenses.forEach((e, i) => {
                    let catBadge = e.category && e.category !== 'N/A' ? 'bg-info' : 'bg-warning text-dark';
                    let catText = e.category || 'Uncategorized';
                    html += '<tr>';
                    html += '<td>' + (i + 1) + '</td>';
                    html += '<td><strong>' + (e.title || 'N/A') + '</strong></td>';
                    html += '<td><span class="badge ' + catBadge + '">' + catText + '</span></td>';
                    html += '<td class="fw-bold text-danger">$' + parseFloat(e.amount || 0).toFixed(2) + '</td>';
                    html += '<td><small class="text-muted">' + (e.description ? e.description.substring(0, 50) : '') + '</small></td>';
                    html += '<td>' + (e.expense_date || 'N/A') + '</td>';
                    html += '<td><span class="badge bg-danger">' + (e.time || 'N/A') + '</span></td>';
                    html += '</tr>';
                });
                expBody.innerHTML = html;
                if (expFoot) {
                    expFoot.classList.remove('d-none');
                    const footExpTotal = document.getElementById('footExpTotal');
                    if (footExpTotal) footExpTotal.textContent = '$' + totalExpenses.toFixed(2);
                }
            }
        }
    };

    // DATE CHANGE LISTENER
    const dateInput = document.getElementById('transactionDate');
    if (dateInput) {
        dateInput.addEventListener('change', function() {
            loadDailyData(this.value);
        });
    }

});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/finance/summary.blade.php ENDPATH**/ ?>