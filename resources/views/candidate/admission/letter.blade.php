<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Offer of Admission</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            color: #1f2937;
            font-size: 12px;
            line-height: 1.7;
            margin: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 32px;
        }

        .header .university {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
        }

        .header .letter-title {
            margin: 6px 0 0;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #374151;
        }

        .date {
            text-align: right;
            font-size: 12px;
            margin: 8px 0 12px;
        }

        .separator {
            border: none;
            border-top: 2px solid #111827;
            margin: 0 0 28px;
        }

        p {
            margin: 0 0 14px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 24px 0;
        }

        .details-table td {
            border: 1px solid #9ca3af;
            padding: 8px 12px;
            vertical-align: top;
        }

        .details-table .label {
            width: 32%;
            font-weight: bold;
            background-color: #f3f4f6;
            color: #374151;
        }

        .closing {
            margin-top: 32px;
        }

        .signature-line {
            margin: 28px 0 4px;
        }

        .signer {
            font-weight: bold;
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
        <h1 class="university">UNIVERSITY OF PUBLIC RELATIONS NIGERIA</h1>
        <p class="letter-title">Offer of Admission</p>
    </div>

    <p class="date">{{ now()->format('F j, Y') }}</p>

    <hr class="separator">

    <p>Dear {{ $candidate->first_name }} {{ $candidate->surname }},</p>

    <p>
        We are pleased to congratulate you on being offered admission into
        {{ $candidate->course->name ?? 'your selected course' }} at the University of Public Relations Nigeria,
        with JAMB Registration Number {{ $candidate->jamb_reg_number }}.
    </p>

    <p>
        To secure your place, you are required to pay the acceptance fee and complete your registration within the
        stipulated period. Please note that failure to do so may result in the forfeiture of this offer of admission.
    </p>

    <table class="details-table">
        <tr>
            <td class="label">JAMB Registration Number</td>
            <td>{{ $candidate->jamb_reg_number }}</td>
        </tr>
        <tr>
            <td class="label">Full Name</td>
            <td>{{ trim($candidate->surname.' '.$candidate->first_name.' '.$candidate->other_names) }}</td>
        </tr>
        <tr>
            <td class="label">Course</td>
            <td>{{ $candidate->course->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Faculty</td>
            <td>{{ $candidate->course->faculty->name ?? 'N/A' }}</td>
        </tr>
    </table>

    <div class="closing">
        <p>We look forward to welcoming you to the University.</p>
        <p>Sincerely,</p>
        <p class="signature-line">___________________________</p>
        <p class="signer">Admissions Office, University of Public Relations Nigeria</p>
    </div>

    <div class="footer">
        This is a system-generated admission letter from the UPR Admission Portal.
    </div>
</body>
</html>