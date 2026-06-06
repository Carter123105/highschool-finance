@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary: #4f46e5;
        --success: #059669;
        --warning: #d97706;
        --danger: #dc2626;
        --info: #0891b2;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-700: #334155;
        --gray-800: #1e293b;
        --radius: 10px;
        --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }

    .invoice-show { max-width: 1200px; margin: 0 auto; }

    .invoice-header {
        background: linear-gradient(135deg, var(--gray-800) 0%, #1e293b 100%);
        color: #fff; padding: 2rem; border-radius: var(--radius); margin-bottom: 1.5rem;
    }
    .invoice-header h2 { font-weight: 800; margin-bottom: 0.5rem; }

    .invoice-meta {
        display: flex; gap: 2rem; flex-wrap: wrap; margin-top: 1rem; opacity: 0.9;
    }
    .meta-item { display: flex; flex-direction: column; }
    .meta-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; opacity: 0.7; }
    .meta-value { font-weight: 700; font-size: 1.125rem; }

    .status-badge-large {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.5rem 1.25rem; border-radius: 9999px;
        font-weight: 700; font-size: 0.875rem;
    }
    .status-paid { background: rgba(5, 150, 105, 0.2); color: #34d399; }
    .status-partial { background: rgba(217, 119, 6, 0.2); color: #fbbf24; }
    .status-unpaid { background: rgba(220, 38, 38, 0.2); color: #f87171; }

    .card-modern {
        background: #fff; border-radius: var(--radius); box-shadow: var(--shadow);
        border: 1px solid var(--gray-100); overflow: hidden; margin-bottom: 1.5rem;
    }
    .card-header-modern {
        padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--gray-100);
        font-weight: 700; color: var(--gray-800); display: flex; align-items: center; gap: 0.5rem;
    }

    .table-modern { width: 100%; border-collapse: collapse; }
    .table-modern th {
        background: var(--gray-50); padding: 0.875rem 1.5rem; text-align: left;
        font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;
        color: var(--gray-700); font-weight: 700;
    }
    .table-modern td { padding: 1rem 1.5rem; border-bottom: 1px solid var(--gray-100); }
    .amount-cell { font-weight: 700; font-variant-numeric: tabular-nums; }

    .summary-row { background: var(--gray-50); font-weight: 700; }
    .summary-row td { border-top: 2px solid var(--gray-200); }

    .btn-back {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.625rem 1.25rem; background: rgba(255,255,255,0.1);
        color: #fff; border: 1px solid rgba(255,255,255,0.2);
        border-radius: var(--radius); text-decoration: none; font-weight: 600; transition: all 0.2s;
    }
    .btn-back:hover { background: rgba(255,255,255,0.2); color: #fff; }

    .btn-action-bar { display: flex; gap: 0.75rem; margin-top: 1.5rem; flex-wrap: wrap; }

    .btn-modern {
        padding: 0.625rem 1.25rem; border-radius: var(--radius);
        font-weight: 600; font-size: 0.875rem; border: none; cursor: pointer;
        display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;
    }
    .btn-primary { background: var(--primary); color: #fff; }
    .btn-warning { background: var(--warning); color: #fff; }
    .btn-danger { background: var(--danger); color: #fff; }
    .btn-info { background: var(--info); color: #fff; }
    .btn-success { background: var(--success); color: #fff; }

    .text-muted-sm { color: #94a3b8; font-size: 0.75rem; }

    .student-status {
        display: inline-flex; align-items: center; gap: 0.375rem;
        padding: 0.25rem 0.75rem; border-radius: 9999px;
        font-size: 0.75rem; font-weight: 600;
    }
    .student-paid { background: #d1fae5; color: #065f46; }
    .student-partial { background: #fef3c7; color: #92400e; }
    .student-unpaid { background: #fee2e2; color: #991b1b; }

    .pay-btn {
        padding: 0.375rem 0.875rem; background: var(--primary); color: #fff;
        border: none; border-radius: 6px; font-size: 0.8125rem; font-weight: 600;
        cursor: pointer; display: inline-flex; align-items: center; gap: 0.375rem;
        text-decoration: none; transition: all 0.2s;
    }
    .pay-btn:hover { background: #4338ca; color: #fff; }

    .receipt-btn {
        padding: 0.375rem 0.875rem; background: var(--success); color: #fff;
        border: none; border-radius: 6px; font-size: 0.8125rem; font-weight: 600;
        cursor: pointer; display: inline-flex; align-items: center; gap: 0.375rem;
        text-decoration: none; transition: all 0.2s;
    }
    .receipt-btn:hover { background: #047857; color: #fff; }

    .stats-grid {
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem;
    }
    .stat-card {
        background: #fff; border-radius: var(--radius); padding: 1.25rem;
        box-shadow: var(--shadow); border: 1px solid var(--gray-100); text-align: center;
    }
    .stat-number { font-size: 1.75rem; font-weight: 800; color: var(--gray-800); }
    .stat-label {
        font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem;
        text-transform: uppercase; letter-spacing: 0.05em;
    }
    .stat-success { color: var(--success); }
    .stat-warning { color: var(--warning); }
    .stat-danger { color: var(--danger); }

    @media(max-width:768px){
        .stats-grid{ grid-template-columns: repeat(2, 1fr); }
        .invoice-meta{ gap: 1rem; }
    }
</style>

<div class="container-fluid py-4 invoice-show">
    {{-- HEADER --}}
    <div class="invoice-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <a href="{{ route('invoices.index') }}" class="btn-back mb-3">
                    <i class="bi bi-arrow-left"></i> Back to Invoices
                </a>
                <h2>Class Invoice #{{ $invoice->invoice_no ?? 'N/A' }}</h2>
                <p class="mb-0 opacity-75">
                    Created {{ optional($invoice->created_at)->format('F d, Y \a\t h:i A') ?? 'N/A' }}
                </p>
            </div>
            @php
                $status = strtolower($invoice->status ?? 'unpaid');
                $statusClass = match($status) {
                    'paid' => 'status-paid',
                    'partial' => 'status-partial',
                    default => 'status-unpaid',
                };
            @endphp
            <span class="status-badge-large {{ $statusClass }}">
                <i class="bi bi-circle-fill" style="font-size: 0.5rem;"></i>
                {{ ucfirst($status) }}
            </span>
        </div>

        <div class="invoice-meta">
            <div class="meta-item">
                <span class="meta-label">Class</span>
                <span class="meta-value">{{ optional($invoice->schoolClass)->name ?? 'N/A' }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Section</span>
                <span class="meta-value">{{ optional($invoice->section)->name ?? 'All Sections' }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Student Type</span>
                <span class="meta-value">{{ $invoice->student_type ?? 'N/A' }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Academic Year</span>
                <span class="meta-value">{{ optional($invoice->academicYear)->name ?? 'N/A' }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Amount Per Student</span>
                <span class="meta-value">LRD {{ number_format($invoice->total_amount ?? 0, 2) }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Total Students</span>
                <span class="meta-value">{{ $totalStudents ?? 0 }}</span>
            </div>
        </div>
    </div>

    {{-- STATS SUMMARY --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $totalStudents ?? 0 }}</div>
            <div class="stat-label">Total Students</div>
        </div>
        <div class="stat-card">
            <div class="stat-number stat-success">{{ $paidStudents ?? 0 }}</div>
            <div class="stat-label">Paid</div>
        </div>
        <div class="stat-card">
            <div class="stat-number stat-warning">{{ $partialStudents ?? 0 }}</div>
            <div class="stat-label">Partial</div>
        </div>
        <div class="stat-card">
            <div class="stat-number stat-danger">{{ $unpaidStudents ?? 0 }}</div>
            <div class="stat-label">Unpaid</div>
        </div>
    </div>

    {{-- FINANCIAL SUMMARY --}}
    <div class="card-modern">
        <div class="card-header-modern">
            <i class="bi bi-cash-stack"></i> Financial Summary
        </div>
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="text-end">Amount (LRD)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Amount Per Student</td>
                        <td class="text-end amount-cell">{{ number_format($invoice->total_amount ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Students</td>
                        <td class="text-end amount-cell">{{ $totalStudents ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td>Total Expected Collection</td>
                        <td class="text-end amount-cell">{{ number_format(($invoice->total_amount ?? 0) * ($totalStudents ?? 0), 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total Collected</td>
                        <td class="text-end amount-cell text-success">{{ number_format($totalCollected ?? 0, 2) }}</td>
                    </tr>
                    <tr class="summary-row">
                        <td class="text-end">Total Outstanding</td>
                        <td class="text-end amount-cell {{ (($invoice->total_amount ?? 0) * ($totalStudents ?? 0) - ($totalCollected ?? 0)) > 0 ? 'text-danger' : 'text-success' }}" style="font-size: 1.125rem;">
                            {{ number_format(max(0, ($invoice->total_amount ?? 0) * ($totalStudents ?? 0) - ($totalCollected ?? 0)), 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- INVOICE ITEMS --}}
    <div class="card-modern">
        <div class="card-header-modern">
            <i class="bi bi-list-check"></i> Invoice Items
        </div>
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Fee Category</th>
                        <th class="text-end">Amount</th>
                        <th class="text-end">Discount</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoice->invoiceItems ?? collect() as $item)
                        <tr>
                            <td>{{ optional($item->feeCategory)->name ?? 'N/A' }}</td>
                            <td class="text-end amount-cell">{{ number_format($item->amount ?? 0, 2) }}</td>
                            <td class="text-end amount-cell text-danger">{{ number_format($item->discount ?? 0, 2) }}</td>
                            <td class="text-end amount-cell">{{ number_format($item->subtotal ?? 0, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No invoice items found</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="summary-row">
                        <td colspan="3" class="text-end">Total Amount Per Student:</td>
                        <td class="text-end amount-cell" style="font-size: 1.125rem;">
                            LRD {{ number_format($invoice->total_amount ?? 0, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- STUDENT PAYMENTS --}}
    <div class="card-modern">
        <div class="card-header-modern">
            <i class="bi bi-people"></i> Student Payments
            <span class="ms-auto badge bg-dark">{{ $totalStudents ?? 0 }} students</span>
        </div>
        
        @if(($totalStudents ?? 0) > 0)
            <div class="table-responsive">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Amount Due</th>
                            <th>Amount Paid</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($studentPayments ?? collect() as $payment)
                            @php
                                $studentStatus = strtolower($payment->status ?? 'unpaid');
                                $statusClass = match($studentStatus) {
                                    'paid' => 'student-paid',
                                    'partial' => 'student-partial',
                                    default => 'student-unpaid',
                                };
                                $studentFullName = trim((optional($payment->student)->first_name ?? '') . ' ' . (optional($payment->student)->last_name ?? ''));
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex flex-column">
                                        <strong>{{ $studentFullName ?: 'Unknown Student' }}</strong>
                                        <span class="text-muted-sm">{{ optional($payment->student)->student_type ?? '' }}</span>
                                    </div>
                                </td>
                                <td class="amount-cell">LRD {{ number_format($payment->amount_due ?? 0, 2) }}</td>
                                <td class="amount-cell text-success">LRD {{ number_format($payment->amount_paid ?? 0, 2) }}</td>
                                <td class="amount-cell {{ ($payment->balance ?? 0) > 0 ? 'text-danger' : 'text-success' }}">
                                    LRD {{ number_format($payment->balance ?? 0, 2) }}
                                </td>
                                <td>
                                    <span class="student-status {{ $statusClass }}">
                                        <i class="bi bi-circle-fill" style="font-size: 0.4rem;"></i>
                                        {{ ucfirst($studentStatus) }}
                                    </span>
                                </td>
                                <td>
                                    @if($studentStatus !== 'paid')
                                        {{-- ✅ LINK TO PAYMENT PAGE --}}
                                        <a href="{{ route('payments.create', [
                                            'student_id' => $payment->student_id,
                                            'invoice_id' => $invoice->id,
                                            'amount' => $payment->balance,
                                            'student_name' => $studentFullName
                                        ]) }}" class="pay-btn">
                                            <i class="bi bi-cash-coin"></i> Pay
                                        </a>
                                    @else
                                        {{-- ✅ RECEIPT BUTTON FOR PAID STUDENTS --}}
                                        @php
                                            $studentPayment = $invoice->payments->where('student_id', $payment->student_id)->first();
                                        @endphp
                                        @if($studentPayment)
                                            <a href="{{ route('payments.receipt', $studentPayment) }}" class="receipt-btn" target="_blank">
                                                <i class="bi bi-receipt"></i> Receipt
                                            </a>
                                        @else
                                            <span class="text-success"><i class="bi bi-check-circle-fill"></i> Paid</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-4 text-center text-muted">
                <i class="bi bi-people fs-1 d-block mb-2"></i>
                No students assigned to this invoice.
            </div>
        @endif
    </div>

    {{-- ACTIONS --}}
    <div class="btn-action-bar">
        <a href="{{ route('invoices.edit', $invoice) }}" class="btn-modern btn-warning">
            <i class="bi bi-pencil"></i> Edit Invoice
        </a>
        <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="d-inline" 
              onsubmit="return confirm('Are you sure you want to delete this invoice? This will also delete all student payment records.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-modern btn-danger">
                <i class="bi bi-trash"></i> Delete Invoice
            </button>
        </form>
        <a href="#" onclick="window.print()" class="btn-modern btn-primary ms-auto">
            <i class="bi bi-printer"></i> Print Invoice
        </a>
    </div>
</div>
@endsection