<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    <div class="card">

        <div class="card-header d-flex justify-content-between">
            <h4>Academic Years</h4>

            <a href="<?php echo e(route('academic-years.create')); ?>" class="btn btn-primary">
                + Add Year
            </a>
        </div>

        <div class="card-body">

            <?php if(session('success')): ?>
                <div class="alert alert-success">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <table class="table table-bordered">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Academic Year</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>

                            <td><?php echo e($year->name); ?></td>

                            <td><?php echo e($year->start_date); ?></td>

                            <td><?php echo e($year->end_date); ?></td>

                            <td>
                                <span class="badge bg-<?php echo e($year->is_active ? 'success' : 'secondary'); ?>">
                                    <?php echo e($year->is_active ? 'Active' : 'Inactive'); ?>

                                </span>
                            </td>

                            <td class="d-flex gap-2">

                                <a href="<?php echo e(route('academic-years.edit', $year->id)); ?>"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form action="<?php echo e(route('academic-years.destroy', $year->id)); ?>"
                                      method="POST">

                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>

                                    <button class="btn btn-danger btn-sm">
                                        Delete
                                    </button>

                                </form>

                            </td>
                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <tr>
                            <td colspan="6" class="text-center">
                                No Academic Years Found
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/academic_years/index.blade.php ENDPATH**/ ?>