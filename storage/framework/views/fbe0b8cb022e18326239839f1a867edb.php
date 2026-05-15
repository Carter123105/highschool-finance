<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    <div class="card">

        <div class="card-header">
            <h4>Edit Section</h4>
        </div>

        <div class="card-body">

            <form action="<?php echo e(route('sections.update', $section->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                
                <div class="mb-3">
                    <label>Section Name</label>
                    <input type="text"
                           name="name"
                           value="<?php echo e(old('name', $section->name)); ?>"
                           class="form-control"
                           required>
                </div>

                
                <div class="mb-3">
                    <label>Class</label>
                    <select name="class_id" class="form-control" required>

                        <option value="">-- Select Class --</option>

                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($class->id); ?>"
                                <?php echo e(old('class_id', $section->class_id) == $class->id ? 'selected' : ''); ?>>
                                <?php echo e($class->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </select>
                </div>

                
                <div class="mb-3">
                    <label>Capacity</label>
                    <input type="number"
                           name="capacity"
                           value="<?php echo e(old('capacity', $section->capacity)); ?>"
                           class="form-control">
                </div>

                <button class="btn btn-primary">
                    Update
                </button>

            </form>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/sections/edit.blade.php ENDPATH**/ ?>