<?php $__env->startSection('content'); ?>

<?php
    use Carbon\Carbon;
    use App\Models\AcademicYear;

    $today = Carbon::today()->format('F d, Y');

    $yearId = session('academic_year_id');

    // SAFE DEFAULTS (IMPORTANT FIX)
    $recentPayments = $recentPayments ?? collect();
    $fullyPaidStudents = $fullyPaidStudents ?? 0;
    $studentsOwing = $studentsOwing ?? 0;
    $todayRevenue = $todayRevenue ?? 0;
    $monthlyRevenue = $monthlyRevenue ?? 0;
    $totalRevenue = $totalRevenue ?? 0;
    $outstandingBalance = $outstandingBalance ?? 0;
?>

<style>
    .dashboard-card{
        border:none;
        border-radius:18px;
        overflow:hidden;
        transition:0.3s ease;
        box-shadow:0 4px 18px rgba(0,0,0,0.06);
    }

    .dashboard-card:hover{
        transform:translateY(-4px);
    }

    .card-icon{
        width:60px;
        height:60px;
        border-radius:16px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:24px;
    }

    .stat-number{
        font-size:28px;
        font-weight:800;
        margin-top:8px;
    }

    .dashboard-header{
        background:linear-gradient(135deg,#0d6efd,#0b5ed7);
        color:white;
        border-radius:20px;
        padding:30px;
        margin-bottom:25px;
        box-shadow:0 8px 30px rgba(13,110,253,0.2);
    }

    .summary-box{
        background:#fff;
        border-radius:18px;
        padding:20px;
        box-shadow:0 4px 18px rgba(0,0,0,0.06);
        height:100%;
    }

    @media print{
        .btn,
        form{
            display:none !important;
        }
    }
</style>

<div class="container-fluid py-4">

    
    <div class="dashboard-header">

        <div class="d-flex justify-content-between align-items-center flex-wrap">

            <div>
                <h2 class="fw-bold mb-1">Accountant Dashboard</h2>
                <p class="mb-0 opacity-75">
                    Financial overview and payment tracking system
                </p>
            </div>

            <div class="text-end mt-3 mt-md-0">

                <div class="fw-semibold">
                    <?php echo e($today); ?>

                </div>

                
                <form method="POST" action="<?php echo e(route('finance.set-year')); ?>" class="mt-2">
                    <?php echo csrf_field(); ?>

                    <div class="d-flex gap-2 align-items-center">

                        <select name="academic_year_id"
                                class="form-select form-select-sm"
                                required>

                            <option value="">Select Year</option>

                            <?php $__currentLoopData = AcademicYear::orderBy('id','desc')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <option value="<?php echo e($year->id); ?>"
                                    <?php echo e($yearId == $year->id ? 'selected' : ''); ?>>

                                    <?php echo e($year->name); ?>


                                </option>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </select>

                        <button type="submit" class="btn btn-light btn-sm">
                            Save
                        </button>

                    </div>
                </form>

                <button onclick="window.print()" class="btn btn-light btn-sm mt-2">
                    <i class="bi bi-printer"></i> Print Report
                </button>

            </div>

        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success mt-3">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

    </div>

    
    <div class="row g-4">

        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card bg-white p-4 h-100">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="text-muted fw-semibold">Today's Revenue</div>
                        <div class="stat-number text-success">
                            $<?php echo e(number_format($todayRevenue, 2)); ?>

                        </div>
                    </div>
                    <div class="card-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card bg-white p-4 h-100">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="text-muted fw-semibold">Monthly Revenue</div>
                        <div class="stat-number text-primary">
                            $<?php echo e(number_format($monthlyRevenue, 2)); ?>

                        </div>
                    </div>
                    <div class="card-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-bar-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card bg-white p-4 h-100">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="text-muted fw-semibold">Outstanding Balance</div>
                        <div class="stat-number text-danger">
                            $<?php echo e(number_format($outstandingBalance, 2)); ?>

                        </div>
                    </div>
                    <div class="card-icon bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-exclamation-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="dashboard-card bg-white p-4 h-100">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="text-muted fw-semibold">Total Revenue</div>
                        <div class="stat-number text-dark">
                            $<?php echo e(number_format($totalRevenue, 2)); ?>

                        </div>
                    </div>
                    <div class="card-icon bg-dark bg-opacity-10 text-dark">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    
    
<div class="row g-4 mt-1">

    <div class="col-md-4">
        <div class="summary-box">
            <div class="text-muted fw-semibold">Total Payments</div>
            <h3 class="fw-bold mt-2">
                <?php echo e($recentPayments->count()); ?>

            </h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="summary-box">
            <div class="text-muted fw-semibold">Fully Paid Students</div>
            <h3 class="fw-bold mt-2 text-success">
                <?php echo e($fullyPaidStudents); ?>

            </h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="summary-box">
            <div class="text-muted fw-semibold">Students Owing</div>
            <h3 class="fw-bold mt-2 text-danger">
                <?php echo e($studentsOwing); ?>

            </h3>
        </div>
    </div>

</div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/Accountant/accountant.blade.php ENDPATH**/ ?>