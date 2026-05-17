    @extends('admin.layout.main')

    <style>
        /* ===== TYPOGRAPHY & SPACING ===== */
        h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 1.5rem;
            letter-spacing: -0.5px;
        }

        .container-fluid > .row:first-child h1 {
            font-size: 2.2rem;
            font-weight: 800;
        }

        /* ===== CARD STYLING ===== */
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* ===== BUTTON STYLING ===== */
        .btn {
            border: none;
            border-radius: 8px;
            font-weight: 600;
            padding: 0.6rem 1.2rem;
            transition: all 0.3s ease;
            text-transform: none;
            letter-spacing: 0.3px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-color: transparent;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-color: transparent;
            color: white;
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            color: white;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-color: transparent;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            color: white;
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        /* ===== TABLE STYLING ===== */
        .table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            color: #1f2937;
            font-weight: 700;
            border: none;
            padding: 1rem;
            font-size: 0.95rem;
            text-transform: none;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            border-bottom: 1px solid #f0f0f0;
            transition: background-color 0.2s ease;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #fafafa;
        }

        .table tbody tr:nth-child(even) {
            background-color: #ffffff;
        }

        .table tbody tr:hover {
            background-color: #f0f9ff;
            box-shadow: inset 0 0 8px rgba(59, 130, 246, 0.1);
        }

        .table td {
            padding: 0.95rem 1rem;
            color: #374151;
            vertical-align: middle;
        }

        .table td:first-child {
            border-radius: 8px 0 0 8px;
        }

        .table td:last-child {
            border-radius: 0 8px 8px 0;
        }

        /* ===== FORM STYLING ===== */
        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-select,
        .form-control {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.65rem 0.75rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            font-size: 0.95rem;
        }

        .form-select:focus,
        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* ===== ACTION BUTTONS ===== */
        .action-buttons {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .action-buttons button,
        .action-buttons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.65rem 0.75rem;
            font-size: 0.85rem;
            width: 115px;
            white-space: normal;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            height: 38px;
            line-height: 1.2;
        }

        .action-buttons .btn-sm {
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
            padding: 0.65rem 1rem;
            font-size: 0.85rem;
        }

        .action-buttons .btn-sm:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.18);
            transform: translateY(-1px);
        }

        .action-buttons .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .action-buttons .btn-warning:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            color: white;
        }

        .action-buttons .btn-info {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            color: white;
        }

        .action-buttons .btn-info:hover {
            background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);
            color: white;
        }

        .action-buttons .btn-outline-primary {
            background: white;
            color: #3b82f6;
            border: 2px solid #3b82f6;
        }

        .action-buttons .btn-outline-primary:hover {
            background: #3b82f6;
            color: white;
        }

        .action-buttons .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .action-buttons .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
        }

        /* ===== BADGE STYLING ===== */
        .badge {
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
            font-weight: 600;
            border-radius: 6px;
            text-transform: none;
            letter-spacing: 0.3px;
        }

        /* ===== PADDING & SPACING ===== */
        .body-wrapper .container-fluid {
            padding-top: 100px !important;
        }

        @media (max-width: 991.98px) {
            .body-wrapper .container-fluid {
                padding-top: 100px !important;
            }
        }

        @media (max-width: 767.98px) {
            .body-wrapper > .container-fluid {
                padding: 80px 20px !important;
            }
            h1 {
                font-size: 1.5rem;
            }
        }

        .nowrap-table th,
        .nowrap-table td {
            white-space: nowrap;
        }

        /* ===== GALLERY ITEMS ===== */
        .foto-gallery-item {
            position: relative;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 15px;
            background: #fafafa;
            transition: box-shadow 0.3s ease;
        }

        .foto-gallery-item:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .foto-gallery-item img {
            width: 100%;
            max-height: 300px;
            object-fit: contain;
            border-radius: 6px;
        }

        .foto-gallery-item .btn-delete-foto {
            position: absolute;
            top: 8px;
            right: 8px;
        }

        .foto-gallery-item .foto-info {
            font-size: 0.8rem;
            color: #6b7280;
            margin-top: 8px;
            word-break: break-all;
        }
    </style>

    @section('content')
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1>Manajemen Pengiriman</h1>
                        <div class="d-flex gap-2">
                            <button class="btn btn-success" id="btnExportExcel" disabled>
                                <i class="bi bi-file-earmark-excel"></i> Export Excel
                            </button>

                            <button class="btn btn-warning" id="btnGenerateInvoice" disabled>
                                <i class="bi bi-file-earmark-pdf"></i> Cetak Invoice PDF
                            </button>

                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPengiriman">
                                <i class="bi bi-plus-circle"></i> Tambah Pengiriman
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label class="form-label">Filter PT</label>

                                    <select name="pt_id" id="filterPt" class="form-select">
                                        <option value="">-- Semua PT --</option>

                                        @foreach ($pt as $p)
                                            <option value="{{ $p->id }}"
                                                {{ request('pt_id') == $p->id ? 'selected' : '' }}>
                                                {{ $p->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">Show Data</label>

                                    <select id="perPage" class="form-select">
                                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>
                                            10
                                        </option>

                                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>
                                            25
                                        </option>

                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>
                                            50
                                        </option>

                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>
                                            100
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="table-responsive" style="overflow-x: auto;">
                                <table class="table table-striped table-hover nowrap-table" id="tablePengiriman">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" class="form-check-input" id="selectAll">
                                            </th>
                                            <th>No</th>
                                            <th>PT</th>
                                            <th>Rute</th>
                                            <th>Driver</th>
                                            <th>Armada</th>
                                            <th>Tanggal Ambil</th>
                                            <th>Harga Pabrik</th>
                                            <th>Foto Surat Jalan</th>
                                            <th>Keterangan</th>
                                            <th>Status Invoice</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data as $key => $item)
                                            @php
                                                $hasInvoice = $item->invoiceDetails()->exists();
                                            @endphp
                                            <tr data-id="{{ $item->id }}" data-pt-id="{{ $item->pt_id }}"
                                                data-has-foto="{{ $item->fotos->count() > 0 ? '1' : '0' }}">
                                                <td>
                                                    <input type="checkbox" class="form-check-input row-check"
                                                        value="{{ $item->id }}" data-pt-id="{{ $item->pt_id }}"
                                                        data-has-foto="{{ $item->fotos->count() > 0 ? '1' : '0' }}">
                                                </td>
                                                <td>
                                                    {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                                </td>
                                                <td>
                                                    <strong>{{ $item->pt->name ?? '-' }}</strong>
                                                </td>
                                                <td>
                                                    {{ $item->rute_from }} - {{ $item->rute_to }}
                                                </td>
                                                <td>
                                                    {{ $item->driver->name ?? '-' }}
                                                </td>
                                                <td>
                                                    {{ $item->armada->nama_armada }} -
                                                    {{ $item->armada->plat_nomor ?? '-' }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($item->tanggal_ambil)->format('d/m/Y') }}
                                                </td>
                                                <td>
                                                    {{ formatRupiah($item->harga_pabrik) }}
                                                </td>
                                                <td>
                                                    @if ($item->fotos->count() > 0)
                                                        <button class="btn btn-sm btn-outline-info" type="button"
                                                            onclick="openFotoModal({{ $item->id }})">
                                                            <i class="bi bi-image"></i> {{ $item->fotos->count() }} Foto
                                                        </button>
                                                    @else
                                                        <span class="badge bg-danger">
                                                            <i class="bi bi-exclamation-circle"></i> Belum Ada
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $item->keterangan ?? 'Belum ada keterangan' }}
                                                </td>
                                                <td>
                                                    @if ($hasInvoice)
                                                        <span class="badge bg-info">
                                                            <i class="bi bi-check-circle"></i> Sudah Cetak
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning">
                                                            <i class="bi bi-clock"></i> Belum Cetak
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <button class="btn btn-sm btn-warning" type="button"
                                                            onclick="editData({{ json_encode($item) }})">
                                                            <i class="bi bi-pencil"></i> Edit
                                                        </button>
                                                        <button class="btn btn-sm btn-info" type="button"
                                                            data-bs-toggle="modal" data-bs-target="#modalUploadFoto"
                                                            onclick="setUploadFotoId({{ $item->id }}, {{ $item->fotos->count() }})">
                                                            <i class="bi bi-cloud-upload"></i> Upload
                                                        </button>
                                                        @if ($item->fotos->count() > 0)
                                                            <button class="btn btn-sm btn-outline-primary" type="button"
                                                                onclick="openFotoModal({{ $item->id }})">
                                                                <i class="bi bi-eye"></i> Lihat Foto
                                                            </button>
                                                        @endif
                                                        <button class="btn btn-sm btn-danger" type="button"
                                                            onclick="confirmDelete({{ $item->id }})">
                                                            <i class="bi bi-trash"></i> Hapus
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="11" class="text-center text-muted">
                                                    Belum ada data pengiriman
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                                        <div>
                                            Showing {{ $data->firstItem() ?? 0 }}
                                            to {{ $data->lastItem() ?? 0 }}
                                            of {{ $data->total() }} entries
                                        </div>

                                        <div>
                                            {{ $data->links('pagination::bootstrap-4') }}
                                        </div>
                                    </div>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Pengiriman -->
        <div class="modal fade" id="modalTambahPengiriman" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Pengiriman</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('pengiriman.store') }}" method="POST" enctype="multipart/form-data"
                        id="formTambahPengiriman">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">PT <span class="text-danger">*</span></label>
                                <select class="form-select" name="pt_id" required>
                                    <option value="">-- Pilih PT --</option>
                                    @foreach ($pt as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Armada <span class="text-danger">*</span></label>
                                    <select class="form-select" name="armada_id" required>
                                        <option value="">-- Pilih Armada --</option>
                                        @foreach ($armada as $a)
                                            <option value="{{ $a->id }}">{{ $a->nama_armada }} -
                                                {{ $a->plat_nomor }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Driver <span class="text-danger">*</span></label>
                                    <select class="form-select" name="driver_id" required>
                                        <option value="">-- Pilih Driver --</option>
                                        @foreach ($driver as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal Ambil <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tanggal_ambil" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Rute Dari <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="rute_from"
                                        placeholder="Lokasi awal" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Rute Ke <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="rute_to"
                                        placeholder="Lokasi tujuan" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Harga Pabrik <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="harga_pabrik" step="0.01"
                                        required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Harga Armada <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="harga_armada" step="0.01"
                                        required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Keterangan</label>
                                    <textarea class="form-control" name="keterangan" rows="3"
                                        placeholder="Tambahkan keterangan tambahan jika diperlukan..."></textarea>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Foto Surat Jalan</label>
                                <input type="file" class="form-control" name="foto[]" id="tambahFoto" multiple
                                    accept="image/*">
                                <small class="text-muted">Bisa upload lebih dari 1 file (JPG, PNG, max 2MB)</small>
                            </div>
                            <div id="tambahFilePreview"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit Pengiriman -->
        <div class="modal fade" id="modalEditPengiriman" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Pengiriman</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="formEditPengiriman" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">PT <span class="text-danger">*</span></label>
                                <select class="form-select" name="pt_id" id="edit_pt_id" required>
                                    <option value="">-- Pilih PT --</option>
                                    @foreach ($pt as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Armada <span class="text-danger">*</span></label>
                                    <select class="form-select" name="armada_id" id="edit_armada_id" required>
                                        <option value="">-- Pilih Armada --</option>
                                        @foreach ($armada as $a)
                                            <option value="{{ $a->id }}">{{ $a->nama_armada }} -
                                                {{ $a->plat_nomor }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Driver <span class="text-danger">*</span></label>
                                    <select class="form-select" name="driver_id" id="edit_driver_id" required>
                                        <option value="">-- Pilih Driver --</option>
                                        @foreach ($driver as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal Ambil <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tanggal_ambil" id="edit_tanggal_ambil"
                                    required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Rute Dari <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="rute_from" id="edit_rute_from"
                                        placeholder="Lokasi awal" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Rute Ke <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="rute_to" id="edit_rute_to"
                                        placeholder="Lokasi tujuan" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Harga Pabrik <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="harga_pabrik"
                                        id="edit_harga_pabrik" step="0.01" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Harga Armada <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="harga_armada"
                                        id="edit_harga_armada" step="0.01" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Keterangan</label>
                                        <textarea class="form-control" id="edit_keterangan" name="keterangan" rows="3"
                                            placeholder="Tambahkan keterangan tambahan jika diperlukan..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Upload Foto -->
        <div class="modal fade" id="modalUploadFoto" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Upload Surat Jalan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="formUploadFoto" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <strong>Info:</strong> Upload multiple file surat jalan (JPG, PNG, max 2MB per file)
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Pilih File <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="foto[]" id="fotoPengiriman" multiple
                                    accept="image/*" required>
                                <small class="text-muted">Bisa upload lebih dari 1 file sekaligus</small>
                            </div>
                            <div id="filePreview"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Lihat Foto -->
        <div class="modal fade" id="modalLihatFoto" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Foto Surat Jalan - Pengiriman #<span id="fotoPengirimanId"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="fotoGalleryContainer">
                            <div class="text-center text-muted py-4">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Memuat foto...</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.pengiriman.javascript')
    @endsection
