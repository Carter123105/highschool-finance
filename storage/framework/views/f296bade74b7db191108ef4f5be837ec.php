<?php $__env->startSection('content'); ?>
<div class="container">
    <h3 class="mb-3">Classes</h3>

    <div class="card">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Class Name</th>
                        <th>Description</th>
                        <th>Total Students</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($class->id); ?></td>
                            <td><?php echo e($class->name); ?></td>
                            <td><?php echo e($class->description ?? 'N/A'); ?></td>
                            <td>
                                <span class="badge bg-primary"><?php echo e($class->students_count ?? 0); ?></span>
                            </td>
                            <td><?php echo e($class->created_at?->format('Y-m-d') ?? 'N/A'); ?></td>
                            <td>
                                <a href="<?php echo e(route('finance.classes.students', $class->id)); ?>" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> View Students
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                No classes found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/finance/classes.blade.php ENDPATH**/ ?>