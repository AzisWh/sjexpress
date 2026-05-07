@extends('admin.layout.main')

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title " style="font-weight: bold">Data Driver</h5>
                <div class="d-flex flex-row gap-2">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">+ Tambah</button>
                </div>
            </div>
            <div class="card-body">

                <table class="table table-bordered ">
                    <thead>
                        <tr>
                            <th>Nama Driver</th>
                            <th>No Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $d)
                            <tr>
                                <td>{{ $d->name }}</td>
                                <td>{{ $d->no_telp }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm"
                                        onclick="editData({{ $d }})">Edit</button>
                                    <form id="deleteForm_{{ $d->id }}" action="{{ route('driver.destroy', $d->id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmDelete({{ $d->id }})"
                                            class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{-- modal add --}}
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addDriverLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('driver.store') }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Driver</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Driver</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="no_telp" class="form-label">No Telepon</label>
                                <input type="number" class="form-control" id="no_telp" name="no_telp" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" type="submit">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editPenerimaLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Driver</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="edit_id">
                            <div class="mb-3">
                                <label class="form-label">Nama Driver</label>
                                <input type="text" name="name" id="edit_name" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>No Telepon</label>
                                <input type="number" name="no_telp" id="edit_no_telp" class="form-control">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editData(data) {
            $('#edit_id').val(data.id);
            $('#edit_name').val(data.name);
            $('#edit_no_telp').val(data.no_telp);
            $('#editForm').attr('action', '/admin-driver/update/' + data.id);
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
    </script>
@endsection
