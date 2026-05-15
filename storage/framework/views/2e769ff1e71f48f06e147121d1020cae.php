<?php $__env->startSection('content'); ?>
<div class="container">
    <h3 class="mb-3">Invoices Breakdown</h3>

    <div class="card">
        <div class="card-body">

            <?php $__empty_1 = true; $__currentLoopData = $groupedInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classId => $invoices): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                
                <div class="alert alert-secondary fw-bold mb-3">
                    Class: <?php echo e($invoices->first()->schoolClass->name ?? 'N/A'); ?>

                </div>

                
                <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <div class="border rounded p-3 mb-4">

                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>#<?php echo e($invoice->invoice_no); ?></strong>
                            </div>

                            <span class="badge bg-info">
                                <?php echo e($invoice->status); ?>

                            </span>
                        </div>

                        <hr>

                        
                        <form method="GET"
                              action="<?php echo e(url('/finance/invoice/student/'.$invoice->id)); ?>"
                              class="row g-2 align-items-end mb-3">

                            <div class="col-md-8">
                                <label class="form-label">Select Student</label>

                                <select name="student_id" class="form-select" required>
                                    <option value="">-- Choose Student --</option>

                                    <?php $__currentLoopData = $studentsByClass[$classId] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($student->id); ?>">
                                            <?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <button class="btn btn-primary w-100">
                                    View Invoice
                                </button>
                            </div>
                        </form>

                        
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Fee Category</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $__currentLoopData = $invoice->invoiceItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($item->feeCategory->name ?? 'Unknown'); ?></td>
                                        <td class="text-end"><?php echo e(number_format($item->amount, 2)); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>

                            <tfoot>
                                <tr class="fw-bold">
                                    <td>Total</td>
                                    <td class="text-end">
                                        <?php echo e(number_format($invoice->total_amount, 2)); ?>

                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                    </div>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center text-muted">
                    No invoices found
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/finance/invoices.blade.php ENDPATH**/ ?>