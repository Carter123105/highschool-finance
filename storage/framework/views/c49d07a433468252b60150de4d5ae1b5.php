<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    <div class="card">

        <div class="card-header">
            <h4>Create Class</h4>
        </div>

        <div class="card-body">

            <form action="<?php echo e(route('classes.store')); ?>"
                  method="POST">

                <?php echo csrf_field(); ?>

                <?php echo $__env->make('classes._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            </form>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/classes/create.blade.php ENDPATH**/ ?>