<?php $__env->startSection('content'); ?>

<div class="container">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Edit Payment</h5>
        </div>

        <div class="card-body">

            
            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php
                $invoice = $payment->invoice;

                $invoiceTotal = floatval($invoice->total_amount ?? 0);

                $otherPayments = $invoice->payments->where('id', '!=', $payment->id);
                $otherPaid = floatval($otherPayments->sum('amount_paid'));

                $currentPayment = floatval($payment->amount_paid);

                $maxAllowed = max(0, $invoiceTotal - $otherPaid);

                $currentBalance = max(0, $invoiceTotal - ($otherPaid + $currentPayment));
            ?>

            <form action="<?php echo e(route('payments.update', $payment->id)); ?>" method="POST">

                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                
                <input type="hidden"
                       name="payment_date"
                       value="<?php echo e($payment->payment_date ?? now()->format('Y-m-d')); ?>">

                
                <input type="hidden"
                       name="invoice_id"
                       value="<?php echo e($payment->invoice_id); ?>">

                
                <div class="mb-3">
                    <label class="form-label fw-bold">Student</label>
                    <input type="text"
                           class="form-control"
                           value="<?php echo e($payment->student->first_name ?? ''); ?> <?php echo e($payment->student->last_name ?? ''); ?>"
                           disabled>
                </div>

                
                <div class="mb-3">
                    <label class="form-label fw-bold">Invoice No</label>
                    <input type="text"
                           class="form-control"
                           value="<?php echo e($invoice->invoice_no ?? ''); ?>"
                           disabled>
                </div>

                
                <div class="mb-3">
                    <label class="form-label fw-bold">Total Invoice Amount</label>
                    <input type="text"
                           class="form-control bg-light fw-bold"
                           value="<?php echo e(number_format($invoiceTotal, 2)); ?>"
                           disabled>
                </div>

                
                <div class="mb-3">
                    <label class="form-label fw-bold">Other Payments Already Made</label>
                    <input type="text"
                           class="form-control bg-light"
                           value="<?php echo e(number_format($otherPaid, 2)); ?>"
                           disabled>
                </div>

                
                <div class="mb-3">
                    <label class="form-label fw-bold text-primary">Current Payment Amount</label>
                    <input type="text"
                           class="form-control bg-light text-primary fw-bold"
                           value="<?php echo e(number_format($currentPayment, 2)); ?>"
                           disabled>
                </div>

                
                <div class="mb-3">
                    <label class="form-label fw-bold text-danger">Current Invoice Balance</label>
                    <input type="text"
                           class="form-control bg-light text-danger fw-bold"
                           value="<?php echo e(number_format($currentBalance, 2)); ?>"
                           disabled>
                </div>

                
                <div class="mb-3">
                    <label class="form-label fw-bold">Edit Amount Paid</label>

                    <input type="number"
                           step="0.01"
                           min="0.01"
                           max="<?php echo e($maxAllowed); ?>"
                           name="amount_paid"
                           value="<?php echo e(old('amount_paid', $payment->amount_paid)); ?>"
                           class="form-control form-control-lg"
                           required>

                    <small class="text-muted d-block mt-2">
                        Maximum allowed amount:
                        <strong><?php echo e(number_format($maxAllowed, 2)); ?></strong>
                    </small>

                    <small class="text-danger d-block">
                        You cannot exceed the invoice total.
                    </small>
                </div>

                
                <div class="mb-3">
                    <label class="form-label fw-bold">Payment Method</label>

                    <select name="payment_method" class="form-select" required>
                        <option value="Cash" <?php if($payment->payment_method == 'Cash'): echo 'selected'; endif; ?>>Cash</option>
                        <option value="Bank" <?php if($payment->payment_method == 'Bank'): echo 'selected'; endif; ?>>Bank</option>
                        <option value="Mobile Money" <?php if($payment->payment_method == 'Mobile Money'): echo 'selected'; endif; ?>>Mobile Money</option>
                    </select>
                </div>

                
                <div class="mb-3">
                    <label class="form-label fw-bold">Invoice Fee Breakdown</label>

                    <div class="border rounded p-3 bg-light">
                        <?php $__currentLoopData = $payment->invoice->invoiceItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <div><?php echo e($item->feeCategory->name ?? 'Unknown Fee'); ?></div>
                                <div class="fw-bold"><?php echo e(number_format($item->subtotal, 2)); ?></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                
                <button class="btn btn-success w-100 py-2">
                    Update Payment
                </button>

            </form>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/payments/edit.blade.php ENDPATH**/ ?>