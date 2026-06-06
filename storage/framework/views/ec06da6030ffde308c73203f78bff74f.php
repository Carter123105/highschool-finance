<?php $__env->startSection('content'); ?>

<?php
    use Carbon\Carbon;
    use App\Models\Setting;

    $payments = $payments ?? collect();

    $selectedDate = request('date')
        ? Carbon::parse(request('date'))->format('Y-m-d')
        : now()->format('Y-m-d');

    $totalPayment = $payments->sum('amount_paid');

    /* ================= SETTINGS (FIXED) ================= */
    $setting = Setting::first();

    $schoolName = $setting?->school_name ?? config('app.name');

    // ✅ FIXED COLUMN NAME (IMPORTANT)
    $schoolAddress = $setting?->school_address ?? 'Address not available';

    $schoolPhone = $setting?->school_phone ?? null;

    $schoolLogo = $setting?->logo
        ? asset('storage/' . $setting->logo)
        : null;
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>

/* ================= SCREEN ================= */
body {
    background: #f4f7fb;
}

.payments-report {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* HEADER */
.payments-header {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 20px;
}

.page-title {
    font-size: 28px;
    font-weight: 800;
}

/* BUTTONS */
.header-actions {
    display: flex;
    gap: 10px;
}

.btn-print {
    background: #2563eb;
    color: #fff;
    border: none;
    padding: 10px 15px;
    border-radius: 10px;
}

.btn-back {
    background: #111827;
    color: #fff;
    padding: 10px 15px;
    border-radius: 10px;
    text-decoration: none;
}

/* TABLE HEADER GREEN */
thead th {
    background: #16a34a;
    color: #fff;
    font-weight: 700;
    font-size: 15px;
    padding: 12px;
    white-space: nowrap;
}

/* PRINT HEADER */
.print-header {
    text-align: center;
    margin-bottom: 20px;
}

.print-header img {
    max-height: 80px;
    margin-bottom: 10px;
}

.print-header h2 {
    font-weight: 900;
    margin: 0;
}

.print-header p {
    margin: 2px 0;
    color: #444;
}

/* ================= PRINT ================= */
@media print {

    @page {
        size: A4 landscape;
        margin: 8mm;
    }

    html, body {
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
    }

    body * {
        visibility: hidden;
    }

    .payments-report,
    .payments-report * {
        visibility: visible;
    }

    .payments-report {
        position: absolute;
        left: 0;
        top: 0;
        width: 100% !important;
    }

    aside, nav, .sidebar, .sidebar-wrapper {
        display: none !important;
    }

    .header-actions,
    .btn,
    form,
    input,
    select {
        display: none !important;
    }

    .print-header {
        border-bottom: 2px solid #111827;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }

    .print-header h2 {
        font-size: 20pt !important;
    }

    .print-header p {
        font-size: 12pt !important;
        color: #000 !important;
    }

    table {
        width: 100% !important;
        border-collapse: collapse !important;
    }

    th {
        background: #111827 !important;
        color: #fff !important;
        font-size: 12px !important;
        padding: 10px !important;
        border: 1px solid #000 !important;
    }

    td {
        font-size: 12px !important;
        padding: 8px !important;
        border: 1px solid #ddd !important;
        white-space: nowrap !important;
    }

    tbody tr:nth-child(even) {
        background: #f8fafc !important;
    }

    tfoot td {
        font-weight: bold !important;
        background: #e5e7eb !important;
        border: 1px solid #000 !important;
    }
}

</style>

<div class="payments-report">

    
    <div class="payments-header no-print">

        <h2 class="page-title">
            <i class="bi bi-cash-stack text-success"></i>
            Payments Report
        </h2>

        <div class="header-actions">

            <form method="GET" action="<?php echo e(route('finance.payments')); ?>">
                <input type="date"
                       name="date"
                       value="<?php echo e($selectedDate); ?>"
                       onchange="this.form.submit()"
                       class="form-control">
            </form>

            <button class="btn-print" onclick="window.print()">
                <i class="bi bi-printer"></i> Print
            </button>

            <a href="<?php echo e(route('finance.summary')); ?>" class="btn-back">
                Back
            </a>

        </div>
    </div>

    
    <div class="print-header">

        <?php if($schoolLogo): ?>
            <img src="<?php echo e($schoolLogo); ?>" alt="School Logo">
        <?php endif; ?>

        <h2><?php echo e($schoolName); ?></h2>

        
        <p><?php echo e($schoolAddress); ?></p>

        <?php if($schoolPhone): ?>
            <p><?php echo e($schoolPhone); ?></p>
        <?php endif; ?>

        <p>
            <strong>
                Payments Report - <?php echo e(Carbon::parse($selectedDate)->format('d M Y')); ?>

            </strong>
        </p>
    </div>

    
    <div class="card payment-card">

        <table class="table table-bordered align-middle mb-0">

            <thead>
                <tr>
                    <th>#</th>
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
                    <td><?php echo e($payment->student->first_name ?? ''); ?> <?php echo e($payment->student->last_name ?? ''); ?></td>
                    <td><?php echo e($payment->student->schoolClass->name ?? ''); ?></td>
                    <td><?php echo e($payment->invoice->invoice_no ?? ''); ?></td>
                    <td>$<?php echo e(number_format($payment->amount_paid,2)); ?></td>
                    <td><?php echo e($payment->payment_method); ?></td>
                    <td><?php echo e($payment->receiver->name ?? ''); ?></td>
                    <td><?php echo e($displayDate->format('d M Y h:i A')); ?></td>
                    <td>Completed</td>
                </tr>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9" class="text-center p-4">
                        No records found
                    </td>
                </tr>
            <?php endif; ?>

            </tbody>

            <?php if($payments->isNotEmpty()): ?>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end">Total collected:</td>
                    <td>$<?php echo e(number_format($totalPayment,2)); ?></td>
                    <td colspan="4"></td>
                </tr>
            </tfoot>
            <?php endif; ?>

        </table>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/finance/payments.blade.php ENDPATH**/ ?>