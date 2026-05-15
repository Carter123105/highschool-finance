<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">Edit Student</h4>
        </div>

        <div class="card-body">

            
            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            
            <form action="<?php echo e(route('students.update', $student->id)); ?>"
                  method="POST"
                  enctype="multipart/form-data">

                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="row">

                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Student ID</label>
                        <input type="text"
                               class="form-control bg-light"
                               value="<?php echo e($student->student_id); ?>"
                               disabled>
                    </div>

                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text"
                               name="first_name"
                               value="<?php echo e(old('first_name', $student->first_name)); ?>"
                               class="form-control">
                    </div>

                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text"
                               name="last_name"
                               value="<?php echo e(old('last_name', $student->last_name)); ?>"
                               class="form-control">
                    </div>

                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Class</label>

                        <select name="class_id" class="form-control" required>
                            <option value="">-- Select Class --</option>

                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>"
                                    <?php echo e(old('class_id', $student->class_id) == $class->id ? 'selected' : ''); ?>>
                                    <?php echo e($class->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Section</label>

                        <select name="section_id" class="form-control" required>
                            <option value="">-- Select Section --</option>

                            <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($section->id); ?>"
                                    <?php echo e(old('section_id', $student->section_id) == $section->id ? 'selected' : ''); ?>>
                                    <?php echo e($section->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Academic Year</label>

                        <select name="academic_year_id" class="form-control" required>
                            <option value="">-- Select Year --</option>

                            <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($year->id); ?>"
                                    <?php echo e(old('academic_year_id', $student->academic_year_id) == $year->id ? 'selected' : ''); ?>>
                                    <?php echo e($year->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Gender</label>

                        <select name="gender" class="form-control" required>
                            <option value="Male"
                                <?php echo e(old('gender', $student->gender) == 'Male' ? 'selected' : ''); ?>>
                                Male
                            </option>

                            <option value="Female"
                                <?php echo e(old('gender', $student->gender) == 'Female' ? 'selected' : ''); ?>>
                                Female
                            </option>
                        </select>
                    </div>

                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Student Type</label>

                        <select name="student_type" class="form-control" required>
                            <option value="New"
                                <?php echo e(old('student_type', $student->student_type) == 'New' ? 'selected' : ''); ?>>
                                New
                            </option>

                            <option value="Old"
                                <?php echo e(old('student_type', $student->student_type) == 'Old' ? 'selected' : ''); ?>>
                                Old
                            </option>
                        </select>
                    </div>

                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text"
                               name="phone"
                               value="<?php echo e(old('phone', $student->phone)); ?>"
                               class="form-control">
                    </div>

                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Guardian Name</label>
                        <input type="text"
                               name="guardian_name"
                               value="<?php echo e(old('guardian_name', $student->guardian_name)); ?>"
                               class="form-control">
                    </div>

                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Guardian Phone</label>
                        <input type="text"
                               name="guardian_phone"
                               value="<?php echo e(old('guardian_phone', $student->guardian_phone)); ?>"
                               class="form-control">
                    </div>

                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Photo</label>

                        <br>

                        <?php if($student->photo): ?>
                            <img src="<?php echo e(asset('storage/'.$student->photo)); ?>"
                                 width="80"
                                 class="mb-2 rounded">
                        <?php endif; ?>

                        <input type="file" name="photo" class="form-control">
                    </div>

                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Address</label>
                        <input type="text"
                               name="address"
                               value="<?php echo e(old('address', $student->address)); ?>"
                               class="form-control">
                    </div>

                </div>

                <button type="submit" class="btn btn-primary">
                    Update Student
                </button>

            </form>

        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/students/edit.blade.php ENDPATH**/ ?>