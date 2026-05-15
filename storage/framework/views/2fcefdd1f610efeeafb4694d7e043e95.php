<?php $__env->startSection('content'); ?>
<style>
    :root {
        --primary: #4f46e5;
        --primary-light: #e0e7ff;
        --primary-dark: #4338ca;
        --success: #059669;
        --success-light: #d1fae5;
        --warning: #d97706;
        --warning-light: #fef3c7;
        --danger: #dc2626;
        --danger-light: #fee2e2;
        --info: #0891b2;
        --info-light: #cffafe;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-300: #cbd5e1;
        --gray-400: #94a3b8;
        --gray-500: #64748b;
        --gray-600: #475569;
        --gray-700: #334155;
        --gray-800: #1e293b;
        --gray-900: #0f172a;
        --radius: 12px;
        --radius-sm: 8px;
        --radius-lg: 16px;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-md: 0 6px 12px -2px rgb(0 0 0 / 0.1), 0 3px 6px -3px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    }

    .users-dashboard {
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-title-group {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--gray-900);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-title i {
        color: var(--primary);
        font-size: 1.5rem;
    }

    .page-subtitle {
        font-size: 0.9375rem;
        color: var(--gray-500);
        font-weight: 500;
    }

    .btn-create-user {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.9375rem;
        text-decoration: none;
        box-shadow: var(--shadow);
        transition: all 0.2s ease;
    }

    .btn-create-user:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: var(--shadow-lg);
        color: #fff;
    }

    /* Alert Modern */
    .alert-modern {
        border: none;
        border-radius: var(--radius-sm);
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 500;
        position: relative;
    }

    .alert-modern .btn-close {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        padding: 0.5rem;
        background: none;
        border: none;
        font-size: 1rem;
        color: inherit;
        opacity: 0.5;
        cursor: pointer;
    }

    .alert-modern .btn-close:hover {
        opacity: 1;
    }

    .alert-success-modern {
        background: var(--success-light);
        color: var(--success);
        border-left: 4px solid var(--success);
    }

    .alert-danger-modern {
        background: var(--danger-light);
        color: var(--danger);
        border-left: 4px solid var(--danger);
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: #fff;
        border-radius: var(--radius);
        padding: 1.25rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-100);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.2s ease;
    }

    .stat-card:hover {
        box-shadow: var(--shadow);
        transform: translateY(-2px);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .stat-icon.blue { background: var(--primary-light); color: var(--primary); }
    .stat-icon.green { background: var(--success-light); color: var(--success); }
    .stat-icon.orange { background: var(--warning-light); color: var(--warning); }
    .stat-icon.red { background: var(--danger-light); color: var(--danger); }
    .stat-icon.purple { background: #f3e8ff; color: #7c3aed; }

    .stat-content {
        flex: 1;
    }

    .stat-label {
        font-size: 0.8125rem;
        color: var(--gray-500);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--gray-900);
        line-height: 1.2;
    }

    /* Main Card */
    .main-card {
        background: #fff;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--gray-100);
        overflow: hidden;
    }

    .card-header-custom {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--gray-100);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .card-header-title {
        font-weight: 700;
        color: var(--gray-800);
        font-size: 1.125rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-header-title i {
        color: var(--primary);
    }

    .search-box {
        position: relative;
    }

    .search-box input {
        padding: 0.5rem 1rem 0.5rem 2.5rem;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-sm);
        font-size: 0.875rem;
        width: 260px;
        transition: all 0.2s;
        background: var(--gray-50);
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-light);
        background: #fff;
    }

    .search-box i {
        position: absolute;
        left: 0.875rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-500);
        font-size: 0.875rem;
    }

    /* Table */
    .table-modern {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-modern thead th {
        background: var(--gray-50);
        padding: 0.875rem 1.25rem;
        font-size: 0.6875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--gray-500);
        border-bottom: 2px solid var(--gray-200);
        white-space: nowrap;
    }

    .table-modern tbody tr {
        transition: all 0.15s ease;
    }

    .table-modern tbody tr:hover {
        background: var(--gray-50);
    }

    .table-modern tbody td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--gray-100);
        vertical-align: middle;
        color: var(--gray-700);
    }

    /* User Cell */
    .user-info {
        display: flex;
        align-items: center;
        gap: 0.875rem;
    }

    .user-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary) 0%, #6366f1 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9375rem;
        flex-shrink: 0;
        box-shadow: var(--shadow-sm);
    }

    .user-details {
        display: flex;
        flex-direction: column;
        gap: 0.125rem;
    }

    .user-name {
        font-weight: 700;
        color: var(--gray-900);
        font-size: 0.9375rem;
    }

    .user-id {
        font-size: 0.75rem;
        color: var(--gray-400);
        font-weight: 600;
    }

    /* Email */
    .user-email {
        font-size: 0.875rem;
        color: var(--gray-600);
        font-weight: 500;
    }

    /* Status Badge */
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.875rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .status-active {
        background: var(--success-light);
        color: var(--success);
    }

    .status-blocked {
        background: var(--danger-light);
        color: var(--danger);
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .status-dot.active { background: var(--success); }
    .status-dot.blocked { background: var(--danger); }

    /* Date */
    .date-display {
        font-size: 0.875rem;
        color: var(--gray-500);
        font-weight: 500;
    }

    /* Action Buttons */
    .action-group {
        display: flex;
        gap: 0.375rem;
        flex-wrap: wrap;
        justify-content: center;
    }

    .btn-action {
        padding: 0.5rem 0.75rem;
        border-radius: var(--radius-sm);
        font-size: 0.8125rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-edit {
        background: var(--warning-light);
        color: var(--warning);
    }

    .btn-edit:hover {
        background: var(--warning);
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-unblock {
        background: var(--success-light);
        color: var(--success);
    }

    .btn-unblock:hover {
        background: var(--success);
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-block {
        background: var(--gray-100);
        color: var(--gray-600);
    }

    .btn-block:hover {
        background: var(--gray-600);
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-delete {
        background: var(--danger-light);
        color: var(--danger);
    }

    .btn-delete:hover {
        background: var(--danger);
        color: #fff;
        transform: translateY(-1px);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3.5rem 1.5rem;
        color: var(--gray-500);
    }

    .empty-state i {
        font-size: 3.5rem;
        margin-bottom: 1rem;
        display: block;
        color: var(--gray-300);
    }

    .empty-state h5 {
        font-weight: 700;
        color: var(--gray-700);
        margin-bottom: 0.5rem;
    }

    /* Pagination */
    .pagination-modern {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
    }

    .pagination-modern .pagination {
        gap: 0.25rem;
    }

    .pagination-modern .page-link {
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-sm);
        color: var(--gray-600);
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.5rem 0.875rem;
        transition: all 0.2s;
    }

    .pagination-modern .page-link:hover {
        background: var(--gray-50);
        border-color: var(--gray-300);
        color: var(--primary);
    }

    .pagination-modern .page-item.active .page-link {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
    }

    .pagination-modern .page-item.disabled .page-link {
        color: var(--gray-300);
        background: var(--gray-50);
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .table-responsive-wrap {
            overflow-x: auto;
        }
        .table-modern {
            min-width: 900px;
        }
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .search-box input {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container-fluid py-4 users-dashboard">

    
    <div class="page-header">
        <div class="page-title-group">
            <h4 class="page-title">
                <i class="bi bi-people-fill"></i>
                User Management
            </h4>
            <p class="page-subtitle">Manage all system users, roles, and access permissions</p>
        </div>
        <a href="<?php echo e(route('admin.users.create')); ?>" class="btn-create-user">
            <i class="bi bi-person-plus-fill"></i>
            Create User
        </a>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert-modern alert-success-modern">
            <i class="bi bi-check-circle-fill"></i>
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" onclick="this.parentElement.remove()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    <?php endif; ?>

    
    <?php if(session('error')): ?>
        <div class="alert-modern alert-danger-modern">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" onclick="this.parentElement.remove()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    <?php endif; ?>

    <?php
        $totalUsers = $users->total() ?? $users->count();
        $activeUsers = $users->filter(function($u) { return !$u->is_blocked; })->count();
        $blockedUsers = $users->filter(function($u) { return $u->is_blocked; })->count();
        $newUsers = $users->filter(function($u) { return $u->created_at && $u->created_at->diffInDays(now()) <= 7; })->count();
    ?>

    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Users</div>
                <div class="stat-value"><?php echo e($totalUsers); ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="bi bi-person-check-fill"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Active Users</div>
                <div class="stat-value"><?php echo e($activeUsers); ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red">
                <i class="bi bi-person-x-fill"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Blocked Users</div>
                <div class="stat-value"><?php echo e($blockedUsers); ?></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="bi bi-person-plus-fill"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">New (7 Days)</div>
                <div class="stat-value"><?php echo e($newUsers); ?></div>
            </div>
        </div>
    </div>

    
    <div class="main-card">
        <div class="card-header-custom">
            <div class="card-header-title">
                <i class="bi bi-list-check"></i>
                Users List
            </div>
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="userSearch" placeholder="Search users..." onkeyup="filterTable()">
            </div>
        </div>

        <div class="table-responsive-wrap">
            <table class="table-modern" id="usersTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>

                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <tr data-user-name="<?php echo e(strtolower($user->name)); ?>" data-user-email="<?php echo e(strtolower($user->email)); ?>">

                        <td class="fw-bold text-muted"><?php echo e($loop->iteration); ?></td>

                        
                        <td>
                            <div class="user-info">
                                <div class="user-avatar">
                                    <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                </div>
                                <div class="user-details">
                                    <span class="user-name"><?php echo e($user->name); ?></span>
                                    <span class="user-id">ID: <?php echo e($user->id); ?></span>
                                </div>
                            </div>
                        </td>

                        
                        <td>
                            <span class="user-email">
                                <i class="bi bi-envelope-fill me-1" style="color: var(--gray-400);"></i>
                                <?php echo e($user->email); ?>

                            </span>
                        </td>

                        
                        <td>
                            <?php if($user->is_blocked): ?>
                                <span class="status-pill status-blocked">
                                    <span class="status-dot blocked"></span>
                                    Blocked
                                </span>
                            <?php else: ?>
                                <span class="status-pill status-active">
                                    <span class="status-dot active"></span>
                                    Active
                                </span>
                            <?php endif; ?>
                        </td>

                        
                        <td>
                            <span class="date-display">
                                <i class="bi bi-calendar3 me-1" style="color: var(--gray-400);"></i>
                                <?php echo e($user->created_at?->format('M d, Y') ?? 'N/A'); ?>

                            </span>
                        </td>

                        
                        <td>
                            <div class="action-group">

                                
                                <a href="<?php echo e(route('admin.users.edit', $user)); ?>"
                                   class="btn-action btn-edit"
                                   title="Edit User">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                
                                <?php if($user->is_blocked): ?>
                                    <form action="<?php echo e(route('admin.users.unblock', $user)); ?>"
                                          method="POST"
                                          style="display:inline-block;"
                                          onsubmit="return confirm('Unblock this user?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <button type="submit" class="btn-action btn-unblock" title="Unblock User">
                                            <i class="bi bi-unlock-fill"></i>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <form action="<?php echo e(route('admin.users.block', $user)); ?>"
                                          method="POST"
                                          style="display:inline-block;"
                                          onsubmit="return confirm('Block this user?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <button type="submit" class="btn-action btn-block" title="Block User">
                                            <i class="bi bi-lock-fill"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>

                                
                                <form action="<?php echo e(route('admin.users.destroy', $user)); ?>"
                                      method="POST"
                                      style="display:inline-block;"
                                      onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn-action btn-delete" title="Delete User">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-people"></i>
                                <h5>No Users Found</h5>
                                <p>There are no users in the system yet.</p>
                                <a href="<?php echo e(route('admin.users.create')); ?>" class="btn-create-user" style="margin-top: 1rem;">
                                    <i class="bi bi-person-plus-fill"></i>
                                    Create First User
                                </a>
                            </div>
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>

    
    <?php if($users->hasPages()): ?>
        <div class="pagination-modern">
            <?php echo e($users->links()); ?>

        </div>
    <?php endif; ?>

</div>


<script>
    function filterTable() {
        const input = document.getElementById('userSearch');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('usersTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        for (let i = 0; i < rows.length; i++) {
            const userName = rows[i].getAttribute('data-user-name');
            const userEmail = rows[i].getAttribute('data-user-email');
            if (userName || userEmail) {
                const match = (userName && userName.includes(filter)) || (userEmail && userEmail.includes(filter));
                rows[i].style.display = match ? '' : 'none';
            }
        }
    }
</script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/Admin/users/index.blade.php ENDPATH**/ ?>