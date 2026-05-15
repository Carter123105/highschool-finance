<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Classes</h4>

        <a href="<?php echo e(route('classes.create')); ?>" class="btn btn-primary">
            + Add Class
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover">

                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Class Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>

                            
                            <td><?php echo e($class->name); ?></td>

                            <td class="d-flex gap-2">

                                <a href="<?php echo e(route('classes.edit', $class->id)); ?>"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <form action="<?php echo e(route('classes.destroy', $class->id)); ?>"
                                      method="POST"
                                      onsubmit="return confirm('Delete this class?')">

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
                            <td colspan="3" class="text-center text-muted">
                                No Classes Found
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/classes/index.blade.php ENDPATH**/ ?>