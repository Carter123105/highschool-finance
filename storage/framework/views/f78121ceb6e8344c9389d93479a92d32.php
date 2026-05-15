<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    <div class="card shadow-sm border-0">

        
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                Register New Student
            </h4>

            <a href="<?php echo e(route('students.index')); ?>"
               class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
        </div>

        <div class="card-body">

            
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?php echo e(session('success')); ?>


                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            
            <?php if($errors->any()): ?>
                <div class="alert alert-danger alert-dismissible fade show">

                    <strong>
                        Please fix the following errors:
                    </strong>

                    <ul class="mb-0 mt-2">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('students.store')); ?>"
                  method="POST"
                  enctype="multipart/form-data">

                <?php echo csrf_field(); ?>

                <div class="row">

                    
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            Class
                            <span class="text-danger">*</span>
                        </label>

                        <select name="class_id"
                                id="class_id"
                                class="form-control <?php $__errorArgs = ['class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                required>

                            <option value="">
                                Select Class
                            </option>

                            <?php $__currentLoopData = $classes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <option value="<?php echo e($class->id); ?>"
                                    <?php echo e(old('class_id') == $class->id ? 'selected' : ''); ?>>

                                    <?php echo e($class->name); ?>


                                </option>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </select>

                        <?php $__errorArgs = ['class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>

                    
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            Section
                            <span class="text-danger">*</span>
                        </label>

                        <select name="section_id"
                                id="section_id"
                                class="form-control <?php $__errorArgs = ['section_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                required>

                            <option value="">
                                Select Section
                            </option>

                            <?php $__currentLoopData = $sections ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <option value="<?php echo e($section->id); ?>"
                                    data-class-id="<?php echo e($section->class_id); ?>"
                                    <?php echo e(old('section_id') == $section->id ? 'selected' : ''); ?>>

                                    <?php echo e($section->name); ?>


                                </option>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </select>

                        <?php $__errorArgs = ['section_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>

                    
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            Academic Year
                            <span class="text-danger">*</span>
                        </label>

                        <select name="academic_year_id"
                                class="form-control <?php $__errorArgs = ['academic_year_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                required>

                            <option value="">
                                Select Academic Year
                            </option>

                            <?php $__currentLoopData = $years ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <option value="<?php echo e($year->id); ?>"
                                    <?php echo e(old('academic_year_id') == $year->id ? 'selected' : ''); ?>>

                                    <?php echo e($year->name); ?>


                                </option>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </select>

                        <?php $__errorArgs = ['academic_year_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>

                    
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            Student ID
                        </label>

                        <input type="text"
                               name="student_id"
                               id="student_id"
                               value="<?php echo e(old('student_id')); ?>"
                               class="form-control bg-light"
                               placeholder="Auto Generated"
                               readonly>

                    </div>

                    
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            First Name
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="first_name"
                               value="<?php echo e(old('first_name')); ?>"
                               class="form-control <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               required>

                        <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>

                    
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            Last Name
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="last_name"
                               value="<?php echo e(old('last_name')); ?>"
                               class="form-control <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               required>

                        <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>

                    
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            Gender
                            <span class="text-danger">*</span>
                        </label>

                        <select name="gender"
                                class="form-control <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                required>

                            <option value="">
                                Select Gender
                            </option>

                            <option value="Male"
                                <?php echo e(old('gender') == 'Male' ? 'selected' : ''); ?>>
                                Male
                            </option>

                            <option value="Female"
                                <?php echo e(old('gender') == 'Female' ? 'selected' : ''); ?>>
                                Female
                            </option>

                        </select>

                        <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>

                    
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            Student Type
                            <span class="text-danger">*</span>
                        </label>

                        <select name="student_type"
                                class="form-control <?php $__errorArgs = ['student_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                required>

                            <option value="">
                                Select Type
                            </option>

                            <option value="New"
                                <?php echo e(old('student_type') == 'New' ? 'selected' : ''); ?>>
                                New
                            </option>

                            <option value="Old"
                                <?php echo e(old('student_type') == 'Old' ? 'selected' : ''); ?>>
                                Old
                            </option>

                        </select>

                        <?php $__errorArgs = ['student_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>

                    
                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">
                            Phone
                        </label>

                        <input type="text"
                               name="phone"
                               value="<?php echo e(old('phone')); ?>"
                               class="form-control">

                    </div>

                    
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Guardian Name
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="guardian_name"
                               value="<?php echo e(old('guardian_name')); ?>"
                               class="form-control <?php $__errorArgs = ['guardian_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               required>

                        <?php $__errorArgs = ['guardian_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>

                    
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Guardian Phone
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="guardian_phone"
                               value="<?php echo e(old('guardian_phone')); ?>"
                               class="form-control <?php $__errorArgs = ['guardian_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               required>

                        <?php $__errorArgs = ['guardian_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>

                    
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Photo
                        </label>

                        <input type="file"
                               name="photo"
                               class="form-control <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

                        <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>

                    
                    <div class="col-md-6 mb-3">

                        <label class="form-label fw-bold">
                            Address
                        </label>

                        <input type="text"
                               name="address"
                               value="<?php echo e(old('address')); ?>"
                               class="form-control">

                    </div>

                </div>

                
                <div class="mt-3">

                    <button type="submit"
                            class="btn btn-success">

                        <i class="bi bi-check-circle"></i>
                        Save Student

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script>
document.addEventListener("DOMContentLoaded", function () {

    const classSelect = document.getElementById('class_id');
    const studentIdInput = document.getElementById('student_id');
    const sectionSelect = document.getElementById('section_id');

    // ========== STUDENT ID GENERATION ==========
    if (classSelect && studentIdInput) {

        function generateStudentId(classId)
        {
            if (!classId) {
                studentIdInput.value = '';
                return;
            }

            fetch(`/students/generate-id/${classId}`)
                .then(response => response.json())
                .then(data => {

                    if (data.student_id) {
                        studentIdInput.value = data.student_id;
                    } else {
                        studentIdInput.value = '';
                    }

                })
                .catch(error => {

                    console.error('Student ID Error:', error);

                    studentIdInput.value = '';
                });
        }

        classSelect.addEventListener('change', function () {

            generateStudentId(this.value);

        });

        // AUTO LOAD OLD VALUE
        if (classSelect.value && !studentIdInput.value) {

            generateStudentId(classSelect.value);

        }
    }

    // ========== FILTER SECTIONS BY CLASS ==========
    if (classSelect && sectionSelect) {

        // Store all sections for filtering
        const allSections = Array.from(sectionSelect.options).slice(1); // Skip "Select Section"

        function filterSections(classId)
        {
            // Clear current options except placeholder
            sectionSelect.innerHTML = '<option value="">Select Section</option>';

            if (!classId) {
                return;
            }

            // Filter sections by class_id
            const filteredSections = allSections.filter(option => 
                option.getAttribute('data-class-id') == classId
            );

            // Add filtered sections back
            filteredSections.forEach(option => sectionSelect.appendChild(option));

            // Restore old selection if valid
            const oldSectionId = "<?php echo e(old('section_id')); ?>";
            if (oldSectionId) {
                const oldOption = sectionSelect.querySelector(`option[value="${oldSectionId}"]`);
                if (oldOption) {
                    oldOption.selected = true;
                }
            }
        }

        classSelect.addEventListener('change', function () {

            filterSections(this.value);

        });

        // AUTO FILTER ON PAGE LOAD IF CLASS IS PRE-SELECTED
        if (classSelect.value) {
            filterSections(classSelect.value);
        }
    }

});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/students/create.blade.php ENDPATH**/ ?>