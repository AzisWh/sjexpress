@extends('admin.layout.main')

@section('content')
    <style>
        canvas {
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            height: 200px;
        }

        .signature-preview {
            max-width: 200px;
            max-height: 100px;
            border: 1px solid #ddd;
            padding: 5px;
            border-radius: 5px;
        }
    </style>

    <div class="row">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title" style="font-weight: bold">
                    Master Signature
                </h5>

                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    + Tambah
                </button>
            </div>

            <div class="card-body">

                <div class="row mb-3">
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

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Signature</th>
                            <th>Preview</th>
                            <th width="180">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($data as $d)
                            <tr>
                                <td>{{ $d->name }}</td>

                                <td>
                                    <img src="{{ $d->signature }}" class="signature-preview">
                                </td>

                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editData({{ json_encode($d) }})">
                                        Edit
                                    </button>

                                    <form id="deleteForm_{{ $d->id }}"
                                        action="{{ route('signature.destroy', $d->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button" onclick="confirmDelete({{ $d->id }})"
                                            class="btn btn-danger btn-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">
                                    Tidak ada data signature
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
    </div>

    {{-- MODAL ADD --}}
    <div class="modal fade" id="addModal">
        <div class="modal-dialog modal-lg">

            <form action="{{ route('signature.store') }}" method="POST" id="addForm">

                @csrf

                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">
                            Tambah Signature
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label>Nama Signature</label>

                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Tanda Tangan</label>

                            <canvas id="signature-pad"></canvas>

                            <input type="hidden" name="signature" id="signature-input">

                            <div class="mt-2 d-flex gap-2">
                                <button type="button" class="btn btn-secondary btn-sm" id="clear-signature">
                                    Clear
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">
                            Simpan
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div class="modal fade" id="editModal">
        <div class="modal-dialog modal-lg">

            <form method="POST" id="editForm">

                @csrf
                @method('PATCH')

                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">
                            Edit Signature
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label>Nama Signature</label>

                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Tanda Tangan</label>

                            <canvas id="edit-signature-pad"></canvas>

                            <input type="hidden" name="signature" id="edit_signature_input">

                            <div class="mt-2 d-flex gap-2">
                                <button type="button" class="btn btn-secondary btn-sm" id="clear-edit-signature">
                                    Clear
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">
                            Update
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    <script>
        function applyFilter() {

            const perPage = document.getElementById('perPage').value;

            const params = new URLSearchParams();

            if (perPage) {
                params.append('per_page', perPage);
            }

            window.location.href =
                `{{ route('signature.index') }}?${params.toString()}`;
        }

        document.getElementById('perPage')
            ?.addEventListener('change', applyFilter);

        // ADD SIGNATURE
        const canvas = document.getElementById('signature-pad');

        const signaturePad = new SignaturePad(canvas);

        document.getElementById('clear-signature')
            .addEventListener('click', function() {
                signaturePad.clear();
            });

        document.getElementById('addForm')
            .addEventListener('submit', function(e) {

                if (signaturePad.isEmpty()) {

                    e.preventDefault();

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Signature wajib diisi!'
                    });

                    return;
                }

                document.getElementById('signature-input').value =
                    signaturePad.toDataURL();
            });

        // EDIT SIGNATURE
        const editCanvas = document.getElementById('edit-signature-pad');

        const editSignaturePad = new SignaturePad(editCanvas);

        document.getElementById('clear-edit-signature')
            .addEventListener('click', function() {
                editSignaturePad.clear();
            });

        function editData(d) {

            $('#editForm').attr(
                'action',
                '/admin-signature/update/' + d.id
            );

            $('#edit_name').val(d.name);

            editSignaturePad.clear();

            editSignaturePad.fromDataURL(d.signature);

            $('#edit_signature_input').val(d.signature);

            $('#editModal').modal('show');
        }

        document.getElementById('editForm')
            .addEventListener('submit', function(e) {

                if (editSignaturePad.isEmpty()) {

                    e.preventDefault();

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Signature wajib diisi!'
                    });

                    return;
                }

                document.getElementById('edit_signature_input').value =
                    editSignaturePad.toDataURL();
            });

        function confirmDelete(id) {

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data ini akan dihapus permanen!',
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
    </script>
@endsection
