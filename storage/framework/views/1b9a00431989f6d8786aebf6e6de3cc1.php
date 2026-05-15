<?php $__env->startSection('content'); ?>

<div class="container-fluid py-4">

<?php
    $search = request('search');
    $classId = request('class_id');
    $studentType = request('student_type');
    $statusFilter = request('status');
    $yearId = request('academic_year_id') ?? session('academic_year_id');
?>


<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold mb-1">Student Balance Report</h3>
        <p class="text-muted mb-0">
            Track balances by Old and New students
        </p>
    </div>

    <div class="d-flex gap-2 no-print">

        <form method="GET" class="d-flex gap-2">

            <select name="academic_year_id" class="form-select" onchange="this.form.submit()">

                <option value="">All Years</option>

                <?php $__currentLoopData = \App\Models\AcademicYear::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($year->id); ?>"
                        <?php echo e($yearId == $year->id ? 'selected' : ''); ?>>
                        <?php echo e($year->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </select>

        </form>

        <button onclick="window.print()" class="btn btn-dark">
            <i class="bi bi-printer"></i> Print
        </button>

        <a href="<?php echo e(route('finance.balance.export', request()->all())); ?>" class="btn btn-success">
            <i class="bi bi-download"></i> Export CSV
        </a>

    </div>

</div>


<div class="card mb-4 shadow-sm no-print">

    <div class="card-body">

        <form method="GET">

            <input type="hidden" name="academic_year_id" value="<?php echo e($yearId); ?>">

            <div class="row g-3">

                
                <div class="col-md-3">
                    <label class="form-label">Class</label>
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

                
                <div class="col-md-3">
                    <label class="form-label">Student Type</label>
                    <select name="student_type" class="form-select">
                        <option value="">All Types</option>
                        <option value="Old" <?php echo e($studentType == 'Old' ? 'selected' : ''); ?>>Old Students</option>
                        <option value="New" <?php echo e($studentType == 'New' ? 'selected' : ''); ?>>New Students</option>
                    </select>
                </div>

                
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        <option value="Fully Paid" <?php echo e($statusFilter == 'Fully Paid' ? 'selected' : ''); ?>>Fully Paid</option>
                        <option value="Partially Paid" <?php echo e($statusFilter == 'Partially Paid' ? 'selected' : ''); ?>>Partially Paid</option>
                        <option value="Not Paid" <?php echo e($statusFilter == 'Not Paid' ? 'selected' : ''); ?>>Not Paid</option>
                    </select>
                </div>

                
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text"
                           name="search"
                           class="form-control"
                           value="<?php echo e($search); ?>"
                           placeholder="Student name...">
                </div>

                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary">Filter</button>
                    <a href="<?php echo e(route('finance.balance')); ?>" class="btn btn-secondary">Reset</a>
                </div>

            </div>

        </form>

    </div>

</div>


<div class="row mb-4">

    
    <div class="col-md-3">
        <div class="card p-3 shadow-sm border-warning">
            <h6 class="text-warning"><i class="bi bi-person-check"></i> Old Students</h6>
            <small>Count: <?php echo e($oldCount ?? 0); ?></small><br>
            <small>Expected: <?php echo e(number_format($oldExpected ?? 0, 2)); ?></small><br>
            <small>Paid: <?php echo e(number_format($oldPaid ?? 0, 2)); ?></small><br>
            <h5 class="text-danger fw-bold">Balance: <?php echo e(number_format($oldBalance ?? 0, 2)); ?></h5>
        </div>
    </div>

    
    <div class="col-md-3">
        <div class="card p-3 shadow-sm border-success">
            <h6 class="text-success"><i class="bi bi-person-plus"></i> New Students</h6>
            <small>Count: <?php echo e($newCount ?? 0); ?></small><br>
            <small>Expected: <?php echo e(number_format($newExpected ?? 0, 2)); ?></small><br>
            <small>Paid: <?php echo e(number_format($newPaid ?? 0, 2)); ?></small><br>
            <h5 class="text-danger fw-bold">Balance: <?php echo e(number_format($newBalance ?? 0, 2)); ?></h5>
        </div>
    </div>

    
    <div class="col-md-3">
        <div class="card p-3 shadow-sm">
            <h6 class="text-success">Total Paid</h6>
            <h3 class="text-success"><?php echo e(number_format($grandPaid ?? 0, 2)); ?></h3>
        </div>
    </div>

    
    <div class="col-md-3">
        <div class="card p-3 shadow-sm">
            <h6 class="text-danger">Total Balance</h6>
            <h3 class="text-danger"><?php echo e(number_format($grandBalance ?? 0, 2)); ?></h3>
        </div>
    </div>

</div>


<div class="card shadow-sm">

    <div class="card-body table-responsive">

        <table class="table table-bordered align-middle">

            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th>Type</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Invoice No</th>
                    <th>Expected</th>
                    <th>Paid</th>
                    <th>Balance</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

            <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                <?php
                    $student = $report['student'];
                    $invoice = $report['invoice'];
                ?>

                <tr>
                    <td><?php echo e($loop->iteration); ?></td>

                    <td class="fw-bold">
                        <?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?>

                    </td>

                    
                    <td>
                        <?php if($student->student_type == 'New'): ?>
                            <span class="badge bg-success">New</span>
                        <?php elseif($student->student_type == 'Old'): ?>
                            <span class="badge bg-warning text-dark">Old</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">N/A</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php echo e($student->schoolClass->name ?? 'N/A'); ?>

                    </td>

                    <td>
                        <?php echo e($student->section->name ?? 'N/A'); ?>

                    </td>

                    <td>
                        <?php echo e($invoice?->invoice_no ?? 'N/A'); ?>

                    </td>

                    <td class="text-primary fw-bold">
                        <?php echo e(number_format($report['expected'] ?? 0, 2)); ?>

                    </td>

                    <td class="text-success fw-bold">
                        <?php echo e(number_format($report['paid'] ?? 0, 2)); ?>

                    </td>

                    <td class="fw-bold <?php echo e(($report['balance'] ?? 0) > 0 ? 'text-danger' : 'text-success'); ?>">
                        <?php echo e(number_format($report['balance'] ?? 0, 2)); ?>

                    </td>

                    <td>
                        <span class="badge
                            <?php if($report['status'] == 'Fully Paid'): ?> bg-success
                            <?php elseif($report['status'] == 'Partially Paid'): ?> bg-warning text-dark
                            <?php else: ?> bg-danger <?php endif; ?>">

                            <?php echo e($report['status']); ?>

                        </span>
                    </td>

                </tr>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                <tr>
                    <td colspan="10" class="text-center text-muted py-4">
                        No records found
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>

            <tfoot class="table-light">
                <tr>
                    <th colspan="6" class="text-end">GRAND TOTAL</th>
                    <th class="text-primary"><?php echo e(number_format($grandExpected ?? 0, 2)); ?></th>
                    <th class="text-success"><?php echo e(number_format($grandPaid ?? 0, 2)); ?></th>
                    <th class="text-danger"><?php echo e(number_format($grandBalance ?? 0, 2)); ?></th>
                    <th></th>
                </tr>
            </tfoot>

        </table>

    </div>

</div>


<div class="d-flex justify-content-end mt-3 no-print">
    <?php echo e($students->links()); ?>

</div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/finance/balance.blade.php ENDPATH**/ ?>