<?php $__env->startSection('content'); ?>

<div class="container-fluid py-4">

    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Record Payment</h3>
            <p class="text-muted mb-0">Each student pays the full invoice amount individually</p>
        </div>
        <a href="<?php echo e(route('payments.index')); ?>" class="btn btn-dark">← Back</a>
    </div>

    
    <form method="GET" action="<?php echo e(route('payments.create')); ?>" class="mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white fw-bold">Filter Class & Section</div>
            <div class="card-body">
                <div class="row g-3">
                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Select Class <span class="text-danger">*</span></label>
                        <select name="class_id" class="form-select" onchange="this.form.submit()" required>
                            <option value="">Select Class</option>
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>" <?php if($selectedClass == $class->id): echo 'selected'; endif; ?>>
                                    <?php echo e($class->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Section</label>
                        <select name="section_name" class="form-select" onchange="this.form.submit()">
                            <option value="">All Sections</option>
                            <?php $__currentLoopData = $sections->pluck('name')->unique()->sort()->values(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sectionName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($sectionName); ?>" <?php if($selectedSection == $sectionName): echo 'selected'; endif; ?>>
                                    Section <?php echo e($sectionName); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Student Type <span class="text-danger">*</span></label>
                        <select name="student_type" class="form-select" onchange="this.form.submit()" required>
                            <option value="">Select Type</option>
                            <option value="Old" <?php if($selectedType === 'Old'): echo 'selected'; endif; ?>>Old Students</option>
                            <option value="New" <?php if($selectedType === 'New'): echo 'selected'; endif; ?>>New Students</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>

    
    <?php if(app()->environment('local')): ?>
        <div class="alert alert-secondary mb-3">
            <small>
                <strong>Debug:</strong> Class: <?php echo e($selectedClass ?: 'none'); ?> |
                Section: <?php echo e($selectedSection ?: 'all'); ?> |
                Type: <?php echo e($selectedType ?: 'none'); ?> |
                Students: <?php echo e($students->count()); ?> |
                Invoices: <?php echo e($invoices->count()); ?>

                <?php if(!empty($debugInfo['section_ids_found'])): ?>
                    | Section IDs: <?php echo e(implode(', ', $debugInfo['section_ids_found'])); ?>

                <?php endif; ?>
            </small>
        </div>
        <?php if($invoices->isEmpty() && $selectedClass && $selectedType): ?>
            <div class="alert alert-warning">
                <small>
                    <strong>SQL Debug:</strong> Looking for invoices with class_id=<?php echo e($selectedClass); ?>,
                    student_type=<?php echo e($selectedType); ?>,
                    section_id in [<?php echo e(implode(', ', $debugInfo['section_ids_found'] ?? [])); ?>] or NULL
                </small>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    
    <?php if($selectedClass && $selectedType && $invoices->isEmpty()): ?>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>No invoices found</strong> for <?php echo e($selectedType); ?> students
            <?php echo e($selectedSection ? "in Section {$selectedSection}" : 'in all sections'); ?>

            of this class.
            <hr class="my-2">
            <small class="text-muted">
                <strong>Possible causes:</strong><br>
                1. No class invoice exists for this class/section/type combination.<br>
                2. Existing invoices have status "Paid" and are hidden (now fixed).<br>
                3. Section filter is too strict (try "All Sections").<br>
                4. Invoice was created for a different academic year.<br>
                <strong>Solution:</strong> Go to Finance → Invoices → Create a new class invoice.
            </small>
        </div>
    <?php endif; ?>

    
    <?php if($invoices->isNotEmpty() && $students->isNotEmpty()): ?>
        <form method="POST" action="<?php echo e(route('payments.store')); ?>">
            <?php echo csrf_field(); ?>

            <input type="hidden" name="class_id" value="<?php echo e($selectedClass); ?>">
            <input type="hidden" name="student_type" value="<?php echo e($selectedType); ?>">
            <input type="hidden" name="section_name" value="<?php echo e($selectedSection); ?>">

            <div class="row g-4">

                
                <div class="col-lg-5">

                    
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-dark text-white fw-bold">Paying Student</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Select Student <span class="text-danger">*</span></label>
                                <select name="student_id" id="student_id" class="form-select" required>
                                    <option value="">Select Student</option>
                                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($student->id); ?>"
                                            data-section-id="<?php echo e($student->section_id); ?>"
                                            data-section-name="<?php echo e($student->section?->name ?? ''); ?>">
                                            <?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?>

                                            (<?php echo e($student->student_type); ?>)
                                            <?php if($student->section): ?>
                                                - Section <?php echo e($student->section->name); ?>

                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="alert alert-info mb-0">
                                <small>
                                    <strong>Individual Payment:</strong> Each student must pay the full invoice amount.
                                    This is NOT a shared payment.
                                </small>
                            </div>
                        </div>
                    </div>

                    
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-success text-white fw-bold">Class Invoice</div>
                        <div class="card-body">
                            <label class="form-label fw-semibold">Select Invoice <span class="text-danger">*</span></label>
                            <select name="invoice_id" id="invoice_id" class="form-select" required>
                                <option value="">Select Invoice</option>
                                <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $sectionName = $sections->firstWhere('id', $invoice->section_id)?->name;
                                        $statusColor = match($invoice->status) {
                                            'Paid'    => 'success',
                                            'Partial' => 'warning',
                                            default   => 'secondary',
                                        };
                                    ?>
                                    <option value="<?php echo e($invoice->id); ?>"
                                        data-total="<?php echo e($invoice->total_amount); ?>"
                                        data-section-id="<?php echo e($invoice->section_id); ?>"
                                        data-section-name="<?php echo e($sectionName ?? 'All Sections'); ?>"
                                        data-status="<?php echo e($invoice->status); ?>">
                                        #<?php echo e($invoice->invoice_no); ?> - LRD <?php echo e(number_format($invoice->total_amount, 2)); ?>

                                        <?php echo e($sectionName ? "(Section {$sectionName})" : '(All Sections)'); ?>

                                        - <?php echo e($invoice->student_type); ?>

                                        <span class="badge bg-<?php echo e($statusColor); ?>"><?php echo e($invoice->status); ?></span>
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>

                            
                            <div class="mt-3">
                                <div class="border rounded p-3 bg-light">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="fw-semibold">Invoice Amount (Per Student)</span>
                                        <span id="invoiceAmountText" class="fw-bold text-dark">--</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="fw-semibold">Student Type</span>
                                        <span id="invoiceTypeText" class="fw-bold text-primary">--</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-semibold">Section</span>
                                        <span id="invoiceSectionText" class="fw-bold text-secondary">--</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                
                <div class="col-lg-7">

                    
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-info text-white fw-bold">Student's Payment History</div>
                        <div class="card-body" id="paymentHistory">
                            <div class="alert alert-secondary mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Select a student to see their payment history for this invoice.
                            </div>
                        </div>
                    </div>

                    
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-secondary text-white fw-bold">Payment Information</div>
                        <div class="card-body">

                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Amount Paying Now <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">LRD</span>
                                    <input type="number" step="0.01" min="0.01" name="amount_paid" id="amount_paid"
                                        class="form-control <?php $__errorArgs = ['amount_paid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        placeholder="Enter amount" required>
                                </div>

                                <?php $__errorArgs = ['amount_paid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <?php echo e($message); ?>

                                    </div>
                                <?php else: ?>
                                    <small id="amountHint" class="text-muted">Each student must pay the full invoice amount individually</small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                <small id="overpayWarning" class="text-danger d-none d-block mt-1">
                                    <i class="fas fa-exclamation-triangle"></i> Amount cannot exceed remaining balance for this student
                                </small>
                            </div>

                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Payment Date <span class="text-danger">*</span></label>
                                <input type="date" name="payment_date" value="<?php echo e(now()->format('Y-m-d')); ?>" class="form-control" required>
                            </div>

                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Payment Method <span class="text-danger">*</span></label>
                                <select name="payment_method" id="payment_method" class="form-select" required>
                                    <option value="">Select Method</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Bank">Bank</option>
                                    <option value="Mobile Money">Mobile Money</option>
                                </select>
                            </div>

                            
                            <div class="mb-3 d-none" id="mobileMoneyRefField">
                                <label class="form-label fw-semibold">Mobile Money Reference No <span class="text-danger">*</span></label>
                                <input type="text" name="reference_number" id="reference_number"
                                    class="form-control <?php $__errorArgs = ['reference_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="e.g. MM123456789" maxlength="50">
                                <?php $__errorArgs = ['reference_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <?php echo e($message); ?>

                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <small class="text-muted">Enter the transaction reference number from the mobile money provider</small>
                            </div>

                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Notes</label>
                                <textarea name="notes" class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="2" placeholder="Optional notes..."><?php echo e(old('notes')); ?></textarea>
                                <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div class="alert alert-light border mb-3">
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="text-muted small">Invoice Amount</div>
                                        <div id="summaryTotal" class="fw-bold">LRD 0.00</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-muted small">This Payment</div>
                                        <div id="summaryPayment" class="fw-bold text-primary">LRD 0.00</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-muted small">Student Balance After</div>
                                        <div id="summaryBalance" class="fw-bold text-danger">LRD 0.00</div>
                                    </div>
                                </div>
                            </div>

                            
                            <button type="submit" id="submitBtn" class="btn btn-primary w-100 fw-bold py-2" disabled>
                                <i class="fas fa-check-circle me-2"></i>Record Payment
                            </button>
                        </div>
                    </div>

                </div>

            </div>

        </form>
    <?php endif; ?>

</div>

<script>
const PaymentForm = {
    state: {
        invoiceAmount: 0,
        studentTotalPaid: 0,
        studentRemaining: 0,
    },

    elements: {
        studentSelect: document.getElementById('student_id'),
        invoiceSelect: document.getElementById('invoice_id'),
        amountInput: document.getElementById('amount_paid'),
        methodSelect: document.getElementById('payment_method'),
        refField: document.getElementById('mobileMoneyRefField'),
        refInput: document.getElementById('reference_number'),
        submitBtn: document.getElementById('submitBtn'),
        overpayWarning: document.getElementById('overpayWarning'),
        paymentHistory: document.getElementById('paymentHistory'),
        summaryTotal: document.getElementById('summaryTotal'),
        summaryPayment: document.getElementById('summaryPayment'),
        summaryBalance: document.getElementById('summaryBalance'),
        invoiceAmountText: document.getElementById('invoiceAmountText'),
        invoiceTypeText: document.getElementById('invoiceTypeText'),
        invoiceSectionText: document.getElementById('invoiceSectionText'),
    },

    init() {
        this.bindEvents();
        this.autoSelectFirstStudent();
    },

    bindEvents() {
        this.elements.studentSelect.addEventListener('change', () => this.onStudentChange());
        this.elements.invoiceSelect.addEventListener('change', () => this.updateInvoiceDetails());
        this.elements.amountInput.addEventListener('input', () => this.updateSummary());
        this.elements.methodSelect.addEventListener('change', () => this.toggleMobileMoneyField());
    },

    autoSelectFirstStudent() {
        if (this.elements.studentSelect.options.length > 1) {
            this.elements.studentSelect.selectedIndex = 1;
            this.elements.studentSelect.dispatchEvent(new Event('change'));
        }
    },

    onStudentChange() {
        this.filterInvoicesBySection();
        this.updateInvoiceDetails();
    },

    filterInvoicesBySection() {
        const studentOption = this.elements.studentSelect.options[this.elements.studentSelect.selectedIndex];
        const studentSectionId = studentOption?.getAttribute('data-section-id');

        Array.from(this.elements.invoiceSelect.options).forEach(option => {
            if (!option.value) return;

            const invoiceSectionId = option.getAttribute('data-section-id');
            const shouldShow = !studentSectionId || !invoiceSectionId || invoiceSectionId === studentSectionId;
            option.hidden = !shouldShow;
        });

        const visibleOptions = Array.from(this.elements.invoiceSelect.options).filter(o => !o.hidden && o.value);
        this.elements.invoiceSelect.value = visibleOptions.length >= 1 ? visibleOptions[0].value : '';
    },

    updateInvoiceDetails() {
        const selected = this.elements.invoiceSelect.options[this.elements.invoiceSelect.selectedIndex];

        if (!selected?.value) {
            this.elements.invoiceAmountText.textContent = '--';
            this.elements.invoiceTypeText.textContent = '--';
            this.elements.invoiceSectionText.textContent = '--';
            this.state.invoiceAmount = 0;
            this.updateStudentBalance();
            return;
        }

        this.state.invoiceAmount = parseFloat(selected.getAttribute('data-total')) || 0;
        const typeText = selected.text.includes('Old') ? 'Old' : (selected.text.includes('New') ? 'New' : '--');
        const sectionName = selected.getAttribute('data-section-name') || '--';

        this.elements.invoiceAmountText.textContent = `LRD ${this.state.invoiceAmount.toFixed(2)}`;
        this.elements.invoiceTypeText.textContent = typeText;
        this.elements.invoiceSectionText.textContent = sectionName;

        this.updateStudentBalance();
    },

    async updateStudentBalance() {
        const studentId = this.elements.studentSelect.value;
        const invoiceId = this.elements.invoiceSelect.value;

        if (!studentId || !invoiceId || !this.state.invoiceAmount) {
            this.state.studentTotalPaid = 0;
            this.state.studentRemaining = 0;
            this.updateSummary();
            return;
        }

        try {
            const response = await fetch(`<?php echo e(route('payments.student-balance')); ?>?student_id=${studentId}&invoice_id=${invoiceId}`);
            const data = await response.json();

            this.state.studentTotalPaid = parseFloat(data.paid) || 0;
            this.state.studentRemaining = Math.max(0, this.state.invoiceAmount - this.state.studentTotalPaid);

            this.renderPaymentHistory();
            this.setAmountInputDefaults();
            this.updateSummary();
        } catch (error) {
            console.error('Error fetching balance:', error);
            this.state.studentTotalPaid = 0;
            this.state.studentRemaining = this.state.invoiceAmount;
            this.updateSummary();
        }
    },

    renderPaymentHistory() {
        const { studentTotalPaid, studentRemaining, invoiceAmount } = this.state;

        let html;
        if (studentTotalPaid > 0) {
            html = `
                <div class="alert alert-success mb-0">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>LRD ${studentTotalPaid.toFixed(2)}</strong> already paid.
                    Remaining balance: <strong>LRD ${studentRemaining.toFixed(2)}</strong>
                </div>
            `;
        } else {
            html = `
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    No payments recorded yet. Full amount due: <strong>LRD ${invoiceAmount.toFixed(2)}</strong>
                </div>
            `;
        }
        this.elements.paymentHistory.innerHTML = html;
    },

    setAmountInputDefaults() {
        const { studentRemaining } = this.state;
        this.elements.amountInput.max = studentRemaining.toFixed(2);
        this.elements.amountInput.placeholder = `Max: ${studentRemaining.toFixed(2)}`;

        if (studentRemaining > 0 && !this.elements.amountInput.value) {
            this.elements.amountInput.value = studentRemaining.toFixed(2);
        }
    },

    updateSummary() {
        const amount = parseFloat(this.elements.amountInput.value || 0);
        const newBalance = Math.max(0, this.state.studentRemaining - amount);
        const hasSelection = this.elements.studentSelect.value && this.elements.invoiceSelect.value;

        this.updateSubmitButton(amount, hasSelection, newBalance);
        this.updateWarning(amount, hasSelection);
        this.updateDisplay(amount, newBalance);
    },

    updateSubmitButton(amount, hasSelection, newBalance) {
        const { studentRemaining } = this.state;
        const isMobileMoney = this.elements.methodSelect.value === 'Mobile Money';
        const hasRef = this.elements.refInput.value.trim().length > 0;

        const isValid = hasSelection
            && studentRemaining > 0
            && amount > 0
            && amount <= studentRemaining
            && (!isMobileMoney || hasRef);

        this.elements.submitBtn.disabled = !isValid;
        this.elements.submitBtn.classList.toggle('btn-secondary', !isValid);
        this.elements.submitBtn.classList.toggle('btn-primary', isValid);
    },

    updateWarning(amount, hasSelection) {
        const { studentRemaining } = this.state;
        const warning = this.elements.overpayWarning;

        if (!hasSelection) {
            warning.className = 'text-danger d-none';
        } else if (studentRemaining <= 0) {
            warning.innerHTML = '<i class="fas fa-check-circle"></i> This student has already paid the full amount';
            warning.className = 'text-success d-block mt-1';
        } else if (amount > studentRemaining) {
            warning.innerHTML = `<i class="fas fa-exclamation-triangle"></i> Amount cannot exceed remaining balance of LRD ${studentRemaining.toFixed(2)}`;
            warning.className = 'text-danger d-block mt-1';
        } else {
            warning.className = 'text-danger d-none';
        }
    },

    updateDisplay(amount, newBalance) {
        this.elements.summaryTotal.textContent = `LRD ${this.state.invoiceAmount.toFixed(2)}`;
        this.elements.summaryPayment.textContent = `LRD ${amount.toFixed(2)}`;

        const balanceEl = this.elements.summaryBalance;
        if (newBalance <= 0 && amount > 0) {
            balanceEl.className = 'fw-bold text-success';
            balanceEl.textContent = 'LRD 0.00 (PAID)';
        } else {
            balanceEl.className = 'fw-bold text-danger';
            balanceEl.textContent = `LRD ${newBalance.toFixed(2)}`;
        }
    },

    toggleMobileMoneyField() {
        const isMobileMoney = this.elements.methodSelect.value === 'Mobile Money';

        this.elements.refField.classList.toggle('d-none', !isMobileMoney);
        this.elements.refInput.required = isMobileMoney;

        if (!isMobileMoney) {
            this.elements.refInput.value = '';
        }

        this.updateSummary();
    },
};

document.addEventListener('DOMContentLoaded', () => PaymentForm.init());
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\highschool-finance\resources\views/payments/create.blade.php ENDPATH**/ ?>