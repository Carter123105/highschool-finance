<div class="mb-3">
    <label class="form-label">Class Name</label>

    <input type="text"
           name="name"
           class="form-control"
           value="<?php echo e(old('name', $class->name ?? '')); ?>">
</div>

<div class="mb-3">
    <label class="form-label">Description</label>

    <textarea name="description"
              class="form-control"
              rows="4"><?php echo e(old('description', $class->description ?? '')); ?></textarea>
</div>

<button class="btn btn-primary btn-loading">
    Save Class
</button><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/classes/_form.blade.php ENDPATH**/ ?>