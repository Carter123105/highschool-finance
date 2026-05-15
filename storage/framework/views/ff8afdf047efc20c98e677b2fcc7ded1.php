<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo e($setting->school_name ?? 'School Financil System'); ?></title>

    
    <link rel="stylesheet" href="<?php echo e(asset('assets/bootstrap/css/bootstrap.min.css')); ?>">

    
    <link rel="stylesheet" href="<?php echo e(asset('assets/bootstrap-icons/bootstrap-icons.css')); ?>">

    
    <style>

        body{
            font-family: Arial, Helvetica, sans-serif;
            background:#f8fafc;
        }

        .hero{
            background:linear-gradient(135deg,#0d6efd,#0a58ca);
            color:#fff;
            padding:110px 0;
        }

        .feature-card{
            transition:.3s ease;
            border:0;
            border-radius:18px;
        }

        .feature-card:hover{
            transform:translateY(-6px);
            box-shadow:0 12px 30px rgba(0,0,0,.08);
        }

        .brand-badge{
            width:48px;
            height:48px;
            background:#0d6efd;
            color:#fff;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:700;
            border-radius:12px;
            font-size:18px;
        }

        .navbar{
            backdrop-filter:blur(10px);
        }

        .hero .btn{
            border-radius:12px;
            padding:14px 30px;
            font-weight:600;
        }

        footer{
            background:#fff;
        }

    </style>
</head>

<body>


<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">

    <div class="container">

        <a class="navbar-brand d-flex align-items-center gap-3" href="#">

            <div class="brand-badge">
                SF
            </div>

            <div>

                <div class="fw-bold">
                    <?php echo e($setting->school_name ?? 'School Financial System'); ?>

                </div>

                <small class="text-muted">
                    Billing & Payment System
                </small>

            </div>

        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse justify-content-end"
             id="navbarNav">

            <ul class="navbar-nav align-items-lg-center gap-2">

                <?php if(auth()->guard()->check()): ?>

                    <li class="nav-item">

                        <a href="<?php echo e(url('/dashboard')); ?>"
                           class="btn btn-primary px-4">

                            Dashboard

                        </a>

                    </li>

                <?php else: ?>

                    <li class="nav-item">

                        <a href="<?php echo e(route('login')); ?>"
                           class="nav-link fw-semibold">

                            Login

                        </a>

                    </li>

                    <?php if(Route::has('register')): ?>

                        <li class="nav-item">

                            <a href="<?php echo e(route('register')); ?>"
                               class="btn btn-dark px-4">

                                Register

                            </a>

                        </li>

                    <?php endif; ?>

                <?php endif; ?>

            </ul>

        </div>

    </div>

</nav>


<section class="hero text-center">

    <div class="container">

        <h1 class="display-4 fw-bold mb-4">

            School Finance & Invoice Management System

        </h1>

        <p class="lead mb-5">

            Manage students, invoices, payments, receipts,
            expenses, and financial reports in one powerful platform.

        </p>

        <div>

            <?php if(auth()->guard()->check()): ?>

                <a href="<?php echo e(url('/dashboard')); ?>"
                   class="btn btn-light btn-lg shadow-sm">

                    Go to Dashboard

                </a>

            <?php else: ?>

                <a href="<?php echo e(route('login')); ?>"
                   class="btn btn-warning btn-lg shadow-sm">

                    Get Started

                </a>

            <?php endif; ?>

        </div>

    </div>

</section>


<section class="py-5">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">
                System Features
            </h2>

            <p class="text-muted">

                Everything needed to manage school finance efficiently

            </p>

        </div>

        <div class="row g-4">

            
            <div class="col-md-4">

                <div class="card feature-card h-100 p-4 shadow-sm">

                    <h5 class="fw-bold mb-3">
                        📚 Student Management
                    </h5>

                    <p class="text-muted mb-0">

                        Manage students, sections,
                        classes, and academic years.

                    </p>

                </div>

            </div>

            
            <div class="col-md-4">

                <div class="card feature-card h-100 p-4 shadow-sm">

                    <h5 class="fw-bold mb-3">
                        💰 Invoice & Billing
                    </h5>

                    <p class="text-muted mb-0">

                        Create invoices and manage fee structures easily.

                    </p>

                </div>

            </div>

            
            <div class="col-md-4">

                <div class="card feature-card h-100 p-4 shadow-sm">

                    <h5 class="fw-bold mb-3">
                        🧾 Payment Receipts
                    </h5>

                    <p class="text-muted mb-0">

                        Generate professional payment receipts automatically.

                    </p>

                </div>

            </div>

            
            <div class="col-md-4">

                <div class="card feature-card h-100 p-4 shadow-sm">

                    <h5 class="fw-bold mb-3">
                        📊 Financial Reports
                    </h5>

                    <p class="text-muted mb-0">

                        Track income, expenses,
                        balances, and reports.

                    </p>

                </div>

            </div>

            
            <div class="col-md-4">

                <div class="card feature-card h-100 p-4 shadow-sm">

                    <h5 class="fw-bold mb-3">
                        👨‍🏫 Roles & Permissions
                    </h5>

                    <p class="text-muted mb-0">

                        Secure Admin, Registrar,
                        and Accountant access.

                    </p>

                </div>

            </div>

            
            <div class="col-md-4">

                <div class="card feature-card h-100 p-4 shadow-sm">

                    <h5 class="fw-bold mb-3">
                        ⚡ Offline Ready
                    </h5>

                    <p class="text-muted mb-0">

                        Bootstrap assets stored locally
                        for internet-free usage.

                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


<footer class="border-top py-4 mt-5">

    <div class="container text-center text-muted">

        © <?php echo e(date('Y')); ?>

        <?php echo e($setting->school_name ?? 'Leo H. Carter'); ?>


        <br>

        <small>
            All rights reserved.
        </small>

    </div>

</footer>


<script src="<?php echo e(asset('assets/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>

</body>
</html><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/welcome.blade.php ENDPATH**/ ?>