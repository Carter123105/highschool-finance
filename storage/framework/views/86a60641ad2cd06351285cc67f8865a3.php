

<?php $__env->startSection('content'); ?>
<div class="container">
    
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1"><?php echo e($class->name); ?> - Students</h3>
            <p class="text-muted mb-0">
                <?php echo e($students->total()); ?> student(s) found 
                <?php if($studentType): ?>
                    | Filter: <span class="badge bg-secondary"><?php echo e($studentType); ?></span>
                <?php endif; ?>
            </p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="printSelected()" class="btn btn-success" id="print-selected-btn" style="display: none;">
                <i class="bi bi-printer"></i> Print Selected (<span id="selected-count">0</span>)
            </button>
            <a href="<?php echo e(route('finance.classes.students.print', ['classId' => $class->id] + request()->all())); ?>" 
               class="btn btn-dark" target="_blank">
                <i class="bi bi-printer"></i> Print All
            </a>
            <a href="<?php echo e(route('finance.classes')); ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Classes
            </a>
        </div>
    </div>

    
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('finance.classes.students', $class->id)); ?>" class="d-flex gap-2 align-items-end">
                
                <div>
                    <label class="form-label">Student Type</label>
                    <select name="student_type" class="form-select" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        <option value="Old" <?php echo e(request('student_type') == 'Old' ? 'selected' : ''); ?>>Old Students</option>
                        <option value="New" <?php echo e(request('student_type') == 'New' ? 'selected' : ''); ?>>New Students</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" 
                           value="<?php echo e(request('search')); ?>" 
                           placeholder="Name or ID...">
                </div>

                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="<?php echo e(route('finance.classes.students', $class->id)); ?>" class="btn btn-secondary">Reset</a>
            </form>
        </div>
    </div>

    
    <div class="mb-2 d-flex gap-2">
        <button type="button" onclick="selectAllVisible()" class="btn btn-sm btn-outline-primary">Select All on Page</button>
        <button type="button" onclick="deselectAll()" class="btn btn-sm btn-outline-secondary">Deselect All</button>
        <button type="button" onclick="selectByType('New')" class="btn btn-sm btn-outline-success">Select New Only</button>
        <button type="button" onclick="selectByType('Old')" class="btn btn-sm btn-outline-warning">Select Old Only</button>
    </div>

    
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center" style="width: 40px;">
                            <input type="checkbox" id="select-all-checkbox" onclick="toggleAll(this)">
                        </th>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th class="text-center">Type</th>
                        <th>Section</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th class="text-center">Date Joined</th>
                        <th class="text-center" style="width: 80px;">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr data-student-type="<?php echo e($student->student_type); ?>">
                            <td class="text-center">
                                <input type="checkbox" 
                                       class="student-checkbox" 
                                       value="<?php echo e($student->id); ?>"
                                       data-id="<?php echo e($student->id); ?>"
                                       data-student-id="<?php echo e($student->student_id); ?>"
                                       data-name="<?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?>"
                                       data-type="<?php echo e($student->student_type); ?>"
                                       data-section="<?php echo e($student->section?->name ?? '-'); ?>"
                                       data-email="<?php echo e($student->email ?? '-'); ?>"
                                       data-phone="<?php echo e($student->phone ?? '-'); ?>"
                                       data-date="<?php echo e($student->created_at?->format('M d, Y') ?? '-'); ?>"
                                       onchange="updateSelection()">
                            </td>
                            <td class="text-center"><?php echo e($loop->iteration + ($students->currentPage() - 1) * $students->perPage()); ?></td>
                            <td class="fw-bold"><?php echo e($student->student_id); ?></td>
                            <td><?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?></td>
                            <td class="text-center">
                                <?php if($student->student_type == 'New'): ?>
                                    <span class="badge bg-success">New</span>
                                <?php elseif($student->student_type == 'Old'): ?>
                                    <span class="badge bg-warning text-dark">Old</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($student->section?->name ?? '-'); ?></td>
                            <td><?php echo e($student->email ?? '-'); ?></td>
                            <td><?php echo e($student->phone ?? '-'); ?></td>
                            <td class="text-center"><?php echo e($student->created_at?->format('M d, Y') ?? '-'); ?></td>
                            <td class="text-center">
                                <a href="<?php echo e(route('students.show', $student->id)); ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                No students found in this class
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            
            <div class="d-flex justify-content-end mt-3">
                <?php echo e($students->links()); ?>

            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Store selected student IDs
    let selectedIds = new Set();

    function getCheckboxData(checkbox) {
        return {
            id: checkbox.value,
            student_id: checkbox.dataset.studentId,
            name: checkbox.dataset.name,
            type: checkbox.dataset.type,
            section: checkbox.dataset.section,
            email: checkbox.dataset.email,
            phone: checkbox.dataset.phone,
            date: checkbox.dataset.date
        };
    }

    function updateSelection() {
        const checkboxes = document.querySelectorAll('.student-checkbox');
        
        checkboxes.forEach(cb => {
            if (cb.checked) {
                selectedIds.add(cb.value);
            } else {
                selectedIds.delete(cb.value);
            }
        });
        
        // Update master checkbox
        const masterCheckbox = document.getElementById('select-all-checkbox');
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        const someChecked = Array.from(checkboxes).some(cb => cb.checked);
        
        if (allChecked) {
            masterCheckbox.checked = true;
            masterCheckbox.indeterminate = false;
        } else if (someChecked) {
            masterCheckbox.checked = false;
            masterCheckbox.indeterminate = true;
        } else {
            masterCheckbox.checked = false;
            masterCheckbox.indeterminate = false;
        }
        
        updateUI();
    }

    function toggleAll(masterCheckbox) {
        const checkboxes = document.querySelectorAll('.student-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = masterCheckbox.checked;
            if (masterCheckbox.checked) {
                selectedIds.add(cb.value);
            } else {
                selectedIds.delete(cb.value);
            }
        });
        updateUI();
    }

    function selectAllVisible() {
        const checkboxes = document.querySelectorAll('.student-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = true;
            selectedIds.add(cb.value);
        });
        document.getElementById('select-all-checkbox').checked = true;
        document.getElementById('select-all-checkbox').indeterminate = false;
        updateUI();
    }

    function deselectAll() {
        const checkboxes = document.querySelectorAll('.student-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = false;
        });
        selectedIds.clear();
        document.getElementById('select-all-checkbox').checked = false;
        document.getElementById('select-all-checkbox').indeterminate = false;
        updateUI();
    }

    function selectByType(type) {
        // First deselect all
        deselectAll();
        
        // Then select only matching type
        const rows = document.querySelectorAll('tr[data-student-type]');
        rows.forEach(row => {
            if (row.dataset.studentType === type) {
                const checkbox = row.querySelector('.student-checkbox');
                if (checkbox) {
                    checkbox.checked = true;
                    selectedIds.add(checkbox.value);
                }
            }
        });
        
        updateUI();
    }

    function updateUI() {
        const count = selectedIds.size;
        const btn = document.getElementById('print-selected-btn');
        const countSpan = document.getElementById('selected-count');
        
        countSpan.textContent = count;
        
        if (count > 0) {
            btn.style.display = 'inline-block';
        } else {
            btn.style.display = 'none';
        }
    }

    function printSelected() {
        if (selectedIds.size === 0) {
            alert('Please select at least one student.');
            return;
        }

        // Get data from checked checkboxes
        const checkboxes = document.querySelectorAll('.student-checkbox:checked');
        const students = [];
        
        checkboxes.forEach(cb => {
            students.push(getCheckboxData(cb));
        });

        // Build print HTML
        let rowsHtml = '';
        students.forEach((student, index) => {
            const typeBadge = student.type === 'New' 
                ? '<span class="badge badge-new">New</span>'
                : student.type === 'Old'
                ? '<span class="badge badge-old">Old</span>'
                : '<span class="badge badge-na">N/A</span>';
            
            rowsHtml += `
                <tr>
                    <td class="center">${index + 1}</td>
                    <td><strong>${student.student_id}</strong></td>
                    <td>${student.name}</td>
                    <td class="center">${typeBadge}</td>
                    <td>${student.section}</td>
                    <td>${student.email}</td>
                    <td>${student.phone}</td>
                    <td class="center">${student.date}</td>
                </tr>
            `;
        });

        const printWindow = window.open('', '_blank', 'width=1200,height=800');
        
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Selected Students</title>
                <style>
                    @page { size: A4 landscape; margin: 15mm; }
                    * { margin: 0; padding: 0; box-sizing: border-box; }
                    body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; line-height: 1.4; color: #333; padding: 20px; }
                    
                    .print-header { text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #2c3e50; }
                    .print-header h1 { font-size: 20pt; font-weight: bold; color: #2c3e50; margin-bottom: 5px; }
                    .print-header h2 { font-size: 14pt; color: #555; margin-bottom: 5px; }
                    .print-header .meta { font-size: 9pt; color: #777; }
                    
                    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                    thead th { background-color: #2c3e50; color: white; font-weight: bold; padding: 10px 8px; text-align: left; border: 1px solid #1a252f; }
                    thead th.center { text-align: center; }
                    tbody td { padding: 8px; border: 1px solid #999; vertical-align: middle; }
                    tbody tr:nth-child(even) { background-color: #f5f5f5; }
                    
                    .center { text-align: center; }
                    .badge { display: inline-block; padding: 3px 8px; border-radius: 3px; font-size: 8pt; font-weight: bold; }
                    .badge-new { background-color: #28a745; color: white; }
                    .badge-old { background-color: #ffc107; color: #000; }
                    .badge-na { background-color: #6c757d; color: white; }
                    
                    .print-footer { margin-top: 20px; padding-top: 15px; border-top: 1px solid #999; text-align: center; }
                    .print-footer p { font-size: 9pt; color: #777; }
                    .print-footer .total { font-weight: bold; color: #333; }
                    
                    .print-btn { position: fixed; top: 20px; right: 20px; padding: 10px 20px; background: #2c3e50; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 11pt; }
                    .print-btn:hover { background: #1a252f; }
                    @media print { .print-btn { display: none; } }
                </style>
            </head>
            <body>
                <button onclick="window.print()" class="print-btn">🖨️ Print This Page</button>
                
                <div class="print-header">
                    <h1><?php echo e(config('app.name', 'School Finance')); ?></h1>
                    <h2><?php echo e($class->name); ?> - Selected Students</h2>
                    <p class="meta">
                        Printed on: ${new Date().toLocaleString('en-US', {month: 'long', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit'})}
                        | Selected: ${students.length} students
                    </p>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th class="center" style="width: 50px;">#</th>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th class="center">Type</th>
                            <th>Section</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th class="center">Date Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${rowsHtml}
                    </tbody>
                </table>

                <div class="print-footer">
                    <p>
                        --- End of Selected Students ---<br>
                        Total Selected: <span class="total">${students.length}</span>
                    </p>
                </div>
            </body>
            </html>
        `);
        
        printWindow.document.close();
    }

    // Restore selections on page load
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.student-checkbox');
        checkboxes.forEach(cb => {
            if (selectedIds.has(cb.value)) {
                cb.checked = true;
            }
        });
        updateSelection();
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/finance/class_students.blade.php ENDPATH**/ ?>