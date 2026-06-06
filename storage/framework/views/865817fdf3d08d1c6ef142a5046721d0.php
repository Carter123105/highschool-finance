

<?php $__env->startSection('content'); ?>

<div class="container-fluid py-4">

    
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold">Roles & Permissions</h3>
            <p class="text-muted mb-0">Manage user access via roles only</p>
        </div>

    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    
    <?php if(session('error')): ?>
        <div class="alert alert-danger">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Permissions (Role Based)</th>
                            <th style="width:320px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php
                                $role = $u->roles->first();

                                // 🔥 STRICT ROLE PERMISSIONS ONLY (no user-level mixing)
                                $rolePermissions = $role
                                    ? $role->permissions->pluck('name')->toArray()
                                    : [];
                            ?>

                            <tr>

                                
                                <td><?php echo e($u->name); ?></td>

                                
                                <td><?php echo e($u->email); ?></td>

                                
                                <td>
                                    <?php $__empty_1 = true; $__currentLoopData = $u->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <span class="badge bg-primary">
                                            <?php echo e($r->name); ?>

                                        </span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <span class="text-muted">No Role</span>
                                    <?php endif; ?>
                                </td>

                                
                                <td>

                                    <?php if($role && !empty($rolePermissions)): ?>

                                        <?php $__currentLoopData = $rolePermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="badge bg-success mb-1">
                                                <?php echo e($perm); ?>

                                            </span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    <?php else: ?>
                                        <span class="text-muted">No Permissions</span>
                                    <?php endif; ?>

                                </td>

                                
                                <td>

                                    
                                    <form action="<?php echo e(route('permissions.assignRole', $u->id)); ?>"
                                          method="POST"
                                          class="mb-2">

                                        <?php echo csrf_field(); ?>

                                        <div class="input-group">

                                            <select name="role" class="form-select">

                                                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                    <option value="<?php echo e($r->name); ?>"
                                                        <?php if($u->roles->first()?->name === $r->name): echo 'selected'; endif; ?>>
                                                        <?php echo e($r->name); ?>

                                                    </option>

                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            </select>

                                            <button class="btn btn-dark">
                                                Assign Role
                                            </button>

                                        </div>

                                    </form>

                                    
                                    <form action="<?php echo e(route('permissions.assignPermissions', $u->id)); ?>"
                                          method="POST">

                                        <?php echo csrf_field(); ?>

                                        <div class="row">

                                            <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                <div class="col-6">

                                                    <div class="form-check">

                                                        <input type="checkbox"
                                                               name="permissions[]"
                                                               value="<?php echo e($permission->name); ?>"
                                                               class="form-check-input"

                                                               
                                                               <?php if(in_array($permission->name, $rolePermissions)): echo 'checked'; endif; ?>>

                                                        <label class="form-check-label">
                                                            <?php echo e($permission->name); ?>

                                                        </label>

                                                    </div>

                                                </div>

                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        </div>

                                        <button class="btn btn-success btn-sm mt-3 w-100">
                                            Update Role Permissions
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/permissions/index.blade.php ENDPATH**/ ?>