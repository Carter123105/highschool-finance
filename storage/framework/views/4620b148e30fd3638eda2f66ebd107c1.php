<?php $__env->startSection('content'); ?>

<div class="student-profile container-fluid py-4">

    
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-person-badge me-2 text-primary"></i>
                Student Profile
            </h3>

            <p class="text-muted mb-0">
                View full student information and financial records
            </p>
        </div>

        <div class="d-flex gap-2 mt-2 mt-md-0">

            
            <a href="<?php echo e(route('students.payments', $student->id)); ?>"
               class="btn btn-success btn-sm">

                <i class="bi bi-cash-stack me-1"></i>
                View Payments

            </a>

            <a href="<?php echo e(route('students.index')); ?>"
               class="btn btn-dark btn-sm">

                ← Back

            </a>

        </div>

    </div>

    
    <div class="card profile-card border-0 shadow-sm">

        <div class="card-body">

            <div class="row g-4">

                
                <div class="col-md-3 text-center">

                    <div class="profile-photo">

                        <?php if($student->photo): ?>

                            <img src="<?php echo e(asset('storage/' . $student->photo)); ?>"
                                 class="img-fluid rounded-circle border shadow-sm"
                                 style="width:160px;height:160px;object-fit:cover;">

                        <?php else: ?>

                            <div class="no-photo">
                                <i class="bi bi-person"></i>
                                <p>No Photo</p>
                            </div>

                        <?php endif; ?>

                    </div>

                </div>

                
                <div class="col-md-9">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <h4 class="fw-bold mb-0">
                            <?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?>

                        </h4>

                        <div class="d-flex gap-2">

                            
                            <?php if($student->student_type == 'New'): ?>
                                <span class="badge bg-success px-3 py-2">New</span>
                            <?php elseif($student->student_type == 'Old'): ?>
                                <span class="badge bg-warning text-dark px-3 py-2">Old</span>
                            <?php endif; ?>

                            
                            <span class="badge bg-primary px-3 py-2">
                                <?php echo e($student->status); ?>

                            </span>

                        </div>

                    </div>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <div class="info-box">
                                <small>Student ID</small>
                                <h6><?php echo e($student->student_id); ?></h6>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <small>Gender</small>
                                <h6><?php echo e($student->gender); ?></h6>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <small>Student Type</small>
                                <h6>
                                    <?php if($student->student_type == 'New'): ?>
                                        <span class="text-success">● New Student</span>
                                    <?php elseif($student->student_type == 'Old'): ?>
                                        <span class="text-warning">● Returning Student</span>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </h6>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <small>Class</small>
                                <h6><?php echo e($student->schoolClass->name ?? 'N/A'); ?></h6>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <small>Section</small>
                                <h6><?php echo e($student->section->name ?? 'N/A'); ?></h6>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <small>Academic Year</small>
                                <h6><?php echo e($student->academicYear->name ?? 'N/A'); ?></h6>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <small>Phone</small>
                                <h6><?php echo e($student->phone ?? 'N/A'); ?></h6>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="info-box">
                                <small>Address</small>
                                <h6><?php echo e($student->address ?? 'N/A'); ?></h6>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="info-box">
                                <small>Guardian</small>
                                <h6>
                                    <?php echo e($student->guardian_name ?? 'N/A'); ?>

                                    <?php if($student->guardian_phone): ?>
                                        (<?php echo e($student->guardian_phone); ?>)
                                    <?php endif; ?>
                                </h6>
                            </div>
                        </div>

                    </div>

                    
                    <div class="mt-4 d-flex gap-2">

                        <a href="<?php echo e(route('students.edit', $student->id)); ?>"
                           class="btn btn-warning btn-sm">

                            <i class="bi bi-pencil-square me-1"></i>
                            Edit Student

                        </a>

                        <a href="<?php echo e(route('students.payments', $student->id)); ?>"
                           class="btn btn-success btn-sm">

                            <i class="bi bi-receipt me-1"></i>
                            Payment History

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<style>

.student-profile{
    background:#f4f7fb;
}

/* CARD */
.profile-card{
    border-radius:18px;
}

/* PHOTO */
.profile-photo{
    display:flex;
    justify-content:center;
    align-items:center;
}

.no-photo{
    width:160px;
    height:160px;
    border-radius:50%;
    background:#e2e8f0;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    color:#64748b;
}

.no-photo i{
    font-size:32px;
}

/* INFO BOX */
.info-box{
    background:#f8fafc;
    padding:12px 14px;
    border-radius:12px;
    border:1px solid #eef2f7;
}

.info-box small{
    color:#64748b;
    font-size:12px;
}

.info-box h6{
    margin:0;
    font-weight:700;
    color:#0f172a;
}

/* RESPONSIVE */
@media(max-width:768px){
    .no-photo{
        width:120px;
        height:120px;
    }
}

</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/students/show.blade.php ENDPATH**/ ?>