@extends('admin.layout.main')

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title " style="font-weight: bold">Data Pt</h5>
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

                <table class="table table-bordered ">
                    <thead>
                        <tr>
                            <th>Nama Pt</th>
                            <th>PIC</th>
                            <th>No PIC</th>
                            <th>Alamat</th>
                            <th>Penagihan</th>
                            <th>No Penagihan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $d)
                            <tr>
                                <td>{{ $d->name }}</td>
                                <td>{{ $d->pic }}</td>
                                <td>{{ $d->no_pic }}</td>
                                <td>{{ $d->alamat }}</td>
                                <td>{{ $d->penagihan }}</td>
                                <td>{{ $d->no_penagihan }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm"
                                        onclick="editData({{ $d }})">Edit</button>
                                    <form id="deleteForm_{{ $d->id }}" action="{{ route('pt.destroy', $d->id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmDelete({{ $d->id }})"
                                            class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data pt.</td>
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

        <!-- Modal Tambah -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addPenerimaLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('pt.store') }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah PT</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama PT</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Pic</label>
                                <input type="text" name="pic" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nomor Pic</label>
                                <input type="number" name="no_pic" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <input type="text" name="alamat" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Penagihan</label>
                                <input type="text" name="penagihan" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nomor Penagihan</label>
                                <input type="number" name="no_penagihan" class="form-control" required>
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
                            <h5 class="modal-title">Edit PT</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="edit_id">
                            <div class="mb-3">
                                <label class="form-label">Nama PT</label>
                                <input type="text" name="name" id="edit_name" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Nama Pic</label>
                                <input type="text" name="pic" id="edit_pic" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Nomor Pic</label>
                                <input type="number" name="no_pic" id="edit_no_pic" class="form-control"
                                    step="0.01">
                            </div>
                            <div class="mb-3">
                                <label>Alamat</label>
                                <input type="text" name="alamat" id="edit_alamat" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Penagihan</label>
                                <input type="text" name="penagihan" id="edit_penagihan" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Nomor Penagihan</label>
                                <input type="number" name="no_penagihan" id="edit_no_penagihan" class="form-control"
                                    step="0.01">
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
            $('#editForm').attr('action', '/admin-pt/update/' + d.id);
            $('#edit_id').val(d.id);
            $('#edit_name').val(d.name);
            $('#edit_pic').val(d.pic);
            $('#edit_no_pic').val(d.no_pic);
            $('#edit_alamat').val(d.alamat);
            $('#edit_penagihan').val(d.penagihan);
            $('#edit_no_penagihan').val(d.no_penagihan);
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
