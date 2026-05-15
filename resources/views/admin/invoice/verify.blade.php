<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Invoice - {{ $invoice->nomor_invoice }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f0f0;
            min-height: 100vh;
            padding: 20px;
        }

        .page-wrapper {
            max-width: 680px;
            margin: 0 auto;
        }

        .verify-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            width: 100%;
            overflow: hidden;
        }

        .verify-header {
            background: #1a1a1a;
            color: #fff;
            text-align: center;
            padding: 30px 20px;
        }

        .verify-header .logo {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 16px;
            border: 3px solid #fff;
        }

        .verify-header h1 {
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 1px;
            line-height: 1.6;
        }

        .verify-body {
            padding: 30px 24px;
        }

        .invoice-badge {
            display: inline-block;
            background: #f0f0f0;
            color: #333;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .verify-status {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
            padding: 12px 16px;
            background: #e8f5e9;
            border-radius: 8px;
            border-left: 4px solid #4caf50;
        }

        .verify-status .icon {
            width: 24px;
            height: 24px;
            background: #4caf50;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .verify-status .icon svg {
            width: 14px;
            height: 14px;
            fill: #fff;
        }

        .verify-status span {
            font-size: 14px;
            font-weight: 600;
            color: #2e7d32;
        }

        .info-section {
            margin-bottom: 24px;
        }

        .info-section h3 {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #999;
            margin-bottom: 12px;
        }

        .info-row {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            width: 120px;
            font-size: 13px;
            color: #666;
            flex-shrink: 0;
        }

        .info-value {
            font-size: 13px;
            color: #1a1a1a;
            font-weight: 500;
            word-break: break-word;
        }

        .token-display {
            background: #f8f8f8;
            border: 1px dashed #ccc;
            border-radius: 6px;
            padding: 10px 14px;
            font-family: 'Courier New', monospace;
            font-size: 11px;
            color: #666;
            word-break: break-all;
            margin-bottom: 24px;
        }

        .btn-softfile {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 14px;
            background: #1a1a1a;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-softfile:hover {
            background: #333;
        }

        .btn-softfile .chevron {
            display: inline-block;
            transition: transform 0.3s ease;
        }

        .btn-softfile.active .chevron {
            transform: rotate(180deg);
        }

        .preview-section {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease, margin 0.4s ease;
            margin-top: 0;
        }

        .preview-section.open {
            max-height: 2000px;
            margin-top: 20px;
        }

        .preview-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .preview-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px;
            background: #1a1a1a;
            color: #fff;
        }

        .preview-header .preview-title {
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .btn-close-preview {
            display: flex;
            align-items: center;
            gap: 6px;
            background: none;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-close-preview:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .preview-loader {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            gap: 16px;
        }

        .spinner {
            width: 36px;
            height: 36px;
            border: 3px solid #e0e0e0;
            border-top-color: #1a1a1a;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .preview-loader span {
            font-size: 13px;
            color: #999;
        }

        .preview-frame {
            width: 100%;
            height: 700px;
            border: none;
            display: block;
        }

        @media (max-width: 768px) {
            .preview-frame {
                height: 500px;
            }
        }


        .preview-fallback {
            display: none;
            text-align: center;
            padding: 40px 20px;
        }

        .preview-fallback p {
            font-size: 13px;
            color: #999;
            margin-bottom: 16px;
        }

        .btn-download {
            display: inline-block;
            padding: 12px 28px;
            background: #1a1a1a;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            transition: background 0.2s;
        }

        .btn-download:hover {
            background: #333;
        }

        .verify-footer {
            text-align: center;
            padding: 16px;
            font-size: 11px;
            color: #999;
            border-top: 1px solid #f0f0f0;
        }
    </style>
</head>

<body>
    <div class="page-wrapper">

        <div class="verify-card">
            <div class="verify-header">
                <img src="{{ asset('img/logo.jpeg') }}" alt="Jaya Express" class="logo">
                <h1>
                    DOKUMEN INI DIKELOLA DENGAN<br>
                    APLIKASI JAYA EXPRESS
                </h1>
            </div>

            <div class="verify-body">
                <div class="invoice-badge">{{ $invoice->nomor_invoice }}</div>

                <div class="verify-status">
                    <div class="icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" />
                        </svg>
                    </div>
                    <span>Dokumen Terverifikasi</span>
                </div>

                <div class="info-section">
                    <h3>Informasi Generator</h3>
                    <div class="info-row">
                        <span class="info-label">Nama</span>
                        <span class="info-value">{{ $invoice->generator->name ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $invoice->generator->email ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Perusahaan</span>
                        <span class="info-value">Jaya Express</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal Generate</span>
                        <span
                            class="info-value">{{ \Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                <div class="token-display">
                    Token: {{ $invoice->verification_token }}
                </div>


                <button type="button" id="btnTogglePreview" class="btn-softfile">
                    Lihat Softfile Invoice
                    <span class="chevron">&#9660;</span>
                </button>
            </div>

            <div class="verify-footer">
                &copy; {{ date('Y') }} Jaya Express &mdash; Sistem Verifikasi Dokumen
            </div>
        </div>

        <div id="previewSection" class="preview-section">
            <div class="preview-card">
                <div class="preview-header">
                    <span class="preview-title">{{ $invoice->nomor_invoice }}.pdf</span>
                    <button type="button" id="btnClosePreview" class="btn-close-preview">
                        &#10005;&nbsp; Tutup
                    </button>
                </div>

                <div id="previewLoader" class="preview-loader">
                    <div class="spinner"></div>
                    <span>Memuat dokumen invoice...</span>
                </div>

                <iframe id="previewFrame" class="preview-frame" style="display:none;" title="Preview Invoice"></iframe>

                <div id="previewFallback" class="preview-fallback">
                    <p>Browser tidak mendukung preview PDF.</p>
                    <a href="{{ route('invoice.download-public', $invoice->verification_token) }}" class="btn-download">
                        Download Invoice
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            var btnToggle = document.getElementById('btnTogglePreview');
            var btnClose = document.getElementById('btnClosePreview');
            var section = document.getElementById('previewSection');
            var frame = document.getElementById('previewFrame');
            var loader = document.getElementById('previewLoader');
            var fallback = document.getElementById('previewFallback');

            var pdfUrl = '{{ route('invoice.download-public', $invoice->verification_token) }}';
            var isOpen = false;
            var loaded = false;

            function openPreview() {
                isOpen = true;
                section.classList.add('open');
                btnToggle.classList.add('active');
                btnToggle.querySelector('.chevron').innerHTML = '&#9650;';

                if (!loaded) {
                    loader.style.display = 'flex';
                    frame.style.display = 'none';
                    fallback.style.display = 'none';

                    frame.src = pdfUrl;

                    frame.onload = function() {
                        loaded = true;
                        loader.style.display = 'none';
                        frame.style.display = 'block';
                    };

                    frame.onerror = function() {
                        loader.style.display = 'none';
                        fallback.style.display = 'block';
                    };

                    // Fallback timeout — if iframe doesn't fire onload within 8s
                    setTimeout(function() {
                        if (!loaded) {
                            loader.style.display = 'none';
                            fallback.style.display = 'block';
                        }
                    }, 8000);
                }
            }

            function closePreview() {
                isOpen = false;
                section.classList.remove('open');
                btnToggle.classList.remove('active');
                btnToggle.querySelector('.chevron').innerHTML = '&#9660;';
            }

            btnToggle.addEventListener('click', function() {
                if (isOpen) {
                    closePreview();
                } else {
                    openPreview();
                }
            });

            btnClose.addEventListener('click', function() {
                closePreview();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        })();
    </script>
</body>

</html>
