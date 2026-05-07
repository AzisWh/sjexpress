<!-- Modal Tambah -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addArmadaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('armada.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Armada</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Armada</label>
                        <input type="text" name="nama_armada" id="add_nama_armada" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Plat Nomor</label>
                        <input type="text" name="plat_nomor" id="add_plat_nomor" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto Armada</label>
                        <input type="file" name="foto_armada" id="add_foto_armada" class="form-control" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG, GIF (Max 2MB)</small>
                    </div>
                    <div id="add_preview"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
