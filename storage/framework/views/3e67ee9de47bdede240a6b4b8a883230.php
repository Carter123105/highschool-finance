

<?php $__env->startSection('content'); ?>

<div class="container-fluid py-4 expense-show-page">

    
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

        <div>
            <h2 class="fw-bold mb-1">Expense Details</h2>
            <p class="text-muted mb-0">
                View complete expense information
            </p>
        </div>

        <div class="d-flex gap-2 flex-wrap">

            
            <a href="<?php echo e(route('expenses.index')); ?>"
               class="btn btn-secondary d-flex align-items-center gap-2">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>

            
            <a href="<?php echo e(route('expenses.edit', $expense->id)); ?>"
               class="btn btn-primary d-flex align-items-center gap-2">

                <i class="bi bi-pencil-square"></i>
                Edit

            </a>

            
            <form action="<?php echo e(route('expenses.destroy', $expense->id)); ?>"
                  method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this expense?')">

                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>

                <button type="submit"
                        class="btn btn-danger d-flex align-items-center gap-2">

                    <i class="bi bi-trash"></i>
                    Delete

                </button>

            </form>

        </div>

    </div>

    
    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            <div class="row g-4">

                
                <div class="col-md-6">

                    <div class="detail-box">

                        <label class="detail-label">
                            Expense Title
                        </label>

                        <div class="detail-value">
                            <?php echo e($expense->title); ?>

                        </div>

                    </div>

                </div>

                
                <div class="col-md-6">

                    <div class="detail-box">

                        <label class="detail-label">
                            Category
                        </label>

                        <div class="detail-value">

                            <span class="badge bg-primary px-3 py-2">
                                <?php echo e($expense->category ?? 'N/A'); ?>

                            </span>

                        </div>

                    </div>

                </div>

                
                <div class="col-md-6">

                    <div class="detail-box">

                        <label class="detail-label">
                            Amount
                        </label>

                        <div class="detail-value text-danger fw-bold fs-4">
                            $<?php echo e(number_format($expense->amount, 2)); ?>

                        </div>

                    </div>

                </div>

                
                <div class="col-md-6">

                    <div class="detail-box">

                        <label class="detail-label">
                            Expense Date
                        </label>

                        <div class="detail-value">

                            <?php echo e($expense->expense_date
                                ? \Carbon\Carbon::parse($expense->expense_date)->format('d M Y')
                                : 'N/A'); ?>


                        </div>

                    </div>

                </div>

                
                <div class="col-md-6">

                    <div class="detail-box">

                        <label class="detail-label">
                            Added By
                        </label>

                        <div class="detail-value">
                            <?php echo e($expense->user->name ?? 'System'); ?>

                        </div>

                    </div>

                </div>

                
                <div class="col-md-6">

                    <div class="detail-box">

                        <label class="detail-label">
                            Created At
                        </label>

                        <div class="detail-value">
                            <?php echo e($expense->created_at->format('d M Y h:i A')); ?>

                        </div>

                    </div>

                </div>

                
                <div class="col-12">

                    <div class="detail-box">

                        <label class="detail-label">
                            Description
                        </label>

                        <div class="detail-description">

                            <?php echo e($expense->description ?? 'No description available.'); ?>


                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<style>

.expense-show-page{
    background:#f4f7fb;
    min-height:100vh;
}

.card{
    border-radius:20px;
    overflow:hidden;
}

.detail-box{
    background:#fff;
    border:1px solid #edf2f7;
    border-radius:16px;
    padding:20px;
    height:100%;
}

.detail-label{
    display:block;
    font-size:13px;
    font-weight:700;
    color:#6b7280;
    margin-bottom:10px;
    text-transform:uppercase;
    letter-spacing:.5px;
}

.detail-value{
    font-size:16px;
    font-weight:600;
    color:#111827;
}

.detail-description{
    background:#f9fafb;
    border-radius:14px;
    padding:18px;
    line-height:1.8;
    color:#374151;
}

.btn{
    border-radius:12px;
    padding:10px 18px;
    font-weight:600;
}

</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/expenses/show.blade.php ENDPATH**/ ?>