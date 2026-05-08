<script>
    let editPengirimanId = null;
    let uploadFotoId = null;

    const GENERATE_INVOICE_URL = "{{ route('invoice.generate-pdf') }}";
    const EXPORT_EXCEL_URL = "{{ route('export.pengiriman-excel') }}";
    const DELETE_FOTO_URL = "{{ route('pengiriman.delete-foto', ['id' => ':id']) }}";
    const GET_FOTOS_URL = "{{ route('pengiriman.fotos', ['id' => ':id']) }}";

    // excel export
    document.getElementById('btnExportExcel')?.addEventListener('click', function() {

        const checked = document.querySelectorAll('.row-check:checked');

        if (checked.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Pilih minimal 1 pengiriman!'
            });
            return;
        }

        const ids = [];

        checked.forEach(cb => {
            ids.push(cb.value);
        });

        Swal.fire({
            title: 'Export Excel?',
            text: ids.length + ' data akan diexport',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya Export',
            cancelButtonText: 'Batal'
        }).then((result) => {

            if (result.isConfirmed) {

                const form = document.createElement('form');

                form.method = 'POST';
                form.action = EXPORT_EXCEL_URL;

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = document.querySelector('meta[name="csrf-token"]').content;

                form.appendChild(csrf);

                ids.forEach(id => {

                    const input = document.createElement('input');

                    input.type = 'hidden';
                    input.name = 'pengiriman_ids[]';
                    input.value = id;

                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            }
        });
    });

    // ==================== CHECKBOX LOGIC ====================
    // function updateGenerateButton() {
    //     const checked = document.querySelectorAll('.row-check:checked');
    //     const btn = document.getElementById('btnGenerateInvoice');
    //     btn.disabled = checked.length === 0;
    // }

    function updateGenerateButton() {
        const checked = document.querySelectorAll('.row-check:checked');

        const btnInvoice = document.getElementById('btnGenerateInvoice');
        const btnExcel = document.getElementById('btnExportExcel');

        btnInvoice.disabled = checked.length === 0;
        btnExcel.disabled = checked.length === 0;
    }

    document.getElementById('selectAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.row-check');
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateGenerateButton();
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('row-check')) {
            const allCheckboxes = document.querySelectorAll('.row-check');
            const allChecked = document.querySelectorAll('.row-check:checked');
            document.getElementById('selectAll').checked = allCheckboxes.length > 0 && allCheckboxes.length ===
                allChecked.length;
            updateGenerateButton();
        }
    });

    // ==================== GENERATE INVOICE ====================
    document.getElementById('btnGenerateInvoice')?.addEventListener('click', function() {
        const checked = document.querySelectorAll('.row-check:checked');

        if (checked.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Pilih minimal 1 pengiriman!'
            });
            return;
        }

        const ids = [];
        let ptIds = new Set();
        let noFotoList = [];

        checked.forEach(cb => {
            ids.push(cb.value);
            ptIds.add(cb.dataset.ptId);
            if (cb.dataset.hasFoto === '0') {
                noFotoList.push(cb.value);
            }
        });

        if (noFotoList.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Surat Jalan Belum Upload',
                html: 'Pengiriman ID: <strong>' + noFotoList.join(', ') +
                    '</strong><br>belum upload foto surat jalan!'
            });
            return;
        }

        if (ptIds.size > 1) {
            Swal.fire({
                icon: 'error',
                title: 'PT Berbeda',
                text: 'Semua pengiriman harus dari PT yang sama untuk 1 invoice!'
            });
            return;
        }

        Swal.fire({
            title: 'Generate Invoice PDF?',
            text: ids.length + ' pengiriman akan digabung menjadi 1 invoice.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Generate!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                generateInvoicePdf(ids);
            }
        });
    });

    function generateInvoicePdf(ids) {
        Swal.fire({
            title: 'Memproses Invoice...',
            text: 'Mohon tunggu, PDF sedang digenerate',
            icon: 'info',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = GENERATE_INVOICE_URL;
        form.target = '_blank';

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = document.querySelector('meta[name="csrf-token"]').content;
        form.appendChild(csrf);

        ids.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'pengiriman_ids[]';
            input.value = id;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);

        setTimeout(() => {
            Swal.close();
            Swal.fire({
                icon: 'success',
                title: 'Selesai!',
                text: 'PDF invoice telah digenerate di tab baru.',
                confirmButtonText: 'OK'
            });
        }, 2000);
    }

    // ==================== EDIT DATA ====================
    function editData(data) {
        editPengirimanId = data.id;

        document.getElementById('edit_pt_id').value = data.pt_id;
        document.getElementById('edit_armada_id').value = data.armada_id;
        document.getElementById('edit_driver_id').value = data.driver_id;
        document.getElementById('edit_tanggal_ambil').value = data.tanggal_ambil;
        document.getElementById('edit_rute_from').value = data.rute_from;
        document.getElementById('edit_rute_to').value = data.rute_to;
        document.getElementById('edit_harga_pabrik').value = data.harga_pabrik;
        document.getElementById('edit_harga_armada').value = data.harga_armada;

        const route = "{{ route('pengiriman.update', ['id' => ':id']) }}".replace(':id', data.id);
        document.getElementById('formEditPengiriman').action = route;

        const modal = new bootstrap.Modal(document.getElementById('modalEditPengiriman'));
        modal.show();
    }

    // ==================== CONFIRM DELETE ====================
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data pengiriman ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const route = "{{ route('pengiriman.destroy', ['id' => ':id']) }}".replace(':id', id);

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = route;

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // ==================== SET UPLOAD FOTO ID ====================
    function setUploadFotoId(id, count) {
        uploadFotoId = id;

        document.getElementById('fotoPengiriman').value = '';
        document.getElementById('filePreview').innerHTML = '';

        const route = "{{ route('pengiriman.upload-foto', ['id' => ':id']) }}".replace(':id', id);
        document.getElementById('formUploadFoto').action = route;

        document.querySelector('#modalUploadFoto .modal-title').textContent = 'Upload Surat Jalan - ID: ' + id +
            ' (Foto: ' + count + ')';
    }

    // ==================== FILE PREVIEW ====================
    function setupFilePreview(inputId, previewId) {
        document.getElementById(inputId)?.addEventListener('change', function(e) {
            const files = e.target.files;
            const preview = document.getElementById(previewId);
            preview.innerHTML = '';

            if (files.length === 0) {
                preview.innerHTML = '<p class="text-muted">Belum ada file dipilih</p>';
                return;
            }

            let html = '<div class="mt-3"><h6>Preview File:</h6><ul class="list-group">';
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const size = (file.size / 1024 / 1024).toFixed(2);
                html += '<li class="list-group-item d-flex justify-content-between align-items-center">' +
                    '<span>' + file.name + '</span>' +
                    '<span class="badge bg-primary rounded-pill">' + size + ' MB</span>' +
                    '</li>';
            }
            html += '</ul></div>';
            preview.innerHTML = html;
        });
    }

    setupFilePreview('fotoPengiriman', 'filePreview');
    setupFilePreview('tambahFoto', 'tambahFilePreview');

    // ==================== UPLOAD FORM SUBMIT ====================
    document.getElementById('formUploadFoto')?.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const route = this.action;

        $.ajax({
            url: route,
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                const modal = bootstrap.Modal.getInstance(document.getElementById(
                    'modalUploadFoto'));
                modal.hide();

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message || 'Foto berhasil diupload',
                    confirmButtonText: 'OK'
                }).then(() => location.reload());
            },
            error: function(xhr) {
                let errors = '';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    Object.keys(xhr.responseJSON.errors).forEach(key => {
                        errors += xhr.responseJSON.errors[key].join('<br>') + '<br>';
                    });
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    html: errors || 'Terjadi kesalahan saat upload',
                });
            }
        });
    });

    function openFotoModal(pengirimanId) {
        document.getElementById('fotoPengirimanId').textContent = pengirimanId;
        document.getElementById('fotoGalleryContainer').innerHTML =
            '<div class="text-center text-muted py-4">' +
            '<div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>' +
            '<p class="mt-2">Memuat foto...</p></div>';

        const modal = new bootstrap.Modal(document.getElementById('modalLihatFoto'));
        modal.show();

        const url = GET_FOTOS_URL.replace(':id', pengirimanId);

        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    let html = '<div class="row">';
                    response.data.forEach(function(foto) {
                        html += '<div class="col-md-6 mb-3">' +
                            '<div class="foto-gallery-item">' +
                            '<button type="button" class="btn btn-sm btn-danger btn-delete-foto" ' +
                            'onclick="deleteFoto(' + foto.id + ', ' + pengirimanId + ')">' +
                            '<i class="bi bi-trash"></i></button>' +
                            '<img src="' + foto.url + '" alt="Surat Jalan">' +
                            '<div class="foto-info">' + foto.file_path + '</div>' +
                            '</div></div>';
                    });
                    html += '</div>';
                    document.getElementById('fotoGalleryContainer').innerHTML = html;
                } else {
                    document.getElementById('fotoGalleryContainer').innerHTML =
                        '<div class="text-center text-muted py-4"><p>Tidak ada foto</p></div>';
                }
            },
            error: function() {
                document.getElementById('fotoGalleryContainer').innerHTML =
                    '<div class="text-center text-danger py-4"><p>Gagal memuat foto</p></div>';
            }
        });
    }

    function deleteFoto(fotoId, pengirimanId) {
        Swal.fire({
            title: 'Hapus Foto?',
            text: 'Foto ini akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const url = DELETE_FOTO_URL.replace(':id', fotoId);

                $.ajax({
                    url: url,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                        openFotoModal(pengirimanId);
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: xhr.responseJSON?.message || 'Gagal menghapus foto'
                        });
                    }
                });
            }
        });
    }

    // ==================== EDIT FORM SUBMIT ====================
    document.getElementById('formEditPengiriman')?.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const route = this.action;

        $.ajax({
            url: route,
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-HTTP-Method-Override': 'PATCH'
            },
            success: function(response) {
                const modal = bootstrap.Modal.getInstance(document.getElementById(
                    'modalEditPengiriman'));
                modal.hide();

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message || 'Data pengiriman berhasil diupdate',
                    confirmButtonText: 'OK'
                }).then(() => location.reload());
            },
            error: function(xhr) {
                let errors = '';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    Object.keys(xhr.responseJSON.errors).forEach(key => {
                        errors += xhr.responseJSON.errors[key].join('<br>') + '<br>';
                    });
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errors = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    html: errors || 'Terjadi kesalahan saat update',
                });
            }
        });
    });

    // ==================== FORM TAMBAH SUBMIT ====================
    document.getElementById('formTambahPengiriman')?.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const route = this.action;

        $.ajax({
            url: route,
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                const modal = bootstrap.Modal.getInstance(document.getElementById(
                    'modalTambahPengiriman'));
                modal.hide();

                document.getElementById('formTambahPengiriman').reset();
                document.getElementById('tambahFilePreview').innerHTML = '';

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message || 'Pengiriman berhasil ditambahkan',
                    confirmButtonText: 'OK'
                }).then(() => location.reload());
            },
            error: function(xhr) {
                let errors = '';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    Object.keys(xhr.responseJSON.errors).forEach(key => {
                        errors += xhr.responseJSON.errors[key].join('<br>') + '<br>';
                    });
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Error!',
                    html: errors || 'Terjadi kesalahan saat menambah pengiriman',
                });
            }
        });
    });

    function applyFilter() {
        const ptId = document.getElementById('filterPt').value;
        const perPage = document.getElementById('perPage').value;

        const params = new URLSearchParams();

        if (ptId) {
            params.append('pt_id', ptId);
        }

        if (perPage) {
            params.append('per_page', perPage);
        }

        window.location.href = `{{ route('pengiriman.index') }}?${params.toString()}`;
    }

    document.getElementById('filterPt')?.addEventListener('change', applyFilter);

    document.getElementById('perPage')?.addEventListener('change', applyFilter);
</script>
