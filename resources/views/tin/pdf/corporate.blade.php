<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>TIN Certificate</title>
    <style>
        @page {
            margin: 0px;
        }
        body {
            font-family: sans-serif;
            margin: 0px;
            padding: 0px;
            width: 100%;
            height: 100%;
        }
        .container {
            position: relative;
            width: 100%;
            height: 100%;
        }
        .background-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        .content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        /* Adjust these positions based on the actual template */
        .taxpayer-name {
            position: absolute;
            top: 39.5%; /* Adjust based on "Last_name, First_name middle_name" position */
            left: 32%;
            font-size: 23px;
            font-weight: bold;
            color: #d41212ff;
        }
        .tin-number {
            position: absolute;
            top: 45%; /* Adjust based on "nin" position */
            left: 32%;
            font-size: 30px;
            font-weight: bold;
             color: #d41212ff;
        }
        .date-issue {
            position: absolute;
            top: 59%; /* Adjust based on "current date" position */
            left: 32%;
            font-size: 30px;
            font-weight: bold;
             color: #d41212ff;
        }
        .address {
            position: absolute;
            top: 69%; /* Adjust based on "address, lga, state, NIGERIA" position */
            left: 32%;
            font-size: 18px;
            font-weight: bold;
            width: 50%;
            line-height: 1.4;
             color: #000;
        }
        .qr-code {
            position: absolute;
            top: 44%;
            right: 10.5%; /* Adjust based on the QR code placeholder box */
            width: 140px;
            height: 140px;
        }
        .qr-code img {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Background Image --}}
        {{-- Use absolute path for dompdf --}}
        <img src="{{ public_path('assets/img/tin/tin.png') }}" class="background-image" alt="Certificate Background">

        <div class="content">
            {{-- Taxpayer Name --}}
            <div class="taxpayer-name">
                {{ strtoupper($enrollmentInfo->last_name . ', ' . $enrollmentInfo->first_name . ' ' . $enrollmentInfo->middle_name) }}
            </div>

            {{-- TIN --}}
            <div class="tin-number">
                {{ $enrollmentInfo->nin ?? $enrollmentInfo->number ?? 'N/A' }}
            </div>

            {{-- Date of Issue --}}
            <div class="date-issue">
                {{ now()->format('d M Y') }}
            </div>


            {{-- QR Code --}}
            <div class="qr-code">
                 <img src="data:image/svg+xml;base64, {{ base64_encode($qrcode) }} " />
            </div>
        </div>
    </div>
</body>
</html>
