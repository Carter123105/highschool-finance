<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Students Report - {{ $class->name ?? 'Class' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #333;
            background: #fff;
            padding: 20px;
        }

        /* Print Controls */
        .print-controls {
            text-align: center;
            padding: 15px;
            margin-bottom: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        .print-controls button {
            background: #0d6efd;
            color: white;
            border: none;
            padding: 10px 24px;
            font-size: 14px;
            border-radius: 6px;
            cursor: pointer;
            margin: 0 5px;
            transition: background 0.2s;
        }

        .print-controls button:hover {
            background: #0b5ed7;
        }

        .print-controls a {
            display: inline-block;
            background: #6c757d;
            color: white;
            text-decoration: none;
            padding: 10px 24px;
            font-size: 14px;
            border-radius: 6px;
            margin: 0 5px;
            transition: background 0.2s;
        }

        .print-controls a:hover {
            background: #5a6268;
        }

        /* Header Section */
        .report-header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 3px double #333;
        }

        .school-logo {
            max-width: 100px;
            max-height: 100px;
            margin-bottom: 10px;
        }

        .school-name {
            font-size: 22pt;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .school-address {
            font-size: 10pt;
            color: #666;
            margin-bottom: 5px;
        }

        .school-contact {
            font-size: 9pt;
            color: #666;
            margin-bottom: 15px;
        }

        .report-title {
            font-size: 16pt;
            font-weight: bold;
            color: #0d6efd;
            margin: 15px 0 5px 0;
            text-transform: uppercase;
        }

        .report-subtitle {
            font-size: 11pt;
            color: #555;
        }

        /* Info Box */
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
        }

        .info-group {
            flex: 1;
        }

        .info-group label {
            font-weight: bold;
            color: #495057;
            font-size: 10pt;
            display: block;
            margin-bottom: 3px;
        }

        .info-group .value {
            font-size: 11pt;
            color: #212529;
        }

        /* Table Styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11pt;
        }

        .data-table thead {
            background: #0d6efd;
            color: white;
        }

        .data-table th {
            padding: 12px 10px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 10pt;
            letter-spacing: 0.5px;
            border: 1px solid #0d6efd;
        }

        .data-table td {
            padding: 10px;
            border: 1px solid #dee2e6;
            vertical-align: middle;
        }

        .data-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .data-table tbody tr:hover {
            background: #e9ecef;
        }

        .student-name {
            font-weight: 600;
            color: #212529;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 9pt;
            font-weight: 500;
        }

        .badge-day {
            background: #d1ecf1;
            color: #0c5460;
        }

        .badge-boarding {
            background: #d4edda;
            color: #155724;
        }

        .badge-scholarship {
            background: #fff3cd;
            color: #856404;
        }

        /* Footer */
        .report-footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            font-size: 9pt;
            color: #6c757d;
        }

        .signature-area {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            text-align: center;
            width: 200px;
        }

        .signature-line {
            border-top: 1px solid #333;
            padding-top: 5px;
            margin-top: 50px;
            font-size: 10pt;
        }

        /* Print Styles */
        @media print {
            body {
                padding: 0;
                font-size: 10pt;
            }

            .print-controls {
                display: none !important;
            }

            .report-header {
                border-bottom: 2px solid #333;
            }

            .data-table thead {
                background: #333 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .badge-day, .badge-boarding, .badge-scholarship {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .info-section {
                background: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .data-table tbody tr:nth-child(even) {
                background: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }

        .empty-state svg {
            width: 60px;
            height: 60px;
            margin-bottom: 15px;
            opacity: 0.5;
        }
    </style>
</head>
<body>

    <!-- Print Controls -->
    <div class="print-controls no-print">
        <button onclick="window.print()">
            <span>&#128424; Print Report</span>
        </button>
        <a href="{{ url()->previous() }}">&#8592; Back to List</a>
    </div>

    <!-- Report Header -->
    <div class="report-header">
        @if(isset($settings->logo) && $settings->logo)
            <img src="{{ asset('storage/' . $settings->logo) }}" alt="School Logo" class="school-logo">
        @endif
        <div class="school-name">{{ $settings->school_name ?? 'LIGHT ACADEMY MODEL SCHOOL SYSTEM' }}</div>
        <div class="school-address">{{ $settings->address ?? 'Diggsville Community, Schiefflin Township, Lower Margibi County' }}</div>
        <div class="school-contact">
            @if(isset($settings->phone))
                Phone: {{ $settings->phone }}
            @endif
            @if(isset($settings->email))
                | Email: {{ $settings->email }}
            @endif
        </div>
        <div class="report-title">Class Students Report</div>
        <div class="report-subtitle">
            {{ $settings->academic_year ?? 'Academic Year ' . date('Y') . ' - ' . (date('Y') + 1) }}
        </div>
    </div>

    @if(isset($class))
    <!-- Class Information -->
    <div class="info-section">
        <div class="info-group">
            <label>Class Name</label>
            <div class="value">{{ $class->name ?? 'N/A' }}</div>
        </div>
        <div class="info-group">
            <label>Section</label>
            <div class="value">{{ $class->section->name ?? 'All Sections' }}</div>
        </div>
        <div class="info-group">
            <label>Total Students</label>
            <div class="value">{{ $students->count() ?? 0 }} Students</div>
        </div>
        <div class="info-group">
            <label>Report Date</label>
            <div class="value">{{ date('F d, Y') }}</div>
        </div>
    </div>
    @endif

    @if(isset($students) && $students->count() > 0)

    <!-- Students Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 50px;">#</th>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Type</th>
                <th>Gender</th>
                <th>Section</th>
                <th>Contact</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>{{ $student->student_id ?? $student->id }}</strong></td>
                    <td class="student-name">
                        {{ $student->first_name }} {{ $student->last_name }}
                    </td>
                    <td>
                        @if($student->student_type == 'Day')
                            <span class="badge badge-day">Day Scholar</span>
                        @elseif($student->student_type == 'Boarding')
                            <span class="badge badge-boarding">Boarding</span>
                        @else
                            <span class="badge badge-scholarship">{{ $student->student_type }}</span>
                        @endif
                    </td>
                    <td>{{ $student->gender ?? 'N/A' }}</td>
                    <td>{{ $student->section->name ?? 'N/A' }}</td>
                    <td>{{ $student->phone ?? $student->parent_phone ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="report-footer">
        <div>
            <strong>Generated by:</strong> {{ auth()->user()->name ?? 'System' }} <br>
            <strong>Date & Time:</strong> {{ date('F d, Y h:i A') }}
        </div>
        <div style="text-align: right;">
            <strong>Page</strong> <span class="pageNumber"></span> of <span class="totalPages"></span>
        </div>
    </div>

    <!-- Signature Area -->
    <div class="signature-area">
        <div class="signature-box">
            <div class="signature-line">Class Teacher</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">Head of Department</div>
        </div>
        <div class="signature-box">
            <div class="signature-line">Principal</div>
        </div>
    </div>

    @else
    <!-- Empty State -->
    <div class="empty-state">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <h3>No Students Found</h3>
        <p>There are no students registered in this class.</p>
    </div>
    @endif

    <script>
        // Auto-print option (uncomment if you want auto-print on load)
        // window.onload = function() { window.print(); }
    </script>

</body>
</html>