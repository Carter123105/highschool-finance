<?php $__env->startSection('content'); ?>

<div class="container-fluid py-4">

    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Record Payment</h3>
            <p class="text-muted mb-0">Record payments for Old and New student invoices</p>
        </div>
        <a href="<?php echo e(route('payments.index')); ?>" class="btn btn-dark">← Back</a>
    </div>

    
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    
    <form method="GET" action="<?php echo e(route('payments.create')); ?>" class="mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white fw-bold">Filter Students & Invoices</div>
            <div class="card-body">
                <div class="row g-3">
                    
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Select Class</label>
                        <select name="class_id" class="form-select" onchange="this.form.submit()" required>
                            <option value="">Select Class</option>
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>" <?php echo e($selectedClass == $class->id ? 'selected' : ''); ?>>
                                    <?php echo e($class->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Student Type</label>
                        <select name="student_type" class="form-select" onchange="this.form.submit()" required>
                            <option value="">Select Type</option>
                            <option value="Old" <?php echo e($selectedType == 'Old' ? 'selected' : ''); ?>>Old Students</option>
                            <option value="New" <?php echo e($selectedType == 'New' ? 'selected' : ''); ?>>New Students</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>

    
    <form method="POST" action="<?php echo e(route('payments.store')); ?>">
        <?php echo csrf_field(); ?>

        <input type="hidden" name="class_id" value="<?php echo e($selectedClass); ?>">
        <input type="hidden" name="student_type" value="<?php echo e($selectedType); ?>">

        <div class="row g-4">

            
            <div class="col-lg-5">

                
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-dark text-white fw-bold">Student Information</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Select Student</label>
                            <select name="student_id" id="student_id" class="form-select" required>
                                <option value="">Select Student</option>
                                <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <option value="<?php echo e($student->id); ?>">
                                        <?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?>

                                        (<?php echo e($student->student_type); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <option disabled>No students found</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="alert alert-info mb-0">
                            <strong>Student Type:</strong> <?php echo e($selectedType ?: 'Not Selected'); ?>

                        </div>
                    </div>
                </div>

                
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white fw-bold">Invoice Selection</div>
                    <div class="card-body">
                        <label class="form-label fw-semibold">Select Invoice</label>
                        <select name="invoice_id" id="invoice_id" class="form-select" required>
                            <option value="">Select Invoice</option>
                            <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <option value="<?php echo e($invoice->id); ?>" data-total="<?php echo e($invoice->balance); ?>">
                                    <?php echo e($invoice->invoice_no); ?> - <?php echo e(number_format($invoice->balance, 2)); ?>

                                    (<?php echo e($invoice->student_type); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <option disabled>No unpaid invoices found</option>
                            <?php endif; ?>
                        </select>

                        
                        <div class="mt-3">
                            <div class="border rounded p-3 bg-light">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-semibold">Invoice Type</span>
                                    <span id="invoiceTypeText" class="fw-bold text-primary">--</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semibold">Remaining Balance</span>
                                    <span id="balanceText" class="fw-bold text-danger">--</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            
            <div class="col-lg-7">

                
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-info text-white fw-bold">Invoice Details</div>
                    <div class="card-body">
                        <div class="alert alert-warning mb-0">
                            <strong>Important:</strong> Make sure you are paying the correct invoice for this student.
                        </div>
                    </div>
                </div>

                
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-secondary text-white fw-bold">Payment Information</div>
                    <div class="card-body">
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Amount Paid</label>
                            <input type="number" step="0.01" min="0" name="amount_paid" id="amount_paid" class="form-control" required>
                            <small id="overpayWarning" class="text-danger d-none">⚠ Amount exceeds remaining balance</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Payment Date</label>
                            <input type="date" name="payment_date" value="<?php echo e(date('Y-m-d')); ?>" class="form-control" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Payment Method</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="Cash">Cash</option>
                                <option value="Bank">Bank</option>
                                <option value="Mobile Money">Mobile Money</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Save Payment</button>
                    </div>
                </div>

            </div>

        </div>

    </form>

</div>


<script>
let remainingBalance = 0;

function updateBalance() {
    let invoice = document.getElementById('invoice_id');
    let selected = invoice.options[invoice.selectedIndex];
    let total = selected.getAttribute('data-total');
    let typeText = selected.text.includes('Old') ? 'Old' : (selected.text.includes('New') ? 'New' : '--');

    if (!total) {
        document.getElementById('balanceText').innerText = '--';
        document.getElementById('invoiceTypeText').innerText = '--';
        return;
    }

    remainingBalance = parseFloat(total);
    document.getElementById('balanceText').innerText = 'LRD ' + remainingBalance.toFixed(2);
    document.getElementById('invoiceTypeText').innerText = typeText;
}

document.getElementById('invoice_id').addEventListener('change', updateBalance);

document.getElementById('amount_paid').addEventListener('input', function () {
    let value = parseFloat(this.value || 0);
    let warning = document.getElementById('overpayWarning');
    warning.classList.toggle('d-none', value <= remainingBalance);
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/payments/create.blade.php ENDPATH**/ ?>