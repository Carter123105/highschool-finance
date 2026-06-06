<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">🎓 School Finance</h2>
                    <p class="text-muted mb-0">Admin</p>
                </div>
            </div>

            <h4>Edit Class Invoice</h4>
            <p class="text-muted">Update fee structure for <?php echo e($invoice->schoolClass->name ?? 'N/A'); ?> — Academic Year <?php echo e($invoice->academicYear->name ?? 'N/A'); ?></p>

            <a href="<?php echo e(route('invoices.index')); ?>" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Back to Invoices
            </a>

            
            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <h5><i class="fas fa-exclamation-triangle"></i> Please fix the following errors:</h5>
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            
            <form action="<?php echo e(route('invoices.update', $invoice)); ?>" method="POST" id="invoiceForm">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                
                <input type="hidden" name="class_id" value="<?php echo e($invoice->class_id); ?>">
                <input type="hidden" name="student_type" value="<?php echo e($invoice->student_type); ?>">
                <input type="hidden" name="academic_year_id" value="<?php echo e($invoice->academic_year_id); ?>">
                <input type="hidden" name="section_id" value="<?php echo e($invoice->section_id); ?>">

                <div class="card">
                    <div class="card-body">

                        <h5 class="card-title mb-3">Invoice Settings</h5>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Invoice Number</label>
                                    <input type="text" class="form-control" value="<?php echo e($invoice->invoice_no); ?>" disabled>
                                    <small class="text-muted">Auto-generated, cannot be changed</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Class</label>
                                    <input type="text" class="form-control" value="<?php echo e($invoice->schoolClass->name ?? 'N/A'); ?>" disabled>
                                    <small class="text-muted">Class cannot be changed</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Academic Year</label>
                                    <input type="text" class="form-control" value="<?php echo e($invoice->academicYear->name ?? 'N/A'); ?>" disabled>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Applies To (Student Type)</label>
                                    <input type="text" class="form-control" value="<?php echo e($invoice->student_type); ?> Students" disabled>
                                    <small class="text-muted">Student type cannot be changed</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Due Date</label>
                                    <input type="date" name="due_date" class="form-control <?php $__errorArgs = ['due_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           value="<?php echo e(old('due_date', $invoice->due_date ? date('Y-m-d', strtotime($invoice->due_date)) : '')); ?>">
                                    <?php $__errorArgs = ['due_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Invoice Status</label>
                                    <input type="text" class="form-control" value="<?php echo e($invoice->status); ?>" disabled>
                                    <small class="text-muted">Status is auto-calculated based on payments</small>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Class-Level Invoice:</strong> Changes here will affect the fee structure for all students in this class. 
                            Individual student invoices will be generated based on this template.
                        </div>

                        <hr>

                        
                        
                        
                        <h5 class="mb-3">Fee Structure <span class="badge bg-secondary" id="itemCount"><?php echo e($invoice->invoiceItems->count()); ?> items</span></h5>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="feeTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 30%;">Fee Category <span class="text-danger">*</span></th>
                                        <th style="width: 20%;">Amount ($) <span class="text-danger">*</span></th>
                                        <th style="width: 20%;">Discount ($)</th>
                                        <th style="width: 20%;">Subtotal ($)</th>
                                        <th style="width: 10%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="feeItemsBody">
                                    <?php
                                        $oldItems = old('fee_category_id');
                                        $items = [];

                                        if ($oldItems !== null) {
                                            // Form was submitted with errors - use old input
                                            foreach ($oldItems as $i => $catId) {
                                                $items[] = [
                                                    'fee_category_id' => $catId,
                                                    'amount' => old('amount.'.$i, 0),
                                                    'discount' => old('discount.'.$i, 0),
                                                ];
                                            }
                                        } else {
                                            // First load - use existing invoice items
                                            foreach ($invoice->invoiceItems as $item) {
                                                $items[] = [
                                                    'fee_category_id' => $item->fee_category_id,
                                                    'amount' => $item->amount,
                                                    'discount' => $item->discount,
                                                ];
                                            }
                                        }

                                        if (empty($items)) {
                                            $items = [['fee_category_id' => '', 'amount' => 0, 'discount' => 0]];
                                        }
                                    ?>

                                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $feeCategoryId = $item['fee_category_id'] ?? '';
                                            $amount = $item['amount'] ?? 0;
                                            $discount = $item['discount'] ?? 0;
                                            $subtotal = max(0, (float)$amount - (float)$discount);
                                        ?>

                                        <tr class="fee-item-row" data-index="<?php echo e($index); ?>">
                                            <td>
                                                <select name="fee_category_id[<?php echo e($index); ?>]" 
                                                        class="form-select form-select-sm fee-category-select <?php $__errorArgs = ['fee_category_id.'.$index];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                        required>
                                                    <option value="">-- Select Category --</option>
                                                    <?php $__currentLoopData = $feeCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($category->id); ?>" <?php echo e($feeCategoryId == $category->id ? 'selected' : ''); ?>>
                                                            <?php echo e($category->name); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <?php $__errorArgs = ['fee_category_id.'.$index];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </td>
                                            <td>
                                                <input type="number" 
                                                       name="amount[<?php echo e($index); ?>]" 
                                                       class="form-control form-control-sm amount-input <?php $__errorArgs = ['amount.'.$index];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                       value="<?php echo e(number_format((float)$amount, 2, '.', '')); ?>" 
                                                       step="0.01" 
                                                       min="0" 
                                                       required>
                                                <?php $__errorArgs = ['amount.'.$index];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </td>
                                            <td>
                                                <input type="number" 
                                                       name="discount[<?php echo e($index); ?>]" 
                                                       class="form-control form-control-sm discount-input" 
                                                       value="<?php echo e(number_format((float)$discount, 2, '.', '')); ?>" 
                                                       step="0.01" 
                                                       min="0">
                                            </td>
                                            <td class="align-middle text-end">
                                                <span class="subtotal-display fw-bold"><?php echo e(number_format($subtotal, 2)); ?></span>
                                                <input type="hidden" class="subtotal-input" value="<?php echo e($subtotal); ?>">
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm remove-row" onclick="removeRow(this)" <?php echo e(count($items) <= 1 ? 'disabled' : ''); ?>>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-end">
                                            <button type="button" class="btn btn-success btn-sm" id="addRowBtn">
                                                <i class="fas fa-plus"></i> Add Another Fee Category
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td colspan="3" class="text-end fw-bold fs-5">Total Invoice Amount</td>
                                        <td colspan="2" class="fw-bold fs-5 text-primary text-end">
                                            $<span id="totalAmount"><?php echo e(number_format($invoice->total_amount ?? 0, 2)); ?></span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-users"></i>
                            Applied to all students in this class
                        </div>

                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="<?php echo e(route('invoices.index')); ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    let rowIndex = <?php echo e(count($items)); ?>;

    document.getElementById('addRowBtn').addEventListener('click', function() {
        const tbody = document.getElementById('feeItemsBody');
        const newRow = document.createElement('tr');
        newRow.className = 'fee-item-row';
        newRow.setAttribute('data-index', rowIndex);

        const feeCategoriesOptions = `<?php $__currentLoopData = $feeCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>`;

        newRow.innerHTML = `
            <td>
                <select name="fee_category_id[${rowIndex}]" class="form-select form-select-sm fee-category-select" required>
                    <option value="">-- Select Category --</option>
                    ${feeCategoriesOptions}
                </select>
            </td>
            <td>
                <input type="number" name="amount[${rowIndex}]" class="form-control form-control-sm amount-input" value="0.00" step="0.01" min="0" required>
            </td>
            <td>
                <input type="number" name="discount[${rowIndex}]" class="form-control form-control-sm discount-input" value="0.00" step="0.01" min="0">
            </td>
            <td class="align-middle text-end">
                <span class="subtotal-display fw-bold">0.00</span>
                <input type="hidden" class="subtotal-input" value="0">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-row" onclick="removeRow(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;

        tbody.appendChild(newRow);
        rowIndex++;
        updateItemCount();
        attachCalculations(newRow);

        document.querySelectorAll('.remove-row').forEach(btn => btn.disabled = false);
    });

    function removeRow(button) {
        const row = button.closest('tr');
        const tbody = document.getElementById('feeItemsBody');

        if (tbody.children.length > 1) {
            row.remove();
            reindexRows();
            calculateTotal();
            updateItemCount();

            if (tbody.children.length === 1) {
                tbody.querySelector('.remove-row').disabled = true;
            }
        } else {
            alert('At least one fee item is required.');
        }
    }

    function reindexRows() {
        const rows = document.querySelectorAll('.fee-item-row');
        rows.forEach((row, index) => {
            row.setAttribute('data-index', index);
            row.querySelector('.fee-category-select').name = `fee_category_id[${index}]`;
            row.querySelector('.amount-input').name = `amount[${index}]`;
            row.querySelector('.discount-input').name = `discount[${index}]`;
        });
        rowIndex = rows.length;
    }

    function updateItemCount() {
        const count = document.querySelectorAll('.fee-item-row').length;
        document.getElementById('itemCount').textContent = count + ' items';
    }

    function calculateRow(row) {
        const amount = parseFloat(row.querySelector('.amount-input').value) || 0;
        const discount = parseFloat(row.querySelector('.discount-input').value) || 0;
        const subtotal = Math.max(0, amount - discount);

        row.querySelector('.subtotal-display').textContent = subtotal.toFixed(2);
        row.querySelector('.subtotal-input').value = subtotal;
    }

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal-input').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('totalAmount').textContent = total.toFixed(2);
    }

    function attachCalculations(row) {
        const amountInput = row.querySelector('.amount-input');
        const discountInput = row.querySelector('.discount-input');

        amountInput.addEventListener('input', function() {
            calculateRow(row);
            calculateTotal();
        });

        discountInput.addEventListener('input', function() {
            calculateRow(row);
            calculateTotal();
        });
    }

    document.querySelectorAll('.fee-item-row').forEach(row => {
        attachCalculations(row);
    });

    calculateTotal();
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/invoices/edit.blade.php ENDPATH**/ ?>