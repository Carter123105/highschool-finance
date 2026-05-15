<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Fee Categories</h4>

        <a href="<?php echo e(route('fee-categories.create')); ?>" class="btn btn-primary">
            + Create Fee Category
        </a>
    </div>

    
    <div class="card shadow-sm">

        <div class="card-header bg-dark text-white d-flex justify-content-between">
            <span>All Fee Categories</span>
            <span>Total: <?php echo e($feeCategories->total()); ?></span>
        </div>

        <div class="card-body table-responsive">

            <table class="table table-hover table-striped align-middle">

                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Monthly</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th width="180">Action</th>
                    </tr>
                </thead>

                <tbody>

                <?php $__empty_1 = true; $__currentLoopData = $feeCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <tr>
                        <td>
                            <?php echo e($feeCategories->firstItem() + $key); ?>

                        </td>

                        
                        <td class="fw-semibold">
                            <?php echo e($category->name); ?>

                        </td>

                        
                        <td>
                            <span class="badge bg-info text-dark">
                                <?php echo e($category->type); ?>

                            </span>
                        </td>

                        
                        <td>
                            <?php echo e($category->description ?? '-'); ?>

                        </td>

                        
                        <td>
                            <?php if($category->is_monthly): ?>
                                <span class="badge bg-primary">Monthly</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">One-Time</span>
                            <?php endif; ?>
                        </td>

                        
                        <td>
                            <?php if($category->is_active): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactive</span>
                            <?php endif; ?>
                        </td>

                        
                        <td>
                            <?php echo e($category->created_at?->format('d M Y')); ?>

                            <br>
                            <small class="text-muted">
                                <?php echo e($category->created_at?->diffForHumans()); ?>

                            </small>
                        </td>

                        
                        <td class="d-flex gap-1">

                            <a href="<?php echo e(route('fee-categories.edit', $category->id)); ?>"
                               class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <form action="<?php echo e(route('fee-categories.destroy', $category->id)); ?>"
                                  method="POST"
                                  onsubmit="return confirm('Delete this category?')">

                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>

                                <button class="btn btn-sm btn-danger">
                                    Delete
                                </button>

                            </form>

                        </td>
                    </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            No fee categories found
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

            
            <div class="mt-3">
                <?php echo e($feeCategories->links()); ?>

            </div>

        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/fee_categories/index.blade.php ENDPATH**/ ?>