<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Students</h4>

        <a href="<?php echo e(route('students.create')); ?>" class="btn btn-primary">
            + Create Student
        </a>
    </div>

    
    <div class="card mb-3 shadow-sm border-0">
        <div class="card-body d-flex flex-wrap gap-3 align-items-center">

            
            <div style="min-width: 250px;">
                <label class="form-label fw-semibold">Filter by Class</label>

                <select id="classFilter" class="form-select">
                    <option value="">All Classes</option>

                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($class->id); ?>">
                            <?php echo e($class->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div style="min-width: 250px;">
                <label class="form-label fw-semibold">Search Student</label>
                <input type="text" id="searchInput" class="form-control" placeholder="Search name or ID...">
            </div>

            <div class="mt-4">
                <button class="btn btn-secondary" onclick="resetFilters()">
                    Reset
                </button>
            </div>

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

                    <tr class="student-row"
                        data-class="<?php echo e($student->class_id); ?>">

                        <td><?php echo e($loop->iteration); ?></td>

                        <td class="student-id">
                            <?php echo e($student->student_id); ?>

                        </td>

                        <td class="fw-semibold student-name">
                            <?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?>

                        </td>

                        
                        <td>
                            <?php echo e($student->schoolClass?->name ?? 'N/A'); ?>

                        </td>

                        <td>
                            <?php echo e($student->section?->name ?? 'N/A'); ?>

                        </td>

                        <td>
                            <?php echo e($student->academicYear?->name ?? 'N/A'); ?>

                        </td>

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

                        <td>
                            <?php echo e($student->phone ?? 'N/A'); ?>

                        </td>

                        <td class="d-flex gap-1 flex-wrap">

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

                                <button class="btn btn-sm btn-danger">
                                    Delete
                                </button>

                            </form>

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

            <div class="mt-3">
                <?php echo e($students->links()); ?>

            </div>

        </div>
    </div>

</div>


<script>
document.addEventListener("DOMContentLoaded", function () {

    const classFilter = document.getElementById("classFilter");
    const searchInput = document.getElementById("searchInput");
    const rows = document.querySelectorAll(".student-row");

    function filterTable() {

        const classValue = classFilter.value;
        const searchValue = searchInput.value.toLowerCase();

        rows.forEach(row => {

            const studentClass = row.dataset.class;
            const studentName = row.querySelector(".student-name").textContent.toLowerCase();
            const studentId = row.querySelector(".student-id").textContent.toLowerCase();

            const matchClass = classValue === "" || studentClass === classValue;
            const matchSearch = studentName.includes(searchValue) || studentId.includes(searchValue);

            row.style.display = (matchClass && matchSearch) ? "" : "none";
        });
    }

    classFilter.addEventListener("change", filterTable);
    searchInput.addEventListener("keyup", filterTable);

    window.resetFilters = function () {
        classFilter.value = "";
        searchInput.value = "";
        filterTable();
    }

});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/students/index.blade.php ENDPATH**/ ?>