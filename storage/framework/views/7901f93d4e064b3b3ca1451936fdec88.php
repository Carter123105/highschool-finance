<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    <div class="card shadow-sm border-0">

        
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">
                <i class="bi bi-person-plus-fill"></i>
                Create New User
            </h4>

            <a href="<?php echo e(route('admin.users.index')); ?>"
               class="btn btn-light btn-sm">

                <i class="bi bi-arrow-left"></i>
                Back

            </a>

        </div>

        
        <div class="card-body">

            
            <?php if(session('success')): ?>

                <div class="alert alert-success">
                    <?php echo e(session('success')); ?>

                </div>

            <?php endif; ?>

            
            <?php if($errors->any()): ?>

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <li><?php echo e($error); ?></li>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </ul>

                </div>

            <?php endif; ?>

            
            <form action="<?php echo e(route('admin.users.store')); ?>"
                  method="POST">

                <?php echo csrf_field(); ?>

                
                <div class="row">

                    
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Full Name
                        </label>

                        <input type="text"
                               name="name"
                               class="form-control"
                               value="<?php echo e(old('name')); ?>"
                               placeholder="Enter full name"
                               required>

                    </div>

                    
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Email Address
                        </label>

                        <input type="email"
                               name="email"
                               class="form-control"
                               value="<?php echo e(old('email')); ?>"
                               placeholder="Enter email address"
                               required>

                    </div>

                </div>

                
                <div class="row">

                    
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Password
                        </label>

                        <input type="password"
                               name="password"
                               class="form-control"
                               placeholder="Enter password"
                               required>

                    </div>

                    
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Confirm Password
                        </label>

                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               placeholder="Confirm password"
                               required>

                    </div>

                </div>

                
                <div class="mb-3">

                    <label class="form-label fw-bold">
                        Role
                    </label>

                    
                    <select name="role"
                            class="form-select"
                            required>

                        <option value="">
                            -- Select Role --
                        </option>

                        <option value="Admin"
                            <?php echo e(old('role') == 'Admin' ? 'selected' : ''); ?>>
                            Admin
                        </option>

                        <option value="Accountant"
                            <?php echo e(old('role') == 'Accountant' ? 'selected' : ''); ?>>
                            Accountant
                        </option>

                        <option value="Registrar"
                            <?php echo e(old('role') == 'Registrar' ? 'selected' : ''); ?>>
                            Registrar
                        </option>

                        <option value="Teacher"
                            <?php echo e(old('role') == 'Teacher' ? 'selected' : ''); ?>>
                            Teacher
                        </option>

                        <option value="User"
                            <?php echo e(old('role') == 'User' ? 'selected' : ''); ?>>
                            User
                        </option>

                    </select>

                    <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

                        <div class="text-danger mt-1">
                            <?php echo e($message); ?>

                        </div>

                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                </div>

                
                <div class="form-check mb-4">

                    <input type="checkbox"
                           name="is_blocked"
                           value="1"
                           class="form-check-input"
                           id="blocked">

                    <label class="form-check-label" for="blocked">
                        Block this user
                    </label>

                </div>

                
                <div class="d-flex justify-content-end">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-save-fill"></i>
                        Save User

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/Admin/users/create.blade.php ENDPATH**/ ?>