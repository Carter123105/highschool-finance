<?php $__env->startSection('content'); ?>
<div class="container">
    <h3 class="mb-3">Students Finance View</h3>

    
    <div class="card mb-4">
        <div class="card-header bg-light">
            <strong>Filter Students</strong>
        </div>
        <div class="card-body">
            <form action="<?php echo e(url()->current()); ?>" method="GET" class="row align-items-end">
                
                
                <div class="col-md-4 mb-3 mb-md-0">
                    <label for="class_id" class="form-label text-muted small uppercase font-weight-bold">Class</label>
                    <select name="class_id" id="class_id" class="form-control custom-select">
                        <option value="">All Classes</option>
                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($class->id); ?>" <?php echo e(request('class_id') == $class->id ? 'selected' : ''); ?>>
                                <?php echo e($class->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div class="col-md-4 mb-3 mb-md-0">
                    <label for="section_id" class="form-label text-muted small uppercase font-weight-bold">Section</label>
                    <select name="section_id" id="section_id" class="form-control custom-select">
                        <option value="">All Sections</option>
                        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($section->id); ?>" <?php echo e(request('section_id') == $section->id ? 'selected' : ''); ?>>
                                <?php echo e($section->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div class="col-md-4">
                    <div class="d-flex">
                        <button type="submit" class="btn btn-primary flex-grow-1 mr-2">
                            Apply Filter
                        </button>
                        <a href="<?php echo e(url()->current()); ?>" class="btn btn-outline-secondary">
                            Reset
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </div>

    
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
                    <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                No students found matching the selected filters.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/finance/students.blade.php ENDPATH**/ ?>