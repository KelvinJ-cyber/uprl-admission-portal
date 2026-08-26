<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Screening Report</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            color: #1f2937;
            font-size: 12px;
            margin: 40px;
        }

        .header {
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
        }

        .header h2 {
            margin: 4px 0 0;
            font-size: 14px;
            font-weight: normal;
            color: #374151;
        }

        .separator {
            border: none;
            border-top: 2px solid #111827;
            margin: 16px 0;
        }

        .section-title {
            margin: 22px 0 8px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            color: #374151;
        }

        table.details {
            width: 100%;
            border-collapse: collapse;
        }

        table.details td {
            padding: 6px 4px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }

        td.label {
            width: 40%;
            color: #6b7280;
        }

        td.value {
            font-weight: bold;
        }

        .status-box {
            border: 2px solid #9ca3af;
            padding: 12px 16px;
            margin: 4px 0;
        }

        .status-box .status-label {
            font-size: 11px;
            text-transform: uppercase;
            color: #6b7280;
        }

        .status-box .status-value {
            font-size: 16px;
            font-weight: bold;
        }

        .deficiency p {
            margin: 4px 0 0;
            line-height: 1.5;
        }

        .footer {
            margin-top: 48px;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>UNIVERSITY OF PUBLIC RELATIONS NIGERIA</h1>
        <h2>Screening Report</h2>
    </div>

    <hr class="separator">

    <div class="section-title">Candidate Details</div>
    <table class="details">
        <tr>
            <td class="label">JAMB Registration Number</td>
            <td class="value">{{ $candidate->jamb_reg_number }}</td>
        </tr>
        <tr>
            <td class="label">Full Name</td>
            <td class="value">{{ $candidate->surname }} {{ $candidate->first_name }} {{ $candidate->other_names }}</td>
        </tr>
        <tr>
            <td class="label">Course Applied For</td>
            <td class="value">{{ $candidate->course->name ?? 'N/A' }}</td>
        </tr>
    </table>

    <div class="section-title">Screening Result</div>

    @php
        $passed = $report->status === 'screening_passed';
        $borderColor = $passed ? '#16a34a' : '#dc2626';
        $backgroundColor = $passed ? '#f0fdf4' : '#fef2f2';
        $textColor = $passed ? '#166534' : '#991b1b';
    @endphp

    <div class="status-box" style="border-color: {{ $borderColor }}; background-color: {{ $backgroundColor }};">
        <div class="status-label">Status</div>
        <div class="status-value" style="color: {{ $textColor }};">
            {{ strtoupper(str_replace('_', ' ', $report->status)) }}
        </div>
    </div>

    @if (! empty($report->deficiency_reason))
        <div class="deficiency">
            <div class="section-title">Deficiency Notes</div>
            <p>{{ $report->deficiency_reason }}</p>
        </div>
    @endif

    <table class="details" style="margin-top: 16px;">
        <tr>
            <td class="label">Date Generated</td>
            <td class="value">{{ $report->generated_at->format('F j, Y g:i A') }}</td>
        </tr>
    </table>

    <div class="footer">
        This is a system-generated report from the UPR Admission Portal.
    </div>
</body>
</html>
