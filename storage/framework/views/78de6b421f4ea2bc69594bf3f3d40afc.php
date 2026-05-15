<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    <div class="card">

        <div class="card-header d-flex justify-content-between">
            <h4>Sections</h4>

            <a href="<?php echo e(route('sections.create')); ?>" class="btn btn-primary">
                + Add Section
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
                        <th>Section Name</th>
                        <th>Class</th>
                        <th>Capacity</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($section->name); ?></td>

                            
                            <td><?php echo e($section->schoolClass->name ?? 'N/A'); ?></td>

                            <td><?php echo e($section->capacity ?? 'N/A'); ?></td>

                            <td class="d-flex gap-2">

                                <a href="<?php echo e(route('sections.edit', $section->id)); ?>"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form action="<?php echo e(route('sections.destroy', $section->id)); ?>"
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
                            <td colspan="5" class="text-center">
                                No Sections Found
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/sections/index.blade.php ENDPATH**/ ?>