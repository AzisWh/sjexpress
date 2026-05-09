@extends('admin.layout.main')
<style>
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
            <div class="card-header">
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
                                        <img src="{{ asset('storage/FotoArmada/' . $d->foto_armada) }}" width="80"
                                            class="rounded" alt="Foto Armada">
                                    @else
                                        <img src="{{ asset('images/no-image.png') }}" width="80" class="rounded"
                                            alt="No Image">
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm"
                                        onclick="editData({{ $d }})">Edit</button>
                                    <form id="deleteForm_{{ $d->id }}"
                                        action="{{ route('armada.destroy', $d->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmDelete({{ $d->id }})"
                                            class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
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
                        {{ $data->links() }}
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
