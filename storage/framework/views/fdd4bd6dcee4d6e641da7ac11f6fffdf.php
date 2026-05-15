

<?php $__env->startSection('content'); ?>
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Student Invoice</h3>

        <button onclick="window.print()" class="btn btn-dark">
            Print Invoice
        </button>
    </div>

    <div class="card p-4">

        
        <div class="mb-3">
            <h5>
                <?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?>

            </h5>

            <p class="mb-0">
                Class: <b><?php echo e($invoice->schoolClass->name); ?></b><br>
                Type: <b><?php echo e($student->student_type); ?></b><br>
                Invoice: <b>#<?php echo e($invoice->invoice_no); ?></b>
            </p>
        </div>

        <hr>

        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Fee Category</th>
                    <th class="text-end">Amount</th>
                </tr>
            </thead>

            <tbody>
                <?php $__currentLoopData = $invoice->invoiceItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($item->feeCategory->name); ?></td>
                        <td class="text-end"><?php echo e(number_format($item->amount, 2)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>

            <tfoot>
                <tr>
                    <th>Total</th>
                    <th class="text-end"><?php echo e(number_format($invoice->total_amount, 2)); ?></th>
                </tr>
            </tfoot>
        </table>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/finance/student-invoice.blade.php ENDPATH**/ ?>