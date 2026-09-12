<!DOCTYPE html>
<html lang="ne">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>सिफारिस पत्र - {{ $application->application_number }}</title>
    <style>
        @page {
            margin: 20mm 15mm 20mm 15mm;
            size: a4 portrait;
        }
        body {
            font-family: 'DejaVu Sans', 'Mukta', 'Arial', sans-serif;
            color: #1a202c;
            line-height: 1.6;
            font-size: 13px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #C41230;
            padding-bottom: 12px;
            margin-bottom: 15px;
            position: relative;
        }
        .emblem {
            width: 75px;
            height: auto;
            margin-bottom: 4px;
        }
        .gov-title {
            font-size: 14px;
            color: #C41230;
            font-weight: bold;
            margin: 0;
        }
        .palika-title {
            font-size: 18px;
            color: #003893;
            font-weight: 800;
            margin: 2px 0;
        }
        .ward-title {
            font-size: 14px;
            color: #1a202c;
            font-weight: bold;
            margin: 2px 0;
        }
        .address-line {
            font-size: 11px;
            color: #4a5568;
            margin: 0;
        }
        .meta-table {
            width: 100%;
            margin-top: 10px;
            margin-bottom: 15px;
            font-size: 11px;
        }
        .subject-box {
            text-align: center;
            margin: 20px 0 15px 0;
            font-size: 15px;
            font-weight: bold;
            text-decoration: underline;
            color: #000;
        }
        .salutation {
            margin-bottom: 12px;
            font-weight: bold;
        }
        .content {
            text-align: justify;
            text-indent: 40px;
            line-height: 1.8;
            font-size: 13px;
        }
        .details-box {
            margin: 15px 0;
            padding: 10px;
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }
        .details-table {
            width: 100%;
            font-size: 12px;
            border-collapse: collapse;
        }
        .details-table td {
            padding: 5px;
            border-bottom: 1px dotted #cbd5e0;
        }
        .footer-table {
            width: 100%;
            margin-top: 40px;
        }
        .stamp-box {
            width: 100px;
            height: 100px;
            border: 2px dashed #003893;
            border-radius: 50%;
            text-align: center;
            line-height: 95px;
            color: #003893;
            font-size: 10px;
            font-weight: bold;
            margin: 0 auto;
        }
        .signature-box {
            text-align: right;
            padding-right: 15px;
        }
        .signature-name {
            font-size: 13px;
            font-weight: bold;
            color: #000;
            margin-top: 30px;
            border-top: 1px solid #4a5568;
            display: inline-block;
            padding-top: 5px;
        }
        .signature-role {
            font-size: 11px;
            color: #4a5568;
        }
        .qr-section {
            margin-top: 30px;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            font-size: 10px;
            color: #718096;
        }
        .qr-table {
            width: 100%;
        }
    </style>
</head>
<body>
    <!-- Official Letterhead -->
    <div class="header">
        <svg class="emblem" viewBox="0 0 100 100" width="70" height="70" style="display:inline-block;">
            <circle cx="50" cy="50" r="45" fill="#DC143C" />
            <polygon points="50,15 61,38 85,38 66,54 73,78 50,62 27,78 34,54 15,38 39,38" fill="#FFFFFF" />
            <circle cx="50" cy="50" r="18" fill="#003893" />
        </svg>
        <p class="gov-title">नेपाल सरकार | बागमती प्रदेश</p>
        <h1 class="palika-title">{{ $palika->name_ne }}</h1>
        <h2 class="ward-title">वडा नं. {{ $ward->ward_number }} को कार्यालय</h2>
        <p class="address-line">{{ $ward->office_address ?? 'कोटेश्वर, काठमाडौँ' }} | फोन: {{ $ward->office_phone ?? '०१-४६०१२३४' }} | इमेल: {{ $ward->office_email }}</p>
    </div>

    <!-- Reference and Date Meta -->
    <table class="meta-table">
        <tr>
            <td style="width: 50%;">
                <div><strong>पत्र संख्या:</strong> २०८१/०८२</div>
                <div><strong>चलानी नम्बर:</strong> {{ $application->application_number }}</div>
            </td>
            <td style="width: 50%; text-align: right;">
                <div><strong>मिति:</strong> {{ $issuedDateBs }} (वि.सं.)</div>
                <div><strong>Date:</strong> {{ $issuedDateAd }} (A.D.)</div>
            </td>
        </tr>
    </table>

    <!-- Subject -->
    <div class="subject-box">
        विषय: {{ $serviceType->name_ne }} सम्बन्धमा।
    </div>

    <div class="salutation">
        श्री जो जससँग सम्बन्ध छ।
    </div>

    <!-- Official Content Paragraphs -->
    <div class="content">
        प्रस्तुत विषयमा यस <strong>{{ $palika->name_ne }}</strong> वडा नं. <strong>{{ $ward->ward_number }}</strong> (साविक ठेगाना {{ $citizen->address }}) निवासी श्री <strong>{{ $citizen->full_name }}</strong> (नागरिकता प्रमाणपत्र नं. <strong>{{ $citizen->citizenship_no ?? '...' }}</strong>) ले यस कार्यालयमा पेश गर्नुभएको निवेदन तथा संलग्न प्रमाण कागजातहरू अध्ययन गर्दा व्यहोरा मनासिब देखिएकोले देहाय बमोजिमको विवरण अनुसार सिफारिस/प्रमाणित गरिएको छ।
    </div>

    <!-- Dynamic details table -->
    <div class="details-box">
        <table class="details-table">
            <tr>
                <td style="width: 35%; font-weight: bold;">सेवाको नाम:</td>
                <td>{{ $serviceType->name_ne }} ({{ $serviceType->name_en }})</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">निवेदकको नाम:</td>
                <td>{{ $citizen->full_name }}</td>
            </tr>
            @if(!empty($application->form_data))
                @foreach($application->form_data as $k => $v)
                    <tr>
                        <td style="font-weight: bold; text-transform: capitalize;">{{ str_replace('_', ' ', $k) }}:</td>
                        <td>{{ is_array($v) ? json_encode($v) : $v }}</td>
                    </tr>
                @endforeach
            @endif
            <tr>
                <td style="font-weight: bold;">सिफारिसको प्रयोजन:</td>
                <td>{{ $application->form_data['purpose'] ?? 'सरकारी / प्रशासनिक कामकाजका लागि' }}</td>
            </tr>
        </table>
    </div>

    <div class="content" style="text-indent: 0; margin-top: 15px;">
        माथि उल्लिखित विवरण अनुसार सिफारिस पत्र जारी गरिएको छ। सम्बन्धित निकायले यसलाई आधिकारिक मानी आवश्यक कारबाही अघि बढाउनुहुन अनुरोध गरिन्छ।
    </div>

    <!-- Signatures and Stamps -->
    <table class="footer-table">
        <tr>
            <td style="width: 40%; text-align: center; vertical-align: bottom;">
                <div class="stamp-box">
                    वडा कार्यालयको छाप<br>(OFFICIAL STAMP)
                </div>
            </td>
            <td style="width: 60%; vertical-align: bottom;" class="signature-box">
                <div class="signature-name">
                    {{ $approver->name ?? 'भरत लाल श्रेष्ठ' }}<br>
                    <span class="signature-role">
                        {{ $approver ? ucfirst(str_replace('_', ' ', $approver->role)) : 'वडा अध्यक्ष (Ward Chair)' }}
                    </span>
                    <br>
                    <span style="font-size: 9px; color: #718096;">Digitally Signed on {{ $application->approved_at ? $application->approved_at->format('Y-m-d H:i') : date('Y-m-d') }}</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Cryptographic / QR Verification Footer -->
    <div class="qr-section">
        <table class="qr-table">
            <tr>
                <td style="width: 25%; text-align: left; vertical-align: middle;">
                    @if(!empty($qrCodeImage))
                        <img src="{{ $qrCodeImage }}" alt="QR Verification" style="width: 90px; height: 90px;">
                    @else
                        <div style="width: 80px; height: 80px; border: 1px solid #cbd5e0; line-height: 80px; text-align: center; font-size: 8px;">
                            QR CODE
                        </div>
                    @endif
                </td>
                <td style="width: 75%; vertical-align: middle; padding-left: 10px;">
                    <div><strong>आधिकारिक अनलाइन प्रमाणीकरण (QR Verification):</strong></div>
                    <div style="font-family: monospace; font-size: 9px; margin-top: 2px;">
                        टोकन: {{ $application->qr_code_token }}
                    </div>
                    <div style="font-size: 9px; margin-top: 3px; color: #4a5568;">
                        यस सिफारिस पत्रको सत्यता जाँच गर्न कुनै पनि स्मार्टफोन वा स्क्यानरबाट माथिको QR कोड स्क्यान गर्नुहोस् अथवा <u>{{ $verificationUrl }}</u> मा लगइन गर्नुहोस्।
                    </div>
                    <div style="font-size: 8px; color: #a0aec0; margin-top: 3px;">
                        स्थानीय सरकार सञ्चालन ऐन, २०७४ बमोजिम डिजिटल रूपमा उत्पन्न आधिकारिक विद्युतीय सिफारिस पत्र।
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
