<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editArmadaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Armada</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label">Nama Armada</label>
                        <input type="text" name="nama_armada" id="edit_nama_armada" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Plat Nomor</label>
                        <input type="text" name="plat_nomor" id="edit_plat_nomor" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto Armada</label>
                        <input type="file" name="foto_armada" id="edit_foto_armada" class="form-control" accept="image/*">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto</small>
                    </div>
                    <div id="edit_preview"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
