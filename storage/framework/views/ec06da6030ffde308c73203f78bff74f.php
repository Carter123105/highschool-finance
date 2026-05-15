<?php $__env->startSection('content'); ?>

<?php
    use Carbon\Carbon;

    $payments = $payments ?? collect();

    $selectedDate = request('date')
        ? Carbon::parse(request('date'))->format('Y-m-d')
        : now()->format('Y-m-d');
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


<style>
@media print {

    @page {
        size: A4 portrait;
        margin: 10mm;
    }

    html, body {
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
    }

    /* ================= RESET BOOTSTRAP LAYOUT ================= */
    .container-fluid,
    .payments-report {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        display: block !important;
    }

    /* REMOVE GRID SHIFT (THIS FIXES RIGHT ALIGNMENT ISSUE) */
    .row,
    .col,
    .col-md-6,
    .col-xl-6 {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    /* ================= HIDE UI ================= */
    .no-print,
    .payments-header,
    .header-actions,
    .search-box,
    .btn,
    .btn-print,
    .btn-back,
    form,
    input,
    select,
    .navbar,
    .sidebar {
        display: none !important;
    }

    /* ================= REMOVE CARD STYLING ================= */
    .card,
    .card-body,
    .payment-card {
        border: none !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    /* ================= TABLE FIX ================= */
    .table-responsive {
        display: block !important;
        width: 100% !important;
        overflow: visible !important;
    }

    table {
        width: 100% !important;
        max-width: 100% !important;
        border-collapse: collapse !important;
        margin: 0 !important;
        table-layout: auto !important;
    }

    thead {
        display: table-header-group !important;
    }

    th {
        background: #111827 !important;
        color: #fff !important;
        border: 1px solid #000 !important;
        padding: 8px !important;
        font-size: 10px !important;
        text-align: left !important;
        white-space: nowrap !important;
    }

    td {
        border: 1px solid #ddd !important;
        padding: 7px !important;
        font-size: 10px !important;
        text-align: left !important;
        white-space: nowrap !important;
        vertical-align: middle !important;
    }

    tbody tr:nth-child(even) {
        background: #f3f4f6 !important;
    }

    /* ================= CLEAN BADGES ================= */
    .badge {
        background: #fff !important;
        color: #000 !important;
        border: 1px solid #333 !important;
        font-size: 9px !important;
    }

    /* ================= REMOVE AVATAR ================= */
    .avatar {
        display: none !important;
    }

    .student-box {
        display: block !important;
    }

    /* ================= FIX LEFT ALIGNMENT ================= */
    body {
        float: none !important;
        display: block !important;
    }

    table {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
}
</style>


<style>
body {
    background: #f4f7fb;
}

.payments-report {
    min-height: 100vh;
}

.payments-header {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
}

.page-title {
    font-size: 30px;
    font-weight: 800;
}

.header-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.search-box {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    padding: 10px 14px;
    border-radius: 10px;
}

.search-box input {
    border: none;
    outline: none;
}

.btn-print {
    background: #2563eb;
    color: #fff;
    padding: 10px 15px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
}

.btn-back {
    background: #111827;
    color: #fff;
    padding: 10px 15px;
    border-radius: 10px;
    text-decoration: none;
}

.payment-card {
    background: #fff;
    border-radius: 12px;
}

table {
    width: 100%;
}
</style>

<div class="payments-report container-fluid py-4">

    
    <div class="payments-header no-print mb-3">

        <div>
            <h2 class="page-title">
                <i class="bi bi-cash-stack text-success"></i>
                Payments Report
            </h2>
        </div>

        <div class="header-actions">

            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="searchPayment" placeholder="Search...">
            </div>

            <form method="GET" action="<?php echo e(route('finance.payments')); ?>">
                <input type="date"
                       name="date"
                       value="<?php echo e($selectedDate); ?>"
                       onchange="this.form.submit()">
            </form>

            <button class="btn-print" onclick="window.print()">
                <i class="bi bi-printer"></i> Print
            </button>

            <a href="<?php echo e(route('finance.summary')); ?>" class="btn-back">
                Back
            </a>

        </div>
    </div>

    
    <div class="card payment-card">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Receipt</th>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Invoice</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Receiver</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <?php
                            $displayDate = $payment->payment_date
                                ? Carbon::parse($payment->payment_date)
                                : Carbon::parse($payment->created_at);
                        ?>

                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><span class="badge"><?php echo e($payment->receipt_no); ?></span></td>
                            <td><?php echo e($payment->student->first_name ?? ''); ?> <?php echo e($payment->student->last_name ?? ''); ?></td>
                            <td><?php echo e($payment->student->schoolClass->name ?? ''); ?></td>
                            <td><?php echo e($payment->invoice->invoice_no ?? ''); ?></td>
                            <td><b>$<?php echo e(number_format($payment->amount_paid,2)); ?></b></td>
                            <td><?php echo e($payment->payment_method); ?></td>
                            <td><?php echo e($payment->receiver->name ?? ''); ?></td>
                            <td>
                                <?php echo e($displayDate->format('d M Y')); ?>

                                <br>
                                <small><?php echo e($displayDate->format('h:i A')); ?></small>
                            </td>
                            <td>Completed</td>
                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="10" class="text-center p-4">
                                No records found
                            </td>
                        </tr>
                    <?php endif; ?>

                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

<script>
document.getElementById('searchPayment')?.addEventListener('input', function () {
    let val = this.value.toLowerCase();

    document.querySelectorAll('tbody tr').forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(val)
            ? ''
            : 'none';
    });
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/finance/payments.blade.php ENDPATH**/ ?>