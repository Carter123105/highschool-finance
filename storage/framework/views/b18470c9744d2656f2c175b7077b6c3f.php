<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    
    <div class="d-flex justify-content-between align-items-center mb-3">

        <h4 class="fw-bold mb-0">
            Students
        </h4>

        <a href="<?php echo e(route('students.create')); ?>"
           class="btn btn-primary">
            + Create Student
        </a>

    </div>

    
    <div class="card mb-3 shadow-sm border-0">

        <div class="card-body">

            <form method="GET" action="<?php echo e(route('students.index')); ?>">

                <div class="row g-3 align-items-end">

                    
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Filter by Class
                        </label>

                        <select name="class_id" class="form-select">

                            <option value="">All Classes</option>

                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <option value="<?php echo e($class->id); ?>"
                                    <?php echo e(request('class_id') == $class->id ? 'selected' : ''); ?>>
                                    <?php echo e($class->name); ?>

                                </option>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </select>

                    </div>

                    
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Search Student
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Search name or ID..."
                               value="<?php echo e(request('search')); ?>">

                    </div>

                    
                    <div class="col-md-4 d-flex gap-2">

                        <button type="submit" class="btn btn-primary">
                            Filter
                        </button>

                        <a href="<?php echo e(route('students.index')); ?>" class="btn btn-secondary">
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

    
    <div class="card shadow-sm border-0">

        <div class="card-header bg-dark text-white">
            All Students (<?php echo e($students->total()); ?>)
        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Year</th>
                        <th>Gender</th>
                        <th>Student Type</th>
                        <th>Phone</th>
                        <th style="width: 200px;">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <tr>

                            <td><?php echo e($loop->iteration); ?></td>

                            <td><?php echo e($student->student_id); ?></td>

                            <td class="fw-semibold">
                                <?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?>

                            </td>

                            <td><?php echo e($student->schoolClass?->name ?? 'N/A'); ?></td>

                            <td><?php echo e($student->section?->name ?? 'N/A'); ?></td>

                            <td><?php echo e($student->academicYear?->name ?? 'N/A'); ?></td>

                            <td>
                                <span class="badge bg-secondary">
                                    <?php echo e($student->gender); ?>

                                </span>
                            </td>

                            <td>
                                <?php if($student->student_type == 'New'): ?>
                                    <span class="badge bg-success">New</span>
                                <?php elseif($student->student_type == 'Old'): ?>
                                    <span class="badge bg-warning text-dark">Old</span>
                                <?php else: ?>
                                    <span class="badge bg-light text-dark">N/A</span>
                                <?php endif; ?>
                            </td>

                            <td><?php echo e($student->phone ?? 'N/A'); ?></td>

                            <td>
                                <div class="d-flex gap-1 flex-wrap">

                                    <a href="<?php echo e(route('students.show', $student->id)); ?>"
                                       class="btn btn-sm btn-info">
                                        View
                                    </a>

                                    <a href="<?php echo e(route('students.edit', $student->id)); ?>"
                                       class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form action="<?php echo e(route('students.destroy', $student->id)); ?>"
                                          method="POST"
                                          onsubmit="return confirm('Delete this student?')">

                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>

                                        <button type="submit"
                                                class="btn btn-sm btn-danger">
                                            Delete
                                        </button>

                                    </form>

                                </div>
                            </td>

                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                No students found
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

            
            <?php if($students->hasPages()): ?>

                <div class="students-pagination-wrapper">

                    <div class="pagination-info">
                        Showing
                        <strong><?php echo e($students->firstItem()); ?></strong>
                        -
                        <strong><?php echo e($students->lastItem()); ?></strong>
                        of
                        <strong><?php echo e($students->total()); ?></strong>
                        students
                    </div>

                    <div class="pagination-links">

                        
                        <?php if($students->onFirstPage()): ?>
                            <span class="pagination-btn disabled">Previous</span>
                        <?php else: ?>
                            <a href="<?php echo e($students->previousPageUrl()); ?>"
                               class="pagination-btn">Previous</a>
                        <?php endif; ?>

                        
                        <?php $__currentLoopData = $students->getUrlRange(1, $students->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php if($page == $students->currentPage()): ?>
                                <span class="pagination-number active"><?php echo e($page); ?></span>
                            <?php else: ?>
                                <a href="<?php echo e($url); ?>" class="pagination-number"><?php echo e($page); ?></a>
                            <?php endif; ?>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        
                        <?php if($students->hasMorePages()): ?>
                            <a href="<?php echo e($students->nextPageUrl()); ?>"
                               class="pagination-btn">Next</a>
                        <?php else: ?>
                            <span class="pagination-btn disabled">Next</span>
                        <?php endif; ?>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>


<style>

.students-pagination-wrapper{
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:20px;
    margin-top:25px;
    padding:18px 22px;
    background:#fff;
    border-radius:16px;
    box-shadow:0 4px 18px rgba(0,0,0,0.05);
    border:1px solid #eef2f7;
}

.pagination-info{
    color:#64748b;
    font-size:14px;
}

.pagination-links{
    display:flex;
    align-items:center;
    gap:8px;
    flex-wrap:wrap;
}

.pagination-btn,
.pagination-number{
    text-decoration:none;
    min-width:42px;
    height:42px;
    padding:0 16px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:12px;
    font-weight:600;
    font-size:14px;
    transition:all .2s ease;
    border:1px solid #e2e8f0;
    background:#fff;
    color:#334155;
}

.pagination-number:hover,
.pagination-btn:hover{
    background:#2563eb;
    color:#fff;
    border-color:#2563eb;
    transform:translateY(-2px);
    box-shadow:0 6px 14px rgba(37,99,235,.18);
}

.pagination-number.active{
    background:#2563eb;
    color:#fff;
    border-color:#2563eb;
    box-shadow:0 6px 14px rgba(37,99,235,.20);
}

.pagination-btn.disabled{
    opacity:.5;
    pointer-events:none;
    background:#f8fafc;
}

@media(max-width:768px){
    .students-pagination-wrapper{
        flex-direction:column;
        align-items:flex-start;
    }

    .pagination-links{
        width:100%;
    }
}

</style>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/students/index.blade.php ENDPATH**/ ?>