@extends('layouts.app')

@section('content')

@php

use App\Models\Setting;
use App\Models\PaymentAllocation;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| SETTINGS
|--------------------------------------------------------------------------
*/
$setting = Setting::first();

/*
|--------------------------------------------------------------------------
| LOGO HANDLING - IMPROVED FETCHING
|--------------------------------------------------------------------------
*/
$logoUrl = null;
$logoPath = $setting->logo ?? null;

if ($logoPath) {
    $cleanPath = ltrim(str_replace('storage/', '', $logoPath), '/');
    
    if (Storage::disk('public')->exists($cleanPath)) {
        $logoUrl = Storage::disk('public')->url($cleanPath);
    } else {
        $logoUrl = asset('storage/' . $cleanPath);
    }
}

$defaultLogo = asset('images/default-school-logo.png');

/*
|--------------------------------------------------------------------------
| SIGNATURE HANDLING
|--------------------------------------------------------------------------
*/
function getSignatureUrl(?string $path): ?string
{
    if (!$path) return null;
    
    $cleanPath = ltrim(str_replace(['storage/', 'public/'], '', $path), '/');
    
    if (Storage::disk('public')->exists($cleanPath)) {
        return Storage::disk('public')->url($cleanPath);
    }
    
    return asset('storage/' . $cleanPath);
}

$authorizedSigUrl = getSignatureUrl($setting->authorized_signature ?? null);
$registrarSigUrl  = getSignatureUrl($setting->registrar_signature ?? null);

/*
|--------------------------------------------------------------------------
| RELATIONS
|--------------------------------------------------------------------------
*/
$invoice = $payment->invoice ?? null;

$student =
    $payment->student
    ?? ($invoice->student ?? null);

$noData = (!$invoice || !$student);

/*
|--------------------------------------------------------------------------
| RECEIPT NO
|--------------------------------------------------------------------------
*/
$receiptNo = ($setting->receipt_prefix ?? 'REC') . '-' .
    str_pad($payment->id, 6, '0', STR_PAD_LEFT);

/*
|--------------------------------------------------------------------------
| CURRENCY
|--------------------------------------------------------------------------
*/
$currency = strtoupper($setting->currency ?? 'LRD');

$rate = max(
    1,
    floatval($setting->exchange_rate ?? 1)
);

/*
|--------------------------------------------------------------------------
| CURRENCY CONVERTER
|--------------------------------------------------------------------------
*/
$toCurrency = function ($amount) use ($currency, $rate) {

    $amount = floatval($amount);

    if ($currency === 'USD' && $rate > 0) {
        return $amount / $rate;
    }

    return $amount;
};

/*
|--------------------------------------------------------------------------
| MOBILE MONEY REFERENCE NUMBER
|--------------------------------------------------------------------------
*/
$mobileMoneyRef = null;

$refFields = [
    'transaction_reference',
    'reference_no',
    'reference_number',
    'momo_reference',
    'mpesa_reference',
    'mpesa_receipt_number',
    'payment_reference',
    'gateway_reference',
    'operator_reference',
    'external_id',
    'checkout_request_id',
];

foreach ($refFields as $field) {
    if (!empty($payment->{$field})) {
        $mobileMoneyRef = $payment->{$field};
        break;
    }
}

if (!$mobileMoneyRef && !empty($payment->metadata)) {
    $metadata = is_string($payment->metadata) 
        ? json_decode($payment->metadata, true) 
        : $payment->metadata;
    
    if (is_array($metadata)) {
        foreach (['reference', 'transaction_reference', 'receipt_number', 'mpesa_receipt', 'momo_ref'] as $metaKey) {
            if (!empty($metadata[$metaKey])) {
                $mobileMoneyRef = $metadata[$metaKey];
                break;
            }
        }
    }
}

$paymentMethod = strtolower($payment->payment_method ?? $payment->method ?? 'cash');
$isMobileMoney = in_array($paymentMethod, ['mobile_money', 'momo', 'mpesa', 'mtn', 'orange_money', 'wave', 'airtel_money']);

/*
|--------------------------------------------------------------------------
| ALL PAYMENTS FOR THIS INVOICE - FILTERED BY STUDENT
|--------------------------------------------------------------------------
*/
$invoicePayments = $invoice && $student
    ? $invoice->payments()
        ->where('student_id', $student->id)
        ->with([
            'allocations.invoiceItem.feeCategory',
            'receiver'
        ])
        ->orderBy('payment_date')
        ->orderBy('id')
        ->get()
    : collect();

/*
|--------------------------------------------------------------------------
| ALL ALLOCATIONS FOR THIS INVOICE - FILTERED BY STUDENT
|--------------------------------------------------------------------------
*/
$allAllocations = PaymentAllocation::with([
        'invoiceItem.feeCategory',
        'payment'
    ])
    ->whereHas('payment', function ($q) use ($invoice, $student) {

        if ($invoice) {
            $q->where('invoice_id', $invoice->id);
        }

        if ($student) {
            $q->where('student_id', $student->id);
        }

    })
    ->get();

/*
|--------------------------------------------------------------------------
| FEE BREAKDOWN
|--------------------------------------------------------------------------
*/
$invoiceItems = collect();

if ($invoice) {

    try {

        $invoiceItems = $invoice->items()
            ->with('feeCategory')
            ->get();

    } catch (\Throwable $e) {

        try {

            $invoiceItems = $invoice->invoiceItems()
                ->with('feeCategory')
                ->get();

        } catch (\Throwable $e2) {

            $invoiceItems = $allAllocations
                ->pluck('invoiceItem')
                ->filter()
                ->unique('id')
                ->values();

        }

    }

}

$paidPerItem = $allAllocations
    ->groupBy(function ($alloc) {
        return optional($alloc->invoiceItem)->id;
    })
    ->map(function ($allocs) {
        return floatval($allocs->sum('amount'));
    });

$feeRows = $invoiceItems
    ->groupBy(function ($item) {
        return optional($item->feeCategory)->name ?? 'Fee';
    })
    ->map(function ($items, $feeName) use ($paidPerItem) {

        $expectedRaw = floatval($items->sum('subtotal'));

        $paidRaw = floatval(
            $items->sum(function ($item) use ($paidPerItem) {
                return $paidPerItem->get($item->id, 0);
            })
        );

        return [
            'name'     => $feeName,
            'expected' => $expectedRaw,
            'paid'     => $paidRaw,
            'balance'  => max(0, $expectedRaw - $paidRaw),
        ];

    })
    ->sortBy(function ($row) {

        $name = strtolower($row['name']);

        if (str_contains($name, 'registration')) return 1;
        if (str_contains($name, '1st')) return 2;
        if (str_contains($name, '2nd')) return 3;
        if (str_contains($name, '3rd')) return 4;
        if (str_contains($name, '4th')) return 5;

        return 99;

    })
    ->values();

/*
|--------------------------------------------------------------------------
| TOTALS
|--------------------------------------------------------------------------
*/
$totalInvoiceRaw = floatval($invoice->total_amount ?? 0);

$totalPaidRaw = floatval(
    $invoicePayments->sum('amount_paid')
);

$balanceRaw = max(
    0,
    $totalInvoiceRaw - $totalPaidRaw
);

$totalInvoice = $toCurrency($totalInvoiceRaw);

$totalPaid = $toCurrency($totalPaidRaw);

$balance = $toCurrency($balanceRaw);

/*
|--------------------------------------------------------------------------
| STATUS
|--------------------------------------------------------------------------
*/
$status =
    $totalPaidRaw <= 0
        ? 'Not Paid'
        : ($balanceRaw > 0
            ? 'Partially Paid'
            : 'Fully Paid');

/*
|--------------------------------------------------------------------------
| STUDENT INFO - CLASS FETCHING
|--------------------------------------------------------------------------
*/
$className = 'N/A';

if ($student) {

    if ($student->schoolClass) {
        $className = $student->schoolClass->name;
    } elseif ($student->school_class) {
        $className = $student->school_class->name;
    } elseif ($student->classRoom) {
        $className = $student->classRoom->name;
    } elseif ($student->classroom) {
        $className = $student->classroom->name;
    } elseif ($student->class) {
        $className = $student->class->name;
    } elseif ($student->class_name) {
        $className = $student->class_name;
    } elseif ($student->grade) {
        $className = $student->grade;
    }

}

if ($className === 'N/A' && $invoice) {

    if ($invoice->class_name) {
        $className = $invoice->class_name;
    } elseif ($invoice->schoolClass) {
        $className = $invoice->schoolClass->name;
    } elseif ($invoice->class) {
        $className = $invoice->class->name;
    }

}

$studentName = trim(
    ($student->first_name ?? '') . ' ' .
    ($student->last_name ?? '')
);

/*
|--------------------------------------------------------------------------
| STUDENT TYPE
|--------------------------------------------------------------------------
*/
$studentType =
    $student->student_type
    ?? $invoice->student_type
    ?? 'General';

/*
|--------------------------------------------------------------------------
| TRANSACTION DATE
|--------------------------------------------------------------------------
*/
$transactionDate =
    $payment->payment_date
    ?? $payment->created_at
    ?? null;

@endphp


@if($noData)

<div class="container py-4 text-center">
    <div class="alert alert-danger">
        ❌ No invoice or student data found.
    </div>
</div>

@else

<div class="receipt-page container py-1">

    {{-- PRINT BUTTON --}}
    <div class="text-center mb-2 no-print">
        <button onclick="window.print()" class="btn btn-danger btn-sm px-3 py-1">
            🖨 Print Receipt
        </button>
    </div>

    <div class="receipt-wrapper">

        <div class="receipt">

            {{-- WATERMARK --}}
            @if($logoUrl)
                <div class="watermark">
                    <img src="{{ $logoUrl }}" alt="School Logo" onerror="this.style.display='none'">
                </div>
            @endif

            {{-- HEADER --}}
            <div class="receipt-header">

                <div class="logo-box">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}"
                             alt="{{ $setting->school_name ?? 'School Logo' }}"
                             class="school-logo"
                             onerror="this.src='{{ $defaultLogo }}'; this.onerror=null;">
                    @else
                        <div class="logo-placeholder">
                            <span>🏫</span>
                        </div>
                    @endif
                </div>

                <div class="school-info">
                    <h2 class="school-name">
                        {{ $setting->school_name ?? 'SCHOOL NAME' }}
                    </h2>
                    <p class="system-name">
                        {{ $setting->system_name ?? 'Finance System' }}
                    </p>
                    <p>{{ $setting->school_address ?? 'Address' }}</p>
                    <p>Tel: {{ $setting->school_phone ?? 'N/A' }}</p>
                </div>

                <div class="header-right">
                    <div>
                        <strong>Date</strong><br>
                        <span>
                            {{
                                $transactionDate
                                    ? \Carbon\Carbon::parse($transactionDate)->format('d M Y h:i A')
                                    : 'N/A'
                            }}
                        </span>
                    </div>
                    <div class="mt-1">
                        <strong>Class</strong><br>
                        <span>{{ $className }}</span>
                    </div>
                </div>

            </div>

            {{-- TITLE --}}
            <div class="receipt-title">
                OFFICIAL PAYMENT RECEIPT
            </div>

            {{-- DETAILS --}}
            <div class="details">

                <div class="detail-row">
                    <div>
                        <strong>Receipt:</strong> {{ $receiptNo }}
                    </div>
                    <div class="text-end">
                        <strong>Invoice:</strong> {{ $invoice->invoice_no ?? 'N/A' }}
                    </div>
                </div>

                <div class="detail-row">
                    <div>
                        <strong>Student:</strong> {{ $studentName }}
                        <br>
                        <small class="
                            @if(strtolower($studentType) == 'new')
                                text-primary
                            @elseif(strtolower($studentType) == 'old')
                                text-success
                            @else
                                text-muted
                            @endif
                        ">
                            ({{ ucfirst($studentType) }} Student)
                        </small>
                    </div>
                    <div class="text-end">
                        <strong>Status:</strong>
                        <span class="
                            @if($status == 'Fully Paid')
                                text-success
                            @elseif($status == 'Partially Paid')
                                text-warning
                            @else
                                text-danger
                            @endif
                        ">
                            {{ $status }}
                        </span>
                    </div>
                </div>

                {{-- MOBILE MONEY REFERENCE --}}
                @if($mobileMoneyRef)
                    <div class="detail-row momo-ref-row">
                        <div>
                            <strong>💳 Payment Method:</strong>
                            <span class="text-uppercase">{{ str_replace('_', ' ', $paymentMethod) }}</span>
                        </div>
                        <div class="text-end">
                            <strong>📱 Reference No:</strong>
                            <span class="momo-ref-code">{{ $mobileMoneyRef }}</span>
                        </div>
                    </div>
                @elseif($isMobileMoney)
                    <div class="detail-row momo-ref-row">
                        <div>
                            <strong>💳 Payment Method:</strong>
                            <span class="text-uppercase">{{ str_replace('_', ' ', $paymentMethod) }}</span>
                        </div>
                        <div class="text-end">
                            <strong>📱 Reference No:</strong>
                            <span class="text-muted">N/A</span>
                        </div>
                    </div>
                @else
                    <div class="detail-row">
                        <div>
                            <strong>💳 Payment Method:</strong>
                            <span class="text-uppercase">{{ str_replace('_', ' ', $paymentMethod) }}</span>
                        </div>
                        <div class="text-end">
                            <strong>Received By:</strong>
                            {{ optional($payment->receiver)->name ?? optional($payment->recordedBy)->name ?? 'System' }}
                        </div>
                    </div>
                @endif

            </div>

            {{-- TABLE --}}
            <table class="table payment-table mt-1 mb-1">

                <thead>
                    <tr>
                        <th>Fee Type</th>
                        <th class="text-end">Fee Total</th>
                        <th class="text-end">Paid</th>
                        <th class="text-end">Balance</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($feeRows as $row)
                    <tr>
                        <td>{{ $row['name'] }}</td>
                        <td class="text-end">
                            {{ $currency }} {{ number_format($toCurrency($row['expected']), 2) }}
                        </td>
                        <td class="text-end text-success">
                            {{ $currency }} {{ number_format($toCurrency($row['paid']), 2) }}
                        </td>
                        <td class="text-end {{ $row['balance'] > 0 ? 'text-danger' : 'text-muted' }}">
                            {{ $currency }} {{ number_format($toCurrency($row['balance']), 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            No Fee Breakdown Found
                        </td>
                    </tr>
                @endforelse

                    {{-- TOTALS ROW --}}
                    <tr class="totals-row">
                        <td><strong>Total</strong></td>
                        <td class="text-end">
                            <strong>{{ $currency }} {{ number_format($totalInvoice, 2) }}</strong>
                        </td>
                        <td class="text-end">
                            <strong class="text-success">{{ $currency }} {{ number_format($totalPaid, 2) }}</strong>
                        </td>
                        <td class="text-end">
                            <strong class="text-danger">{{ $currency }} {{ number_format($balance, 2) }}</strong>
                        </td>
                    </tr>

                </tbody>

            </table>

            {{-- SIGNATURES --}}
            <div class="signature-area">

                {{-- AUTHORIZED SIGNATURE --}}
                <div class="signature-box">
                    <div class="signature-image-wrapper">
                        @if($authorizedSigUrl)
                            <img src="{{ $authorizedSigUrl }}"
                                 alt="Authorized Signature"
                                 class="signature-img"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="signature-placeholder" style="display:none;">
                                <span class="sig-placeholder-line"></span>
                            </div>
                        @else
                            <div class="signature-placeholder">
                                <span class="sig-placeholder-line"></span>
                            </div>
                        @endif
                    </div>
                    <small class="sig-text">AUTHORIZED SIGNATURE</small>
                </div>

                {{-- REGISTRAR SIGNATURE --}}
                <div class="signature-box">
                    <div class="signature-image-wrapper">
                        @if($registrarSigUrl)
                            <img src="{{ $registrarSigUrl }}"
                                 alt="Registrar Signature"
                                 class="signature-img"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="signature-placeholder" style="display:none;">
                                <span class="sig-placeholder-line"></span>
                            </div>
                        @else
                            <div class="signature-placeholder">
                                <span class="sig-placeholder-line"></span>
                            </div>
                        @endif
                    </div>
                    <small class="sig-text">REGISTRAR SIGNATURE</small>
                </div>

            </div>

        </div>

    </div>

</div>

@endif


<style>

body{
    background:#eef2f7;
    font-family:Arial, sans-serif;
    margin:0;
    padding:0;
}

/* PAGE */
.receipt-page{
    padding:2px 0;
}

/* WRAPPER */
.receipt-wrapper{
    display:flex;
    justify-content:center;
    margin-bottom:4px;
}

/* RECEIPT */
.receipt{
    width:760px;
    max-width:100%;
    background:#fff;
    border:3px solid #b91c1c;
    border-radius:3px;
    padding:4px 6px;
    position:relative;
    overflow:hidden;
    box-shadow:0 1px 2px rgba(0,0,0,0.03);
}

/* WATERMARK */
.watermark{
    position:absolute;
    inset:0;
    display:flex;
    justify-content:center;
    align-items:center;
    opacity:0.04;
    pointer-events:none;
}

.watermark img{
    width:300px;
    height:auto;
    object-fit:contain;
}

/* HEADER */
.receipt-header{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:8px;
    margin-bottom:4px;
    position:relative;
    z-index:2;
    width:100%;
    flex-wrap:nowrap;
}

/* LOGO */
.logo-box{
    width:55px;
    min-width:55px;
    display:flex;
    align-items:flex-start;
    justify-content:flex-start;
}

.school-logo{
    width:45px;
    height:45px;
    border-radius:50%;
    object-fit:cover;
    display:block;
}

.logo-placeholder{
    width:45px;
    height:45px;
    border-radius:50%;
    background:#f3f4f6;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:20px;
}

/* SCHOOL INFO */
.school-info{
    flex:1;
    text-align:center;
    overflow:hidden;
}

.school-name{
    font-size:16px;
    font-weight:900;
    color:#b91c1c;
    margin:0;
    line-height:1.1;
    word-break:break-word;
}

.system-name{
    font-size:12px;
    font-weight:700;
    margin:0;
}

.school-info p{
    margin:0;
    font-size:12px;
    line-height:1.1;
}

/* RIGHT HEADER */
.header-right{
    width:140px;
    min-width:140px;
    text-align:right;
    font-size:12px;
    line-height:1.1;
}

/* TITLES */
.receipt-title,
.mini-title{
    background:#b91c1c;
    color:#fff;
    padding:3px;
    text-align:center;
    font-size:12px;
    font-weight:700;
    margin:4px 0;
    border-radius:2px;
}

/* DETAILS */
.details{
    margin-bottom:3px;
}

.detail-row{
    display:flex;
    justify-content:space-between;
    gap:6px;
    margin-bottom:2px;
    font-size:12px;
    line-height:1.1;
}

/* MOBILE MONEY REFERENCE ROW */
.momo-ref-row{
    background:#f0f9ff;
    border:1px dashed #0ea5e9;
    border-radius:3px;
    padding:4px 6px;
    margin-top:3px;
}

.momo-ref-code{
    font-family:'Courier New', monospace;
    font-weight:700;
    color:#0ea5e9;
    font-size:13px;
    letter-spacing:0.5px;
}

/* TABLES */
.payment-history-table,
.payment-table{
    width:100%;
    border-collapse:collapse;
    margin-bottom:3px !important;
}

.payment-history-table th,
.payment-table th{
    background:#1e3a8a !important;
    color:#fff !important;
    font-size:12px;
    padding:3px 4px !important;
    border:1px solid #d1d5db !important;
    white-space:nowrap;
}

.payment-history-table td,
.payment-table td{
    padding:3px 4px !important;
    font-size:12px;
    border:1px solid #d1d5db !important;
    background:#fff;
    line-height:1.1;
}

/* ROW COLORS */
.payment-history-table tbody tr:nth-child(even),
.payment-table tbody tr:nth-child(even){
    background:#f9fafb;
}

/* TOTALS ROW */
.payment-table .totals-row td{
    background:#f3f4f6 !important;
    border-top:2px solid #1e3a8a !important;
}

/* SIGNATURE AREA */
.signature-area{
    display:flex;
    justify-content:space-between;
    gap:24px;
    margin-top:16px;
    padding:0 20px;
}

.signature-box{
    width:42%;
    text-align:center;
    display:flex;
    flex-direction:column;
    align-items:center;
}

/* SIGNATURE IMAGE WRAPPER */
.signature-image-wrapper{
    width:100%;
    height:50px;
    display:flex;
    align-items:flex-end;
    justify-content:center;
    margin-bottom:4px;
    position:relative;
}

/* ACTUAL SIGNATURE IMAGE */
.signature-img{
    max-width:100%;
    max-height:48px;
    object-fit:contain;
    object-position:center bottom;
}

/* SIGNATURE PLACEHOLDER (when no image) */
.signature-placeholder{
    width:100%;
    height:100%;
    display:flex;
    align-items:flex-end;
    justify-content:center;
}

.sig-placeholder-line{
    display:block;
    width:100%;
    height:1px;
    background:#000;
    margin-bottom:2px;
}

/* SIGNATURE LABEL */
.sig-text{
    font-size:11px;
    font-weight:700;
    color:#374151;
    text-transform:uppercase;
    letter-spacing:0.3px;
}

/* PRINT BUTTON */
.no-print{
    margin-bottom:6px;
}

/* MOBILE */
@media(max-width:768px){

    .receipt{
        padding:6px;
    }

    .receipt-header{
        flex-direction:column;
        text-align:center;
    }

    .header-right{
        width:100%;
        text-align:center;
    }

    .detail-row{
        flex-direction:column;
        gap:1px;
    }

    .momo-ref-row{
        flex-direction:column;
        gap:2px;
    }

    .payment-history-table th,
    .payment-table th,
    .payment-history-table td,
    .payment-table td{
        font-size:16px;
    }

    .signature-area{
        gap:16px;
        padding:0 10px;
    }

    .signature-box{
        width:48%;
    }
}

/* PRINT SETTINGS */
@media print{

    @page{
        size:A4 portrait;
        margin:4mm;
    }

    body *{
        visibility:hidden !important;
    }

    .receipt-wrapper,
    .receipt-wrapper *,
    .receipt{
        visibility:visible !important;
    }

    .no-print{
        display:none !important;
    }

    html,
    body{
        background:#fff !important;
        margin:0 !important;
        padding:0 !important;
    }

    .receipt-page{
        width:100%;
        margin:0 !important;
        padding:0 !important;
    }

    .receipt-wrapper{
        position:absolute;
        top:0;
        left:0;
        width:100%;
        display:flex;
        justify-content:center;
        margin:0 !important;
        padding:0 !important;
    }

    .receipt{
        width:100% !important;
        max-width:100% !important;
        padding:6px 8px !important;
        box-shadow:none !important;
        border:4px solid #b91c1c !important;
        overflow:hidden !important;
    }

    /* KEEP HEADER FIXED */
    .receipt-header{
        display:flex !important;
        flex-direction:row !important;
        justify-content:space-between !important;
        align-items:flex-start !important;
        flex-wrap:nowrap !important;
        gap:8px !important;
    }

    .logo-box{
        width:55px !important;
        min-width:55px !important;
    }

    .school-logo{
        width:45px !important;
        height:45px !important;
    }

    .school-info{
        flex:1 !important;
        text-align:center !important;
    }

    .header-right{
        width:140px !important;
        min-width:140px !important;
        text-align:right !important;
    }

    /* KEEP COLORS */
    .receipt-title,
    .mini-title{
        background:#b91c1c !important;
        color:#fff !important;
    }

    .payment-history-table th,
    .payment-table th{
        background:#1e3a8a !important;
        color:#fff !important;
    }

    .payment-table .totals-row td{
        background:#f3f4f6 !important;
    }

    /* MOBILE MONEY REFERENCE ROW PRINT */
    .momo-ref-row{
        background:#f0f9ff !important;
        border:1px dashed #0ea5e9 !important;
    }

    .momo-ref-code{
        color:#0ea5e9 !important;
    }

    /* SIGNATURES PRINT */
    .signature-area{
        margin-top:16px !important;
        gap:24px !important;
        padding:0 20px !important;
    }

    .signature-box{
        width:42% !important;
    }

    .signature-image-wrapper{
        height:50px !important;
    }

    .signature-img{
        max-height:48px !important;
    }

    .sig-placeholder-line{
        background:#000 !important;
    }

    .sig-text{
        color:#374151 !important;
        font-size:11px !important;
    }

    *{
        -webkit-print-color-adjust:exact !important;
        print-color-adjust:exact !important;
    }
}

</style>

@endsection