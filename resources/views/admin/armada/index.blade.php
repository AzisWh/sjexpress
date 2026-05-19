@extends('admin.layout.main')

<style>
    /* ===== TYPOGRAPHY & SPACING ===== */
    h1,
    h5 {
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 1.5rem;
        letter-spacing: -0.5px;
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

        /* ===== IMAGE PREVIEW ===== */
        .img-preview {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            border-radius: 5px;
        }
</style>

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title " style="font-weight: bold">Data Armada</h5>
                <div class="d-flex flex-row gap-2">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">+ Tambah</button>
                </div>
            </div>
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-2">
                        <label class="form-label">Show Data</label>
                        <select id="perPage" class="form-select">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Armada</th>
                            <th>Plat Nomor</th>
                            <th>Foto Armada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $d)
                            <tr>
                                <td>{{ $d->nama_armada }}</td>
                                <td>{{ $d->plat_nomor }}</td>
                                <td>
                                    @if ($d->foto_armada)
                                        <img src="{{ asset('public/storage/FotoArmada/' . $d->foto_armada) }}"
                                            width="80" class="rounded" alt="Foto Armada">
                                    @else
                                        <img src="{{ asset('images/no-image.png') }}" width="80" class="rounded"
                                            alt="No Image">
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-warning btn-sm"
                                            onclick="editData({{ $d }})">Edit</button>
                                        <form id="deleteForm_{{ $d->id }}"
                                            action="{{ route('armada.destroy', $d->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="button" onclick="confirmDelete({{ $d->id }})"
                                                class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    Belum ada data armada
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

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
            </div>
        </div>

        @include('admin.armada.component.modal-add')
        @include('admin.armada.component.modal-edit')

    </div>

    <script>
        function applyFilter() {
            const perPage = document.getElementById('perPage').value;
            const params = new URLSearchParams();

            if (perPage) {
                params.append('per_page', perPage);
            }

            window.location.href = `{{ route('armada.index') }}?${params.toString()}`;
        }

        document.getElementById('perPage')?.addEventListener('change', applyFilter);

        function editData(d) {
            $('#editForm').attr('action', '/admin-armada/update/' + d.id);
            $('#edit_id').val(d.id);
            $('#edit_nama_armada').val(d.nama_armada);
            $('#edit_plat_nomor').val(d.plat_nomor);

            // Preview existing image
            if (d.foto_armada) {
                $('#edit_preview').html(
                    `<img src="{{ asset('storage/FotoArmada') }}/${d.foto_armada}" class="img-preview" alt="Preview">`);
            } else {
                $('#edit_preview').html(
                    `<img src="{{ asset('images/no-image.png') }}" class="img-preview" alt="No Image">`);
            }

            $('#editModal').modal('show');
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data ini akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm_' + id).submit();
                }
            });
        }

        // Preview image untuk modal tambah
        document.getElementById('add_foto_armada')?.addEventListener('change', function(e) {
            const reader = new FileReader();
            reader.onload = function(event) {
                $('#add_preview').html(`<img src="${event.target.result}" class="img-preview" alt="Preview">`);
            };
            reader.readAsDataURL(e.target.files[0]);
        });

        // Preview image untuk modal edit
        document.getElementById('edit_foto_armada')?.addEventListener('change', function(e) {
            const reader = new FileReader();
            reader.onload = function(event) {
                $('#edit_preview').html(`<img src="${event.target.result}" class="img-preview" alt="Preview">`);
            };
            reader.readAsDataURL(e.target.files[0]);
        });
    </script>
@endsection
