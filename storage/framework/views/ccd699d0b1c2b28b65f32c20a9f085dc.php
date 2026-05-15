<?php $__env->startSection('content'); ?>
<div class="container">
    <h3 class="mb-3">Students Finance View</h3>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Total Paid</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            
                            <td>
                                <?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?>

                            </td>

                            
                            <td>
                                <?php echo e($student->schoolClass->name ?? 'N/A'); ?>

                            </td>

                            
                            <td>
                                <?php echo e(number_format($student->payments_sum_amount_paid ?? 0)); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>

            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/finance/students.blade.php ENDPATH**/ ?>