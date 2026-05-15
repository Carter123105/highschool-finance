<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<?php
    use App\Models\Setting;

    $setting = Setting::first();

    $schoolName = $setting->school_name ?? config('app.name', 'School Financial System');
    $logo = $setting->logo ?? null;

    if ($logo) {
        $logoUrl = str_starts_with($logo, 'http')
            ? $logo
            : asset('storage/' . $logo);
    } else {
        $logoUrl = null;
    }
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login - <?php echo e($schoolName); ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700" rel="stylesheet" />

    <style>
        body {
            font-family: Figtree, sans-serif;
            background: radial-gradient(circle at top, #0d6efd, #0a58ca);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-wrapper {
            width: 100%;
            max-width: 1000px;
        }

        .login-card {
            border: 0;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
        }

        .login-left {
            background: linear-gradient(rgba(13,110,253,0.85), rgba(10,88,202,0.85)),
                        url('https://images.unsplash.com/photo-1523240795612-9a054b0db644')
                        center/cover;
            color: white;
            padding: 50px;
        }

        .brand {
            width: 70px;
            height: 70px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            overflow: hidden;
            font-weight: 800;
            color: #0d6efd;
        }

        .brand img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .login-right {
            background: #ffffff;
            padding: 50px;
        }

        .form-control {
            height: 48px;
            border-radius: 10px;
        }

        .btn-login {
            height: 48px;
            border-radius: 10px;
            font-weight: 600;
        }
    </style>
</head>

<body>

<div class="login-wrapper">

    <div class="card login-card">

        <div class="row g-0">

            <!-- LEFT PANEL -->
            <div class="col-md-6 login-left d-none d-md-block">

                <div class="h-100 d-flex flex-column justify-content-center">

                    <!-- LOGO -->
                    <div class="brand mb-4">
                        <?php if($logoUrl): ?>
                            <img src="<?php echo e($logoUrl); ?>" alt="Logo">
                        <?php else: ?>
                            <span>SF</span>
                        <?php endif; ?>
                    </div>

                    <h2><?php echo e($schoolName); ?></h2>

                    <p class="mt-3">
                        Manage students, invoices, payments, receipts, and reports in one powerful platform.
                    </p>

                </div>

            </div>

            <!-- RIGHT PANEL -->
            <div class="col-md-6 login-right">

                <div class="text-center mb-3">
                    <h4 class="fw-bold">Welcome Back</h4>
                    <p class="text-muted">Login to your dashboard</p>
                </div>

                
                <?php if(session('status')): ?>
                    <div class="alert alert-success">
                        <?php echo e(session('status')); ?>

                    </div>
                <?php endif; ?>

                
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                
                <?php if(session('error')): ?>
                    <div class="alert alert-danger">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('login')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email"
                               name="email"
                               value="<?php echo e(old('email')); ?>"
                               class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               required>
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password"
                               name="password"
                               class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               required>
                    </div>

                    <button class="btn btn-primary w-100 btn-login">
                        Sign In
                    </button>

                </form>

                <p class="text-center text-muted mt-4">
                    © <?php echo e(date('Y')); ?> <?php echo e($schoolName); ?>

                </p>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/auth/login.blade.php ENDPATH**/ ?>