<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'School Finance System')); ?></title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>

        :root{
            --sidebar-bg:#0f172a;
            --sidebar-hover:#1e293b;
            --sidebar-active:#2563eb;
            --body-bg:#f1f5f9;
            --border:#e2e8f0;
        }

        *{margin:0;padding:0;box-sizing:border-box;}

        body{
            font-family:'Inter',sans-serif;
            background:var(--body-bg);
        }

        .sidebar{
            width:270px;
            height:100vh;
            position:fixed;
            left:0;
            top:0;
            background:var(--sidebar-bg);
            overflow-y:auto;
            transition:.3s;
        }

        .sidebar::-webkit-scrollbar{width:6px;}
        .sidebar::-webkit-scrollbar-thumb{background:#334155;}

        .brand{
            padding:22px;
            text-align:center;
            color:#fff;
            font-weight:800;
            font-size:20px;
            border-bottom:1px solid rgba(255,255,255,.1);
        }

        .section{
            padding:15px 18px 6px;
            font-size:11px;
            text-transform:uppercase;
            color:#94a3b8;
            font-weight:700;
        }

        .sidebar a,
        .sidebar button{
            width:100%;
            display:flex;
            gap:10px;
            align-items:center;
            padding:12px 18px;
            text-decoration:none;
            color:#cbd5e1;
            border:none;
            background:none;
            font-size:14px;
            transition:.2s;
        }

        .sidebar a:hover,
        .sidebar button:hover{
            background:var(--sidebar-hover);
            color:#fff;
        }

        .sidebar a.active{
            background:var(--sidebar-active);
            color:#fff;
        }

        .main{
            margin-left:270px;
            min-height:100vh;
        }

        .topbar{
            background:#fff;
            border-bottom:1px solid var(--border);
            padding:15px 25px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            position:sticky;
            top:0;
        }

        .user-box{
            display:flex;
            align-items:center;
            gap:10px;
            background:#f8fafc;
            padding:8px 12px;
            border-radius:10px;
            border:1px solid var(--border);
        }

        .avatar{
            width:38px;
            height:38px;
            border-radius:50%;
            background:var(--sidebar-active);
            color:#fff;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:700;
        }

        .content{padding:25px;}

        @media(max-width:991px){
            .sidebar{left:-270px;}
            .sidebar.show{left:0;}
            .main{margin-left:0;}
        }

    </style>

</head>

<body>

<?php
    $user = auth()->user();
?>


<div class="sidebar" id="sidebar">

    <div class="brand">🎓 Finance System</div>

    
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view dashboard')): ?>
        <div class="section">Main</div>
        <a href="<?php echo e(route('dashboard')); ?>"
           class="<?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
            <i class="bi bi-speedometer2"></i>
            Dashboard
        </a>
    <?php endif; ?>


    
    <?php if($user && ($user->can('view users') || $user->can('manage permissions'))): ?>

        <div class="section">User Management</div>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view users')): ?>
        <a href="<?php echo e(route('admin.users.index')); ?>"
           class="<?php echo e(request()->routeIs('admin.users.*') ? 'active' : ''); ?>">
            <i class="bi bi-people"></i>
            Users
        </a>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage permissions')): ?>
        <a href="<?php echo e(route('permissions.index')); ?>"
           class="<?php echo e(request()->routeIs('permissions.*') ? 'active' : ''); ?>">
            <i class="bi bi-shield-lock"></i>
            Roles & Permissions
        </a>
        <?php endif; ?>

    <?php endif; ?>


    
    <?php if($user && ($user->can('view students') || $user->can('view classes'))): ?>

        <div class="section">Academic</div>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view students')): ?>
        <a href="<?php echo e(route('students.index')); ?>"
           class="<?php echo e(request()->routeIs('students.*') ? 'active' : ''); ?>">
            <i class="bi bi-mortarboard"></i>
            Students
        </a>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view classes')): ?>
        <a href="<?php echo e(route('classes.index')); ?>"
           class="<?php echo e(request()->routeIs('classes.*') ? 'active' : ''); ?>">
            <i class="bi bi-building"></i>
            Classes
        </a>

        <a href="<?php echo e(route('sections.index')); ?>"
           class="<?php echo e(request()->routeIs('sections.*') ? 'active' : ''); ?>">
            <i class="bi bi-diagram-3"></i>
            Sections
        </a>

        <a href="<?php echo e(route('academic-years.index')); ?>"
           class="<?php echo e(request()->routeIs('academic-years.*') ? 'active' : ''); ?>">
            <i class="bi bi-calendar-event"></i>
            Academic Years
        </a>
        <?php endif; ?>

    <?php endif; ?>


    
    <?php if($user && ($user->can('view invoices') || $user->can('view payments'))): ?>

        <div class="section">Finance</div>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view invoices')): ?>
        <a href="<?php echo e(route('fee-categories.index')); ?>"
           class="<?php echo e(request()->routeIs('fee-categories.*') ? 'active' : ''); ?>">
            <i class="bi bi-tags"></i>
            Fee Categories
        </a>

        <a href="<?php echo e(route('invoices.index')); ?>"
           class="<?php echo e(request()->routeIs('invoices.*') ? 'active' : ''); ?>">
            <i class="bi bi-receipt"></i>
            Invoices
        </a>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view payments')): ?>
        <a href="<?php echo e(route('payments.index')); ?>"
           class="<?php echo e(request()->routeIs('payments.*') ? 'active' : ''); ?>">
            <i class="bi bi-cash-stack"></i>
            Payments
        </a>

        <a href="<?php echo e(route('finance.summary')); ?>"
           class="<?php echo e(request()->routeIs('finance.*') ? 'active' : ''); ?>">
            <i class="bi bi-bar-chart"></i>
            Reports
        </a>
        <?php endif; ?>

    <?php endif; ?>


    
    <?php if($user && $user->can('manage permissions')): ?>

        <div class="section">System</div>

        <a href="<?php echo e(route('settings.index')); ?>"
           class="<?php echo e(request()->routeIs('settings.*') ? 'active' : ''); ?>">
            <i class="bi bi-gear"></i>
            Settings
        </a>

        
        <form method="POST"
              action="<?php echo e(route('system.backup.run')); ?>"
              onsubmit="return confirm('Run backup now?')"
              style="margin:0;">

            <?php echo csrf_field(); ?>

            <button type="submit"
                    style="background:none;border:none;width:100%;text-align:left;padding:12px 18px;color:#f59e0b;display:flex;gap:10px;align-items:center;">
                <i class="bi bi-cloud-arrow-down"></i>
                Run Backup
            </button>
        </form>

        <a href="<?php echo e(route('system.backup.download')); ?>"
           class="text-warning">
            <i class="bi bi-download"></i>
            Download Backup
        </a>

    <?php endif; ?>


    
    <div class="section">Account</div>

    <a href="<?php echo e(route('profile.edit')); ?>">
        <i class="bi bi-person-circle"></i>
        Profile
    </a>

    <form method="POST" action="<?php echo e(route('logout')); ?>">
        <?php echo csrf_field(); ?>
        <button type="submit">
            <i class="bi bi-box-arrow-right"></i>
            Logout
        </button>
    </form>

</div>


<div class="main">

    <div class="topbar">

        <button onclick="toggleSidebar()">☰</button>

        <div>
            <strong>Welcome Back</strong><br>
            <small><?php echo e(now()->format('l, d M Y')); ?></small>
        </div>

        <div class="user-box">
            <div class="avatar">
                <?php echo e($user ? strtoupper(substr($user->name,0,1)) : 'U'); ?>

            </div>
            <div>
                <div><?php echo e($user->name ?? 'Guest'); ?></div>
                <small><?php echo e($user?->roles->first()->name ?? 'No Role'); ?></small>
            </div>
        </div>

    </div>

    <div class="content">
        <?php echo $__env->yieldContent('content'); ?>
    </div>

</div>

<script>
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('show');
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/layouts/app.blade.php ENDPATH**/ ?>