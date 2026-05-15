<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    <div class="card shadow-sm border-0">

        
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Create Section</h4>
        </div>

        
        <div class="card-body">

            <form action="<?php echo e(route('sections.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="row">

                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Section Name</label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="e.g. A, B, Science"
                            required
                        >
                    </div>

                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Class</label>

                        <select name="class_id" class="form-control" required>
                            <option value="">-- Select Class --</option>

                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>">
                                    <?php echo e($class->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </select>
                    </div>

                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            Capacity
                        </label>

                        <input
                            type="number"
                            name="capacity"
                            class="form-control"
                            placeholder="e.g. 40"
                            value="0"
                        >
                    </div>

                </div>

                
                <div class="mt-3">
                    <button class="btn btn-success">
                        Save Section
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/sections/create.blade.php ENDPATH**/ ?>