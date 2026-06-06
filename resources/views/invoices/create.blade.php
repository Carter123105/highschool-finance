@extends('layouts.app')

@section('content')

@php
    $user = auth()->user();
    $canCreateInvoice = $user?->can('create invoices');
@endphp

{{-- 🚫 HARD BLOCK: DO NOT RENDER PAGE IF NO PERMISSION --}}
@if(!$canCreateInvoice)

    <div class="invoice-page">
        <div class="access-wrap">
            <div class="access-card">
                <h2>Access Denied</h2>
                <p>You do not have permission to access invoice creation.</p>
                <a href="{{ route('invoices.index') }}" class="back-btn">Return</a>
            </div>
        </div>
    </div>

@else

{{-- BOOTSTRAP --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="invoice-page">

    {{-- HEADER --}}
    <div class="page-header">
        <div>
            <h2>Create Class Invoice</h2>
            <p>Generate invoices for students by class, section and type</p>
        </div>

        <a href="{{ route('invoices.index') }}" class="back-btn">← Back</a>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form method="POST" action="{{ route('invoices.store') }}">
        @csrf

        <div class="invoice-grid">

            {{-- LEFT PANEL --}}
            <div class="card-ui">
                <div class="card-header">Invoice Setup</div>

                <div class="form-section">

                    <label>Class *</label>
                    <select name="class_id" id="classSelect" required>
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>

                    <label>Student Type *</label>
                    <select name="student_type" id="studentType" required>
                        <option value="">Select Type</option>
                        <option value="Old">Old Students</option>
                        <option value="New">New Students</option>
                    </select>

                    <label>Section</label>
                    <select name="section_name" id="sectionSelect">
                        <option value="">All Sections</option>
                        @foreach($sections->pluck('name')->unique() as $name)
                            <option value="{{ $name }}">{{ $name }}</option>
                        @endforeach
                    </select>

                    <label>Academic Year *</label>
                    <select name="academic_year_id" required>
                        <option value="">Select Year</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}">{{ $year->name }}</option>
                        @endforeach
                    </select>

                    <label>Due Date</label>
                    <input type="date" name="due_date">

                    <div class="preview" id="previewBox">
                        Select filters to preview students
                    </div>

                </div>
            </div>

            {{-- RIGHT PANEL --}}
            <div class="card-ui">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Invoice Items</span>
                    <button type="button" class="btn-add" id="addRowBtn">+ Add</button>
                </div>

                <div class="table-wrap">
                    <table id="invoiceTable">
                        <thead>
                            <tr>
                                <th>Fee</th>
                                <th>Amount</th>
                                <th>Discount</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    <select name="fee_category_id[]" required>
                                        <option value="">Select</option>
                                        @foreach($feeCategories as $fee)
                                            <option value="{{ $fee->id }}">{{ $fee->name }}</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td><input type="number" class="amount" name="amount[]" value="0"></td>
                                <td><input type="number" class="discount" name="discount[]" value="0"></td>
                                <td><input type="text" class="subtotal" value="0.00" readonly></td>
                                <td><button type="button" class="btn-danger remove">X</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="total-bar">
                    <span>Total</span>
                    <span id="grandTotal">0.00</span>
                </div>

                <button class="btn-submit" type="submit">
                    Create Invoices
                </button>

            </div>

        </div>
    </form>

</div>

{{-- STYLES --}}
<style>
.invoice-page{
    background:#f6f7fb;
    min-height:100vh;
    padding:24px;
    font-family:Inter,sans-serif;
}

/* HEADER */
.page-header{
    background:#fff;
    padding:18px;
    border-radius:14px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 6px 18px rgba(0,0,0,0.06);
    margin-bottom:18px;
}
.page-header h2{margin:0;font-weight:800;}
.page-header p{margin:0;font-size:12px;color:#6b7280;}
.back-btn{
    background:#111827;
    color:#fff;
    padding:8px 12px;
    border-radius:10px;
    text-decoration:none;
}

/* ACCESS */
.access-wrap{
    display:flex;
    justify-content:center;
    align-items:center;
    height:70vh;
}
.access-card{
    background:#fff;
    padding:30px;
    border-radius:16px;
    text-align:center;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

/* GRID */
.invoice-grid{
    display:grid;
    grid-template-columns:360px 1fr;
    gap:18px;
}
@media(max-width:900px){
    .invoice-grid{grid-template-columns:1fr;}
}

/* CARD */
.card-ui{
    background:#fff;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 8px 18px rgba(0,0,0,0.05);
}
.card-header{
    padding:14px;
    background:linear-gradient(135deg,#4f46e5,#6366f1);
    color:#fff;
    font-weight:800;
}

/* FORM */
.form-section{padding:16px;}
label{font-size:12px;font-weight:700;margin-top:10px;display:block;}
select,input{
    width:100%;
    padding:9px;
    border:1px solid #e5e7eb;
    border-radius:10px;
    margin-top:5px;
}

/* PREVIEW */
.preview{
    margin-top:12px;
    padding:12px;
    background:#f1f5f9;
    border-left:4px solid #6366f1;
    border-radius:10px;
}

/* TABLE */
.table-wrap{padding:16px;overflow:auto;}
table{width:100%;border-collapse:separate;border-spacing:0 8px;}
th{font-size:12px;color:#6b7280;text-align:left;}

/* TOTAL */
.total-bar{
    margin:0 16px 16px;
    padding:14px;
    background:#111827;
    color:#fff;
    border-radius:12px;
    display:flex;
    justify-content:space-between;
    font-weight:700;
}

/* BUTTONS */
.btn-add{
    background:#22c55e;
    border:none;
    color:#fff;
    padding:6px 10px;
    border-radius:8px;
}
.btn-submit{
    margin:16px;
    width:calc(100% - 32px);
    padding:12px;
    border:none;
    border-radius:12px;
    font-weight:800;
    background:linear-gradient(135deg,#4f46e5,#6366f1);
    color:#fff;
}
.btn-danger{
    background:#ef4444;
    border:none;
    color:#fff;
    padding:5px 8px;
    border-radius:8px;
}
</style>

{{-- SCRIPT --}}
<script>
document.addEventListener('input', function(e){
    if(e.target.classList.contains('amount') || e.target.classList.contains('discount')){

        let row = e.target.closest('tr');
        let amount = parseFloat(row.querySelector('.amount').value || 0);
        let discount = parseFloat(row.querySelector('.discount').value || 0);

        let subtotal = Math.max(0, amount - discount);
        row.querySelector('.subtotal').value = subtotal.toFixed(2);

        let total = 0;
        document.querySelectorAll('.subtotal').forEach(i=>{
            total += parseFloat(i.value || 0);
        });

        document.getElementById('grandTotal').innerText = total.toFixed(2);
    }
});

document.getElementById('addRowBtn')?.addEventListener('click', function(){
    let tbody = document.querySelector('#invoiceTable tbody');
    let row = tbody.querySelector('tr');
    let clone = row.cloneNode(true);

    clone.querySelectorAll('input').forEach(i=>i.value=0);
    clone.querySelectorAll('select').forEach(i=>i.selectedIndex=0);

    tbody.appendChild(clone);
});

document.addEventListener('click', function(e){
    if(e.target.classList.contains('remove')){
        let rows = document.querySelectorAll('#invoiceTable tbody tr');
        if(rows.length>1){
            e.target.closest('tr').remove();
        }
    }
});
</script>

@endif

@endsection