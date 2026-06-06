@extends('layouts.app')

@section('content')

<style>
    :root {
        --primary: #4f46e5;
        --dark: #0f172a;
        --muted: #64748b;
        --border: #e2e8f0;
        --bg: #f1f5f9;
        --success: #059669;
    }

    body {
        background: var(--bg);
        margin: 0;
        padding: 0;
    }

    .container,
    .container-fluid {
        max-width: 100% !important;
        width: 100% !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    .content,
    .main-content,
    .app-content {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .invoice-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 15px;
    }

    /* TOP BAR */
    .top-bar {
        background: white;
        padding: 18px 22px;
        border-radius: 14px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .top-bar h3 {
        margin: 0;
        font-weight: 900;
        color: var(--dark);
    }

    .btn-print {
        background: var(--dark);
        color: #fff;
        border: none;
        padding: 10px 14px;
        border-radius: 10px;
        font-weight: 700;
        cursor: pointer;
    }

    /* CARD */
    .invoice-card {
        background: white;
        border-radius: 16px;
        padding: 28px 30px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.06);
        width: 100%;
        box-sizing: border-box;
    }

    /* SCHOOL HEADER */
    .school-header {
        text-align: center;
        padding-bottom: 14px;
        border-bottom: 2px solid var(--border);
        margin-bottom: 6px;
    }

    .school-logo {
        max-height: 70px;
        object-fit: contain;
        margin-bottom: 8px;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    .school-header h2 {
        margin: 0 0 2px;
        font-size: 22px;
        font-weight: 900;
        color: var(--dark);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .school-header .school-sub {
        margin: 1px 0;
        font-size: 13px;
        color: var(--primary);
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    /* ✅ UPDATED: Address styling with multiple lines support */
    .school-address {
        margin: 4px 0 0;
        font-size: 12.5px;
        color: var(--muted);
        line-height: 1.4;
    }

    .school-address-line {
        display: block;
        margin: 1px 0;
    }

    .school-contact {
        margin: 6px 0 0;
        font-size: 12.5px;
        color: var(--muted);
    }

    /* INVOICE LABEL */
    .invoice-title {
        text-align: center;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--primary);
        margin: 10px 0 14px;
    }

    /* HEADER: student + invoice meta */
    .header-section {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border);
    }

    .student-box h4 {
        margin: 0;
        font-size: 20px;
        font-weight: 900;
        color: var(--dark);
    }

    .student-box p {
        margin: 3px 0;
        font-size: 13px;
        color: var(--muted);
    }

    .invoice-box {
        text-align: right;
    }

    .invoice-badge {
        background: var(--primary);
        color: white;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        display: inline-block;
    }

    .invoice-total {
        margin-top: 5px;
        font-size: 18px;
        font-weight: 900;
        color: var(--success);
    }

    /* TABLE */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .table {
        width: 100% !important;
        margin: 0;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .table th,
    .table td {
        padding: 12px;
        vertical-align: middle;
        word-wrap: break-word;
    }

    .table thead th {
        background: #f8fafc;
        font-weight: 800;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid var(--border);
    }

    .amount {
        font-weight: 800;
        color: var(--success);
    }

    /* FOOTER NOTE */
    .footer-note {
        margin-top: 15px;
        padding-top: 12px;
        border-top: 1px dashed var(--border);
        text-align: center;
        font-size: 12px;
        color: var(--muted);
    }

    /* ===========================
       PRINT STYLES
    =========================== */
    @media print {

        @page {
            size: A4 portrait;
            margin: 20mm 20mm;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            box-sizing: border-box !important;
        }

        body {
            background: white !important;
            margin: 0 !important;
            padding: 0 !important;
            font-size: 11pt !important;
            width: 100% !important;
            min-width: 0 !important;
        }

        /* Hide ALL UI chrome */
        .sidebar, #sidebar, .app-sidebar,
        aside, nav, .navbar, .main-sidebar,
        .top-bar,
        [class*="sidebar"], [id*="sidebar"],
        [class*="nav"], [class*="menu"],
        .user-panel, [class*="user-panel"],
        [class*="brand"], [class*="welcome"],
        header {
            display: none !important;
        }

        /* Nuke every layout wrapper */
        html, body,
        #app, .app,
        .wrapper, [class*="wrapper"],
        .layout, [class*="layout"],
        .main, .main-content,
        .content-wrapper, .content,
        .app-content, .app-main,
        .container, .container-fluid,
        .invoice-container {
            all: unset !important;
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            min-width: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            float: none !important;
            position: static !important;
            left: auto !important;
            right: auto !important;
            top: auto !important;
            transform: none !important;
            flex: none !important;
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
            overflow: visible !important;
        }

        /* Invoice card */
        .invoice-card {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
            border: none !important;
            border-radius: 0 !important;
            background: white !important;
        }

        /* School header */
        .school-header {
            display: block !important;
            text-align: center !important;
            padding-bottom: 10pt !important;
            border-bottom: 2px solid #cbd5e1 !important;
            margin-bottom: 6pt !important;
            width: 100% !important;
        }

        .school-logo {
            display: block !important;
            max-height: 60px !important;
            object-fit: contain !important;
            margin: 0 auto 5pt !important;
        }

        .school-header h2 {
            display: block !important;
            font-size: 15pt !important;
            font-weight: 900 !important;
            color: #0f172a !important;
            margin: 0 0 2pt !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
        }

        .school-header .school-sub {
            display: block !important;
            font-size: 9pt !important;
            font-weight: 700 !important;
            color: #4f46e5 !important;
            margin: 1pt 0 !important;
        }

        /* ✅ UPDATED: Print styles for address lines */
        .school-address {
            display: block !important;
            font-size: 8.5pt !important;
            color: #64748b !important;
            margin: 2pt 0 !important;
            line-height: 1.3 !important;
        }

        .school-address-line {
            display: block !important;
            margin: 1pt 0 !important;
        }

        .school-contact {
            display: block !important;
            font-size: 8.5pt !important;
            color: #64748b !important;
            margin: 2pt 0 !important;
        }

        /* Invoice label */
        .invoice-title {
            display: block !important;
            text-align: center !important;
            font-size: 8pt !important;
            font-weight: 800 !important;
            letter-spacing: 3px !important;
            text-transform: uppercase !important;
            color: #4f46e5 !important;
            margin: 8pt 0 10pt !important;
        }

        /* Student + invoice meta */
        .header-section {
            display: flex !important;
            justify-content: space-between !important;
            align-items: flex-start !important;
            width: 100% !important;
            padding-bottom: 8pt !important;
            margin-bottom: 10pt !important;
            border-bottom: 1px solid #e2e8f0 !important;
        }

        .student-box { display: block !important; }

        .student-box h4 {
            display: block !important;
            font-size: 13pt !important;
            font-weight: 900 !important;
            margin: 0 0 3pt !important;
            color: #0f172a !important;
        }

        .student-box p {
            display: block !important;
            font-size: 9.5pt !important;
            margin: 2pt 0 !important;
            color: #64748b !important;
        }

        .invoice-box {
            display: block !important;
            text-align: right !important;
        }

        .invoice-badge {
            display: inline-block !important;
            font-size: 9pt !important;
            font-weight: 800 !important;
            padding: 3px 9px !important;
            background: #4f46e5 !important;
            color: white !important;
            border-radius: 999px !important;
        }

        .invoice-total {
            display: block !important;
            font-size: 13pt !important;
            font-weight: 900 !important;
            color: #059669 !important;
            margin-top: 4pt !important;
        }

        /* Table */
        .table-responsive, .mt-3 {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            overflow: visible !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .table {
            display: table !important;
            width: 100% !important;
            max-width: 100% !important;
            min-width: 0 !important;
            table-layout: fixed !important;
            border-collapse: collapse !important;
            font-size: 10pt !important;
            margin: 0 !important;
        }

        .table thead { display: table-header-group !important; }
        .table tbody { display: table-row-group !important; }
        .table tfoot { display: table-footer-group !important; }
        .table tr    { display: table-row !important; page-break-inside: avoid !important; }

        .table th,
        .table td {
            display: table-cell !important;
            padding: 7px 10px !important;
            border: 1px solid #cbd5e1 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            vertical-align: middle !important;
        }

        .table thead th {
            background: #f1f5f9 !important;
            font-size: 9.5pt !important;
            font-weight: 800 !important;
            text-align: left !important;
        }

        .table tfoot th {
            font-size: 10.5pt !important;
            font-weight: 900 !important;
            background: #f8fafc !important;
        }

        .table th:first-child,
        .table td:first-child {
            width: 75% !important;
            text-align: left !important;
        }

        .table th:last-child,
        .table td:last-child {
            width: 25% !important;
            text-align: right !important;
        }

        .amount { color: #059669 !important; font-weight: 800 !important; }

        /* Footer note */
        .footer-note {
            display: block !important;
            font-size: 8pt !important;
            margin-top: 10pt !important;
            text-align: center !important;
            width: 100% !important;
            color: #64748b !important;
            border-top: 1px dashed #e2e8f0 !important;
            padding-top: 7pt !important;
        }
    }

</style>

<script>
    function triggerPrint() {
        const invoice = document.querySelector('.invoice-container');
        if (invoice) {
            let el = invoice.parentElement;
            const overridden = [];
            while (el && el !== document.body) {
                const prev = el.getAttribute('style') || '';
                overridden.push({ el, prev });
                el.style.cssText += ';width:100%!important;max-width:100%!important;margin:0!important;padding:0!important;float:none!important;position:static!important;left:auto!important;transform:none!important;flex:none!important;overflow:visible!important;';
                el = el.parentElement;
            }
            window.print();
            overridden.forEach(({ el, prev }) => { el.setAttribute('style', prev); });
        } else {
            window.print();
        }
    }
</script>

<div class="container-fluid invoice-container">

    {{-- TOP BAR (screen only, hidden on print) --}}
    <div class="top-bar">
        <h3>Student Invoice</h3>
        <button onclick="triggerPrint()" class="btn-print">🖨 Print</button>
    </div>

    {{-- CARD --}}
    <div class="invoice-card">

        {{-- SCHOOL HEADER --}}
        <div class="school-header">

            @php
                $logoPath = $setting->logo ?? null;
                $logoPath = $logoPath ? str_replace(['storage/', 'public/'], '', $logoPath) : null;
                $logoUrl  = $logoPath ? asset('storage/' . $logoPath) : null;

                // ✅ FETCH ADDRESS FROM SETTINGS TABLE
                $schoolName    = $setting->school_name ?? 'School Name';
                $systemName    = $setting->system_name ?? null;
                
                // Handle address - can be single field or split into multiple fields
                $addressLine1  = $setting->school_address ?? $setting->address ?? $setting->address_line_1 ?? null;
                $addressLine2  = $setting->address_line_2 ?? null;
                $city          = $setting->city ?? $setting->school_city ?? null;
                $state         = $setting->state ?? $setting->school_state ?? null;
                $country       = $setting->country ?? $setting->school_country ?? null;
                $postalCode    = $setting->postal_code ?? $setting->zip_code ?? $setting->school_postal_code ?? null;
                
                // Build full address array
                $addressParts = [];
                if ($addressLine1) $addressParts[] = $addressLine1;
                if ($addressLine2) $addressParts[] = $addressLine2;
                
                $locationParts = [];
                if ($city) $locationParts[] = $city;
                if ($state) $locationParts[] = $state;
                if ($postalCode) $locationParts[] = $postalCode;
                if (count($locationParts) > 0) $addressParts[] = implode(', ', $locationParts);
                if ($country) $addressParts[] = $country;
                
                $phone         = $setting->school_phone ?? $setting->phone ?? null;
                $email         = $setting->school_email ?? $setting->email ?? null;
                $website       = $setting->website ?? $setting->school_website ?? null;
            @endphp

            @if($logoUrl)
                <img src="{{ $logoUrl }}" class="school-logo" alt="School Logo">
            @endif

            <h2>{{ $schoolName }}</h2>

            @if($systemName)
                <p class="school-sub">{{ $systemName }}</p>
            @endif

            {{-- ✅ ADDRESS DISPLAY --}}
            @if(count($addressParts) > 0)
                <div class="school-address">
                    @foreach($addressParts as $line)
                        <span class="school-address-line">{{ $line }}</span>
                    @endforeach
                </div>
            @else
                <div class="school-address">
                    <span class="school-address-line">Address not configured</span>
                </div>
            @endif

            @if($phone || $email || $website)
                <p class="school-contact">
                    @if($phone)
                        <span>Tel: {{ $phone }}</span>
                    @endif
                    @if($phone && $email)
                        <span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                    @endif
                    @if($email)
                        <span>{{ $email }}</span>
                    @endif
                    @if(($phone || $email) && $website)
                        <span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                    @endif
                    @if($website)
                        <span>{{ $website }}</span>
                    @endif
                </p>
            @endif

        </div>

        {{-- INVOICE LABEL --}}
        <div class="invoice-title">— Student Invoice —</div>

        {{-- STUDENT + INVOICE META --}}
        <div class="header-section">

            <div class="student-box">
                <h4>{{ $student->first_name }} {{ $student->last_name }}</h4>
                <p>Class: <b>{{ $invoice->schoolClass->name ?? 'N/A' }}</b></p>
                <p>Type: <b>{{ $student->student_type }}</b></p>
            </div>

            <div class="invoice-box">
                <div class="invoice-badge">Invoice #{{ $invoice->invoice_no }}</div>
                <div class="invoice-total">${{ number_format($invoice->total_amount, 2) }}</div>
            </div>

        </div>

        {{-- TABLE --}}
        <div class="table-responsive mt-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Fee Category</th>
                        <th class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->invoiceItems as $item)
                        <tr>
                            <td>{{ $item->feeCategory->name ?? 'Fee Item' }}</td>
                            <td class="text-end amount">${{ number_format($item->amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th class="text-end">${{ number_format($invoice->total_amount, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- FOOTER --}}
        <div class="footer-note">
            System generated invoice — no signature required.
        </div>

    </div>
</div>

@endsection