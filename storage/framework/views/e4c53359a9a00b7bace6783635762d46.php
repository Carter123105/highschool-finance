<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    <div class="card shadow-sm border-0">

        
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Create Fee Category</h4>

            <a href="<?php echo e(route('fee-categories.index')); ?>" class="btn btn-light btn-sm">
                Back
            </a>
        </div>

        
        <div class="card-body">

            <form action="<?php echo e(route('fee-categories.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="row">

                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            Category Name <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="Enter fee category name"
                            value="<?php echo e(old('name')); ?>"
                            required
                        >

                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <small class="text-danger"><?php echo e($message); ?></small>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold d-block">
                            Billing Type
                        </label>

                        <div class="form-check mt-2">

                            <input
                                type="checkbox"
                                name="is_monthly"
                                value="1"
                                class="form-check-input"
                                id="monthlyCheck"
                                <?php echo e(old('is_monthly') ? 'checked' : ''); ?>

                            >

                            <label class="form-check-label" for="monthlyCheck">
                                This is a monthly fee
                            </label>

                        </div>

                    </div>

                    
                    <div class="col-md-12 mb-4">

                        <label class="form-label fw-bold">
                            Description / Note
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            class="form-control"
                            placeholder="Enter description or note..."
                        ><?php echo e(old('description')); ?></textarea>

                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <small class="text-danger"><?php echo e($message); ?></small>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>

                </div>

                
                <div class="d-flex gap-2">

                    <button type="submit" class="btn btn-success">
                        Save Fee Category
                    </button>

                    <a href="<?php echo e(route('fee-categories.index')); ?>" class="btn btn-secondary">
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/fee_categories/create.blade.php ENDPATH**/ ?>