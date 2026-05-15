<?php $__env->startSection('content'); ?>

<?php

/*
|--------------------------------------------------------------------------
| BUILD CATEGORY SUMMARY
|--------------------------------------------------------------------------
*/

$categorySummary = [];

foreach ($payments as $payment) {

    foreach ($payment->allocations as $allocation) {

        $categoryName =
            $allocation->invoiceItem->feeCategory->name
            ?? 'General Fee';

        if (!isset($categorySummary[$categoryName])) {

            $categorySummary[$categoryName] = [
                'billed' => 0,
                'received' => 0,
            ];
        }

        // TOTAL BILLED
        $categorySummary[$categoryName]['billed'] +=
            $allocation->invoiceItem->subtotal ?? 0;

        // TOTAL RECEIVED
        $categorySummary[$categoryName]['received'] +=
            $allocation->amount ?? 0;
    }
}

/*
|--------------------------------------------------------------------------
| REMOVE DUPLICATE BILLED VALUES
|--------------------------------------------------------------------------
*/

$uniqueInvoiceItems = collect();

foreach ($payments as $payment) {

    foreach ($payment->allocations as $allocation) {

        $item = $allocation->invoiceItem;

        if (!$item) continue;

        $key = $item->id;

        if (!$uniqueInvoiceItems->has($key)) {

            $uniqueInvoiceItems->put($key, $item);
        }
    }
}

/*
|--------------------------------------------------------------------------
| REBUILD CLEAN TOTALS
|--------------------------------------------------------------------------
*/

$cleanSummary = [];

foreach ($uniqueInvoiceItems as $item) {

    $category =
        $item->feeCategory->name
        ?? 'General Fee';

    if (!isset($cleanSummary[$category])) {

        $cleanSummary[$category] = [
            'billed' => 0,
            'received' => 0,
        ];
    }

    $cleanSummary[$category]['billed'] += $item->subtotal;
}

/*
|--------------------------------------------------------------------------
| ADD RECEIVED TOTALS
|--------------------------------------------------------------------------
*/

foreach ($payments as $payment) {

    foreach ($payment->allocations as $allocation) {

        $category =
            $allocation->invoiceItem->feeCategory->name
            ?? 'General Fee';

        if (!isset($cleanSummary[$category])) {

            $cleanSummary[$category] = [
                'billed' => 0,
                'received' => 0,
            ];
        }

        $cleanSummary[$category]['received'] +=
            $allocation->amount;
    }
}

$totalBilled = collect($cleanSummary)->sum('billed');
$totalReceived = collect($cleanSummary)->sum('received');
$totalBalance = $totalBilled - $totalReceived;

?>

<div class="container-fluid py-4 income-report-page">

    
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                Fee Category Income Report
            </h3>

            <p class="text-muted mb-0">
                Summary of billed, received, and outstanding balances
            </p>

        </div>

        <div class="text-end">

            <div class="fw-bold text-success fs-4">
                Received:
                <?php echo e(number_format($totalReceived, 2)); ?>

            </div>

            <div class="fw-bold text-danger">
                Balance:
                <?php echo e(number_format($totalBalance, 2)); ?>

            </div>

        </div>

    </div>

    
    <div class="row mb-4">

        <div class="col-md-4 mb-3">

            <div class="card stat-card border-0 shadow-sm">

                <div class="card-body">

                    <div class="stat-title">
                        TOTAL BILLED
                    </div>

                    <div class="stat-value text-primary">
                        <?php echo e(number_format($totalBilled, 2)); ?>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-4 mb-3">

            <div class="card stat-card border-0 shadow-sm">

                <div class="card-body">

                    <div class="stat-title">
                        TOTAL RECEIVED
                    </div>

                    <div class="stat-value text-success">
                        <?php echo e(number_format($totalReceived, 2)); ?>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-4 mb-3">

            <div class="card stat-card border-0 shadow-sm">

                <div class="card-body">

                    <div class="stat-title">
                        TOTAL BALANCE
                    </div>

                    <div class="stat-value text-danger">
                        <?php echo e(number_format($totalBalance, 2)); ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

    
    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark">

                        <tr>

                            <th>#</th>
                            <th>Fee Category</th>
                            <th>Total Billed</th>
                            <th>Total Received</th>
                            <th>Balance</th>
                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php $__empty_1 = true; $__currentLoopData = $cleanSummary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                            <?php

                                $balance =
                                    $data['billed'] - $data['received'];

                            ?>

                            <tr>

                                <td>
                                    <?php echo e($loop->iteration); ?>

                                </td>

                                <td>

                                    <span class="badge bg-primary px-3 py-2">

                                        <?php echo e($category); ?>


                                    </span>

                                </td>

                                <td class="fw-bold text-dark">

                                    <?php echo e(number_format($data['billed'], 2)); ?>


                                </td>

                                <td class="fw-bold text-success">

                                    <?php echo e(number_format($data['received'], 2)); ?>


                                </td>

                                <td class="fw-bold text-danger">

                                    <?php echo e(number_format($balance, 2)); ?>


                                </td>

                                <td>

                                    <?php if($balance <= 0): ?>

                                        <span class="badge bg-success px-3 py-2">
                                            Fully Paid
                                        </span>

                                    <?php elseif($data['received'] > 0): ?>

                                        <span class="badge bg-warning text-dark px-3 py-2">
                                            Partial
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-danger px-3 py-2">
                                            Unpaid
                                        </span>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                            <tr>

                                <td colspan="6"
                                    class="text-center text-muted py-5">

                                    No fee category income records found.

                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                    
                    <tfoot class="table-light">

                        <tr>

                            <th colspan="2" class="text-end">
                                GRAND TOTAL
                            </th>

                            <th class="text-primary">

                                <?php echo e(number_format($totalBilled, 2)); ?>


                            </th>

                            <th class="text-success">

                                <?php echo e(number_format($totalReceived, 2)); ?>


                            </th>

                            <th class="text-danger">

                                <?php echo e(number_format($totalBalance, 2)); ?>


                            </th>

                            <th></th>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>

    </div>

</div>


<style>

.income-report-page{
    background:#f4f7fb;
    min-height:100vh;
}

.card{
    border-radius:18px;
    overflow:hidden;
}

.table thead th{
    font-size:13px;
    letter-spacing:.5px;
    white-space:nowrap;
}

.table tbody td{
    font-size:14px;
    vertical-align:middle;
}

.badge{
    border-radius:10px;
    font-size:12px;
}

.stat-card{
    border-radius:18px;
}

.stat-title{
    font-size:13px;
    font-weight:700;
    color:#6b7280;
    margin-bottom:10px;
}

.stat-value{
    font-size:28px;
    font-weight:800;
}

</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/finance/income.blade.php ENDPATH**/ ?>