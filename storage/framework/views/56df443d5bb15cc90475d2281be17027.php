<?php $__env->startSection('content'); ?>
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Fee Category</h4>
                </div>

                <div class="card-body">

                    

                    
                    <?php
                        $category = null;
                        
                        // Check all possible variable names
                        if (isset($fee_category) && is_object($fee_category)) {
                            $category = $fee_category;
                        } elseif (isset($feecategory) && is_object($feecategory)) {
                            $category = $feecategory;
                        } elseif (isset($feeCategory) && is_object($feeCategory)) {
                            $category = $feeCategory;
                        } elseif (isset($category) && is_object($category)) {
                            $category = $category;
                        }
                    ?>

                    <?php if(!$category || !is_object($category)): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Error:</strong> Fee category not loaded. 
                            Variable received: <?php echo e(isset($fee_category) ? gettype($fee_category) : 'none'); ?>

                            <br>
                            <a href="<?php echo e(route('fee-categories.index')); ?>" class="btn btn-sm btn-outline-danger mt-2">
                                <i class="bi bi-arrow-left"></i> Go Back
                            </a>
                        </div>
                    <?php else: ?>

                    <form action="<?php echo e(route('fee-categories.update', $category->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="name"
                                   class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('name', $category->name)); ?>"
                                   required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" 
                                      class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                      rows="3"><?php echo e(old('description', $category->description)); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Type <span class="text-danger">*</span></label>
                            <select name="type" 
                                    class="form-control <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    required>
                                <option value="Mandatory" <?php echo e((old('type', $category->type) == 'Mandatory') ? 'selected' : ''); ?>>Mandatory</option>
                                <option value="Optional" <?php echo e((old('type', $category->type) == 'Optional') ? 'selected' : ''); ?>>Optional</option>
                            </select>
                            <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="form-check mb-3">
                            <input type="hidden" name="is_monthly" value="0">
                            <input type="checkbox" 
                                   name="is_monthly"
                                   class="form-check-input"
                                   value="1"
                                   <?php echo e(old('is_monthly', $category->is_monthly) ? 'checked' : ''); ?>>
                            <label class="form-check-label fw-bold">Monthly Fee</label>
                        </div>

                        
                        <div class="form-check mb-3">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" 
                                   name="is_active"
                                   class="form-check-input"
                                   value="1"
                                   <?php echo e(old('is_active', $category->is_active) ? 'checked' : ''); ?>>
                            <label class="form-check-label fw-bold">Active</label>
                        </div>

                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update
                            </button>

                            <a href="<?php echo e(route('fee-categories.index')); ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>

                    </form>

                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/fee_categories/edit.blade.php ENDPATH**/ ?>