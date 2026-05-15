<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->nomor_invoice }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .invoice-page {
            padding: 20px 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 25px;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
        }

        .header-left .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header-left .company-info {
            font-size: 10px;
            color: #666;
            line-height: 1.5;
        }

        .header-right {
            text-align: right;
        }

        .header-right .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .header-right .invoice-number {
            font-size: 12px;
            color: #666;
        }

        .customer-info {
            margin-bottom: 20px;
            padding: 10px 15px;
            background: #f8f9fa;
            border-radius: 4px;
        }

        .customer-info h4 {
            margin: 0 0 8px 0;
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .customer-info .name {
            font-size: 14px;
            font-weight: bold;
        }

        .customer-info .address {
            font-size: 11px;
            color: #666;
            margin-top: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table thead th {
            background: #333;
            color: #fff;
            padding: 8px 10px;
            text-align: center;
            font-size: 11px;
            font-weight: bold;
        }

        table thead th:first-child {
            border-radius: 4px 0 0 0;
        }

        table thead th:last-child {
            border-radius: 0 4px 0 0;
        }

        table tbody td {
            padding: 8px 10px;
            border-bottom: 1px solid #dee2e6;
            font-size: 11px;
        }

        table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row {
            background: #333 !important;
            color: #fff;
        }

        .total-row td {
            font-weight: bold;
            font-size: 13px;
            border-bottom: none !important;
        }

        .terbilang-section {
            margin: 10px 0 20px 0;
            padding: 8px 15px;
            background: #f8f9fa;
            border-left: 4px solid #333;
            font-style: italic;
            font-size: 11px;
        }

        .transfer-info {
            margin-bottom: 25px;
            padding: 10px 15px;
            background: #f8f9fa;
            border-radius: 4px;
        }

        .transfer-info h4 {
            margin: 0 0 8px 0;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #666;
        }

        .transfer-info p {
            margin: 2px 0;
            font-size: 11px;
        }

        .signature-section {
            margin-top: 30px;
            text-align: right;
        }

        .signature-section .hormat-kami {
            margin-bottom: 60px;
            font-size: 11px;
        }

        .signature-section .signature-line {
            border-top: 1px solid #333;
            display: inline-block;
            width: 200px;
            padding-top: 5px;
            font-size: 11px;
        }

        .surat-jalan-section {
            margin-top: 30px;
            page-break-before: always;
        }

        .surat-jalan-section h3 {
            font-size: 14px;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }

        .surat-jalan-item {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .surat-jalan-item .sj-info {
            background: #333;
            color: #fff;
            padding: 8px 12px;
            border-radius: 4px 4px 0 0;
            font-size: 11px;
        }

        .surat-jalan-item .sj-info p {
            margin: 2px 0;
        }

        .surat-jalan-item .sj-image {
            border: 1px solid #dee2e6;
            border-top: none;
            padding: 10px;
            text-align: center;
        }

        .surat-jalan-item .sj-image img {
            max-width: 100%;
            max-height: 400px;
        }

        .surat-jalan-item .sj-filename {
            font-size: 9px;
            color: #999;
            margin-top: 5px;
        }

        .footer-note {
            margin-top: 15px;
            font-size: 9px;
            color: #999;
            text-align: center;
        }
    </style>
</head>

<body>
    {{-- INVOICE PAGE --}}
    <div class="invoice-page">
        {{-- HEADER --}}
        <div class="header">
            <div class="header-left">
                <div class="company-name">JAYA EXPRESS</div>
                <div class="company-info">
                    Jl. Raya Transportasi No. 123<br>
                    Telp: (021) 123-4567<br>
                    Email: info@jayaexpress.co.id
                </div>
            </div>
            <div class="header-right">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-number">{{ $invoice->nomor_invoice }}</div>
                <div style="font-size: 11px; color: #666; margin-top: 5px;">
                    Tanggal: {{ \Carbon\Carbon::parse($invoice->tanggal_invoice)->format('d/m/Y') }}
                </div>
            </div>
        </div>

        {{-- CUSTOMER INFO --}}
        <div class="customer-info">
            <h4>Invoice Kepada</h4>
            <div class="name">{{ $pt->name }}</div>
            @if ($pt->alamat)
                <div class="address">{{ $pt->alamat }}</div>
            @endif
            @if ($pt->pic)
                <div class="address">PIC: {{ $pt->pic }} @if ($pt->no_pic)
                        - {{ $pt->no_pic }}
                    @endif
                </div>
            @endif
        </div>

        {{-- TABLE --}}
        <table>
            <thead>
                <tr>
                    <th style="width: 40px;">NO</th>
                    <th>Ambil Paket</th>
                    <th>Armada</th>
                    <th>Rute</th>
                    <th>Harga</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pengirimanList as $i => $p)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($p->tanggal_ambil)->format('d/m/Y') }}</td>
                        <td class="text-center">{{ $p->armada->plat_nomor ?? '-' }}</td>
                        <td>{{ $p->rute_from }} - {{ $p->rute_to }}</td>
                        <td class="text-right">{{ formatRupiah($p->harga_pabrik) }}</td>
                        <td>{{ $p->keterangan ?? 'Belum ada keterangan' }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="4" class="text-right" style="padding-right: 15px;">TOTAL</td>
                    <td class="text-right">{{ formatRupiah($total) }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        {{-- TERBILANG --}}
        <div class="terbilang-section">
            <strong>Terbilang:</strong> {{ $terbilang }}
        </div>

        {{-- TRANSFER INFO --}}
        <div class="transfer-info">
            <h4>Informasi Transfer</h4>
            <p><strong>Nama Rekening:</strong> Jaya Express</p>
            <p><strong>Bank:</strong> BCA</p>
            <p><strong>Nomor Rekening:</strong> 123-456-7890</p>
        </div>

        {{-- QR VERIFICATION --}}
        @php
            $verifyUrl = route('invoice.verify', $invoice->verification_token);

            // SVG format — no Imagick required, works on shared hosting
            $qrSvg = QrCode::format('svg')
                ->size(180)
                ->margin(2)
                ->errorCorrection('H')
                ->generate($verifyUrl);

            // Embed logo into SVG center (pure string manipulation, no image library)
            $logoPath = public_path('img/logo.jpeg');
            if (file_exists($logoPath)) {
                $logoBase64 = base64_encode(file_get_contents($logoPath));

                preg_match('/viewBox="([\d\s.]+)"/', $qrSvg, $vbMatch);
                if ($vbMatch) {
                    $c = explode(' ', trim(preg_replace('/\s+/', ' ', $vbMatch[1])));
                    $vbW = (float) ($c[2] ?? 180);
                    $vbH = (float) ($c[3] ?? 180);
                } else {
                    $vbW = 180;
                    $vbH = 180;
                }

                $logoSize = $vbW * 0.22;
                $pad = $vbW * 0.012;
                $x = ($vbW - $logoSize) / 2;
                $y = ($vbH - $logoSize) / 2;

                $overlay = '<rect x="'.$x.'" y="'.$y.'" width="'.$logoSize.'" height="'.$logoSize.'" fill="#ffffff" rx="'.($vbW * 0.015).'"/>'
                    .'<image x="'.($x + $pad).'" y="'.($y + $pad).'" width="'.($logoSize - $pad * 2).'" height="'.($logoSize - $pad * 2).'" href="data:image/jpeg;base64,'.$logoBase64.'" preserveAspectRatio="xMidYMid slice"/>';

                $qrSvg = str_replace('</svg>', $overlay."\n</svg>", $qrSvg);
            }

            $qrDataUri = 'data:image/svg+xml;base64,'.base64_encode($qrSvg);
        @endphp
        <div class="signature-section">
            <div style="font-size: 10px; margin-bottom: 5px;">
                Scan QR Code untuk verifikasi invoice
            </div>
            <div style="text-align: right;">
                <img src="{!! $qrDataUri !!}" style="width: 140px; height: 140px;">
            </div>
        </div>
    </div>

    {{-- SURAT JALAN PAGES --}}
    <div class="surat-jalan-section">
        <h3>Lampiran Surat Jalan</h3>

        @foreach ($pengirimanList as $i => $pengiriman)
            @foreach ($pengiriman->fotos as $j => $foto)
                <div class="surat-jalan-item">
                    <div class="sj-info">
                        <p><strong>Surat Jalan Pengiriman #{{ $i + 1 }}</strong></p>
                        <p>{{ $pengiriman->armada->plat_nomor ?? '-' }}</p>
                        <p>{{ $pengiriman->rute_from }} - {{ $pengiriman->rute_to }}</p>
                        <p>{{ \Carbon\Carbon::parse($pengiriman->tanggal_ambil)->format('d/m/Y') }}</p>
                    </div>
                    <div class="sj-image">
                        <img src="{{ storage_path('app/public/SuratJalan/' . $foto->file_path) }}" alt="Surat Jalan">
                        <div class="sj-filename">{{ $foto->file_path }}</div>
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
</body>

</html>
