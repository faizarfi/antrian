<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Tiket - {{ $ticket->ticket_number }}</title>
    <style>
        @page {
            margin: 0;
            size: auto;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 4mm 6mm;
            color: #000;
            background: #fff;
            width: 100%;
            max-width: 78mm;
            font-size: 10pt;
            line-height: 1.3;
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }
        .header-title { font-size: 13pt; font-weight: bold; }
        .header-sub { font-size: 11pt; font-weight: bold; }
        .header-addr { font-size: 8pt; border-bottom: 1px dashed #000; padding-bottom: 5px; margin-bottom: 8px; }
        .ticket-box { margin: 8px 0; }
        .ticket-label { font-size: 10pt; }
        .ticket-number { font-size: 34pt; font-weight: 900; margin: 4px 0; }
        .service-name { font-size: 11pt; font-weight: bold; text-transform: uppercase; }
        .meta-table { width: 100%; border-top: 1px dashed #000; border-bottom: 1px dashed #000; padding: 6px 0; margin: 10px 0; font-size: 9pt; }
        .footer-note { font-size: 8pt; margin-top: 8px; line-height: 1.3; }
    </style>
</head>
<body onload="window.print()">
    <div class="text-center">
        <div class="header-title">BADAN PUSAT STATISTIK</div>
        <div class="header-sub">KABUPATEN KARANGANYAR</div>
        <div class="header-addr">{{ $officeAddress }}</div>

        <div class="ticket-box">
            <div class="ticket-label">NOMOR ANTREAN</div>
            <div class="ticket-number">{{ $ticket->ticket_number }}</div>
            <div class="service-name">{{ $ticket->service->name }}</div>
        </div>

        <div class="meta-table text-left">
            <div>Tanggal: {{ $ticket->created_at->translatedFormat('d F Y') }}</div>
            <div>Waktu  : {{ $ticket->created_at->format('H.i') }} WIB</div>
        </div>

        <div class="footer-note">
            {{ $footerNote }}<br>
            Seluruh Pelayanan BPS Bebas Biaya (GRATIS).
        </div>
    </div>
</body>
</html>

