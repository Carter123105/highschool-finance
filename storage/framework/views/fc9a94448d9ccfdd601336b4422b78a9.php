<?php $__env->startSection('content'); ?>

<div class="container-fluid py-4 expense-page">

    
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

        <div>
            <h2 class="fw-bold mb-1">Expenses</h2>
            <p class="text-muted mb-0">Manage all school expenses</p>
        </div>

        <div class="d-flex gap-2 flex-wrap">

            
            <input type="text"
                   id="searchExpense"
                   class="form-control"
                   placeholder="Search expense...">

            
            <a href="<?php echo e(route('expenses.create')); ?>"
               class="btn btn-danger d-flex align-items-center gap-2">

                <i class="bi bi-plus-circle"></i>
                Add Expense

            </a>

        </div>

    </div>

    
    <div class="card shadow-sm border-0">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0" id="expenseTable">

                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Added By</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php $__empty_1 = true; $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                            <tr class="expense-row">

                                <td><?php echo e($expense->id); ?></td>

                                <td class="fw-semibold">
                                    <?php echo e($expense->title); ?>

                                </td>

                                <td>
                                    <span class="badge bg-primary">
                                        <?php echo e($expense->category ?? 'N/A'); ?>

                                    </span>
                                </td>

                                <td class="text-danger fw-bold">
                                    $<?php echo e(number_format($expense->amount, 2)); ?>

                                </td>

                                <td>
                                    <?php echo e($expense->expense_date ? \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') : '—'); ?>

                                </td>

                                <td>
                                    <?php echo e($expense->user->name ?? 'System'); ?>

                                </td>

                                <td class="text-end">

                                    <a href="<?php echo e(route('expenses.edit', $expense->id)); ?>"
                                       class="btn btn-sm btn-primary">

                                        <i class="bi bi-pencil"></i>

                                    </a>

                                    <form action="<?php echo e(route('expenses.destroy', $expense->id)); ?>"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Delete this expense?')">

                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>

                                        <button class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    No expenses found.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<style>

.expense-page{
    background:#f4f7fb;
    min-height:100vh;
}

.table thead th{
    font-size:13px;
    letter-spacing:.5px;
}

.table tbody td{
    font-size:14px;
}

.btn-danger{
    border-radius:10px;
}

.btn-primary{
    border-radius:10px;
}

.card{
    border-radius:16px;
    overflow:hidden;
}

input#searchExpense{
    min-width:220px;
    border-radius:12px;
    padding:10px 14px;
    border:1px solid #e5e7eb;
}

</style>


<script>

document.getElementById("searchExpense").addEventListener("input", function () {

    let value = this.value.toLowerCase();

    document.querySelectorAll(".expense-row").forEach(row => {

        row.style.display = row.innerText.toLowerCase().includes(value)
            ? ""
            : "none";

    });

});

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/expenses/index.blade.php ENDPATH**/ ?>