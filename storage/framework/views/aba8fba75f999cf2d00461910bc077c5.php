<?php $__env->startSection('content'); ?>

<div class="container-fluid py-4 expense-create-page">

    
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

        <div>
            <h2 class="fw-bold mb-1 text-dark">Create Expense</h2>
            <p class="text-muted mb-0">Add a new school expense record</p>
        </div>

        <a href="<?php echo e(route('expenses.index')); ?>" class="btn btn-outline-dark btn-sm">
            <i class="bi bi-arrow-left"></i> Back
        </a>

    </div>

    
    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            
            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            
            <form action="<?php echo e(route('expenses.store')); ?>" method="POST">

                <?php echo csrf_field(); ?>

                <div class="row g-3">

                    
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Expense Title</label>
                        <input type="text"
                               name="title"
                               class="form-control"
                               placeholder="e.g. Teacher Salary"
                               required>
                    </div>

                    
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Amount</label>
                        <input type="number"
                               step="0.01"
                               name="amount"
                               class="form-control"
                               placeholder="0.00"
                               required>
                    </div>

                    
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Category</label>
                        <select name="category" class="form-select">

                            <option value="">-- Select Category --</option>
                            <option value="Salary">Salary</option>
                            <option value="Transport">Transport</option>
                            <option value="Maintenance">Maintenance</option>
                            <option value="Utility">Utility</option>
                            <option value="Office">Office Supplies</option>
                            <option value="Other">Other</option>

                        </select>
                    </div>

                    
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Expense Date</label>
                        <input type="date"
                               name="expense_date"
                               class="form-control">
                    </div>

                    
                    <div class="col-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description"
                                  class="form-control"
                                  rows="4"
                                  placeholder="Optional details about this expense"></textarea>
                    </div>

                    
                    <div class="col-12 d-flex justify-content-end mt-3">

                        <button type="submit" class="btn btn-primary px-4">

                            <i class="bi bi-save me-1"></i>
                            Save Expense

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>


<style>

.expense-create-page{
    background:#f4f7fb;
    min-height:100vh;
}

.card{
    border-radius:16px;
}

.form-label{
    font-size:14px;
    color:#334155;
}

.form-control, .form-select{
    border-radius:10px;
    padding:10px 12px;
    border:1px solid #e2e8f0;
    font-size:14px;
}

.form-control:focus, .form-select:focus{
    box-shadow:none;
    border-color:#2563eb;
}

.btn-primary{
    border-radius:10px;
    font-weight:600;
}

</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/expenses/create.blade.php ENDPATH**/ ?>