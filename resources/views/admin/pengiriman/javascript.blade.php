<script>
    let editPengirimanId = null;
    let uploadFotoId = null;

    const GENERATE_INVOICE_URL = "{{ route('invoice.generate-pdf') }}";
    const EXPORT_EXCEL_URL = "{{ route('export.pengiriman-excel') }}";
    const DELETE_FOTO_URL = "{{ route('pengiriman.delete-foto', ['id' => ':id']) }}";
    const GET_FOTOS_URL = "{{ route('pengiriman.fotos', ['id' => ':id']) }}";
    const FILTER_URL = "{{ route('pengiriman.index') }}";

    // ==================== RUPIAH FORMATTER & CLEANER ====================
    // Format number to Rupiah format with thousands separator (dot)
    // Example: 1000000 → 1.000.000
    function formatRupiah(value) {
        // Remove all non-digit characters
        const clean = value.toString().replace(/\D/g, '');

        if (clean === '' || clean === '0') return '0';

        // Use Intl.NumberFormat to format with Indonesian locale
        return new Intl.NumberFormat('id-ID', {
            style: 'decimal',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(parseInt(clean));
    }

    // Clean Rupiah format to pure number
    // Example: 1.000.000 → 1000000
    function cleanRupiah(value) {
        return value.toString().replace(/\D/g, '') || '0';
    }

    document.addEventListener('DOMContentLoaded', function() {
        // console.log('DOMContentLoaded fired');

        // // ==================== ELEMENT EXISTENCE CHECK ====================
        // console.log('filterPt exists?', !!document.getElementById('filterPt'));
        // console.log('perPage exists?', !!document.getElementById('perPage'));
        // console.log('sortOrder exists?', !!document.getElementById('sortOrder'));
        // console.log('tableBody exists?', !!document.getElementById('tableBody'));
        // console.log('paginationWrapper exists?', !!document.getElementById('paginationWrapper'));
        // console.log('paginationInfo exists?', !!document.getElementById('paginationInfo'));

        // ==================== AJAX FILTER ====================
        function applyFilter(page) {
            page = page || 1;

            const ptId = document.getElementById('filterPt')?.value || '';
            const perPage = document.getElementById('perPage')?.value || '10';
            const sortOrder = document.getElementById('sortOrder')?.value || 'latest';

            // console.log('applyFilter called', { ptId, perPage, sortOrder, page });

            $.ajax({
                url: FILTER_URL,
                method: 'GET',
                data: {
                    pt_id: ptId,
                    per_page: perPage,
                    sort: sortOrder,
                    page: page
                },
                beforeSend: function() {
                    // console.log('AJAX request sending...');
                },
                success: function(response) {
                    // console.log('AJAX success', response);

                    $('#tableBody').html(response.table);
                    $('#paginationWrapper').html(response.pagination);
                    $('#paginationInfo').text(response.info);

                    // Reset selectAll checkbox
                    const selectAll = document.getElementById('selectAll');
                    if (selectAll) selectAll.checked = false;
                    updateGenerateButton();
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error', {
                        status,
                        error,
                        xhr
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memfilter data'
                    });
                }
            });
        }

        // Expose to global for pagination click handler
        window.applyFilter = applyFilter;

        // Event listeners for filter controls
        document.getElementById('filterPt')?.addEventListener('change', function() {
            // console.log('FILTER PT CHANGED');
            applyFilter();
        });

        document.getElementById('perPage')?.addEventListener('change', function() {
            // console.log('PERPAGE CHANGED');
            applyFilter();
        });

        document.getElementById('sortOrder')?.addEventListener('change', function() {
            // console.log('SORT CHANGED');
            applyFilter();
        });

        // Pagination click delegation
        $(document).on('click', '#paginationWrapper .pagination a', function(e) {
            e.preventDefault();
            const url = new URL($(this).attr('href'));
            const page = url.searchParams.get('page');
            // console.log('Pagination clicked, page:', page);
            applyFilter(page);
        });

        // ==================== EXCEL EXPORT ====================
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
            checked.forEach(cb => ids.push(cb.value));

            Swal.fire({
                title: 'Export Excel?',
                text: ids.length + ' data akan diexport',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya Export',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = EXPORT_EXCEL_URL;

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = document.querySelector('meta[name="csrf-token"]').content;
                    form.appendChild(csrf);

                    ids.forEach(function(id) {
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
        function updateGenerateButton() {
            const checked = document.querySelectorAll('.row-check:checked');
            const btnInvoice = document.getElementById('btnGenerateInvoice');
            const btnExcel = document.getElementById('btnExportExcel');

            if (btnInvoice) btnInvoice.disabled = checked.length === 0;
            if (btnExcel) btnExcel.disabled = checked.length === 0;
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
                const selectAll = document.getElementById('selectAll');
                if (selectAll) {
                    selectAll.checked = allCheckboxes.length > 0 && allCheckboxes.length === allChecked
                        .length;
                }
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
            const ptIds = new Set();
            const noFotoList = [];

            checked.forEach(function(cb) {
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
                title: 'Cetak Invoice?',
                text: ids.length + ' pengiriman akan dicetak menjadi invoice.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Cetak',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#28a745',
            }).then(function(result) {
                if (result.isConfirmed) {
                    generateInvoicePdf(ids);
                }
            });
        });

        function generateInvoicePdf(ids) {
            Swal.fire({
                title: 'Memproses Invoice...',
                text: 'Mohon tunggu, PDF sedang dicetak',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: function() {
                    Swal.showLoading();
                }
            });

            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            ids.forEach(function(id) {
                formData.append('pengiriman_ids[]', id);
            });

            $.ajax({
                url: GENERATE_INVOICE_URL,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(blob, status, xhr) {
                    Swal.close();

                    const contentType = xhr.getResponseHeader('Content-Type');
                    if (contentType && contentType.includes('application/json')) {
                        const reader = new FileReader();
                        reader.onload = function() {
                            const response = JSON.parse(reader.result);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message || 'Terjadi kesalahan'
                            });
                        };
                        reader.readAsText(blob);
                        return;
                    }

                    const fileURL = window.URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = fileURL;
                    link.download = 'invoice_' + Date.now() + '.pdf';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    setTimeout(function() {
                        window.URL.revokeObjectURL(fileURL);
                        Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Invoice berhasil dicetak'
                            })
                            .then(function() {
                                location.reload();
                            });
                    }, 500);
                },
                error: function(xhr) {
                    Swal.close();
                    let message = 'Terjadi kesalahan';
                    if (xhr.responseJSON?.message) message = xhr.responseJSON.message;
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: message
                    });
                }
            });
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
                    html +=
                        '<li class="list-group-item d-flex justify-content-between align-items-center">' +
                        '<span>' + file.name + '</span>' +
                        '<span class="badge bg-primary rounded-pill">' + size + ' MB</span></li>';
                }
                html += '</ul></div>';
                preview.innerHTML = html;
            });
        }

        setupFilePreview('fotoPengiriman', 'filePreview');
        setupFilePreview('tambahFoto', 'tambahFilePreview');

        // ==================== HARGA INPUT FORMATTER ====================
        // Setup all harga inputs to auto-format as user types
        document.querySelectorAll('.harga-input').forEach(function(input) {
            input.addEventListener('input', function(e) {
                const cursorPos = this.selectionStart; // Save cursor position
                const oldValue = this.value;
                const formatted = formatRupiah(this.value);
                this.value = formatted;

                // Restore cursor position approximately
                const diff = formatted.length - oldValue.length;
                this.setSelectionRange(cursorPos + diff, cursorPos + diff);
            });

            // Format on paste
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                this.value = formatRupiah(pastedText);
            });
        });

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
                        })
                        .then(function() {
                            location.reload();
                        });
                },
                error: function(xhr) {
                    let errors = '';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        Object.keys(xhr.responseJSON.errors).forEach(function(key) {
                            errors += xhr.responseJSON.errors[key].join('<br>') +
                                '<br>';
                        });
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        html: errors || 'Terjadi kesalahan saat upload'
                    });
                }
            });
        });

        // ==================== EDIT FORM SUBMIT ====================
        document.getElementById('formEditPengiriman')?.addEventListener('submit', function(e) {
            e.preventDefault();

            // Clean harga values before submit
            const hargaPabrik = document.getElementById('edit_harga_pabrik');
            const hargaArmada = document.getElementById('edit_harga_armada');

            if (hargaPabrik) hargaPabrik.value = cleanRupiah(hargaPabrik.value);
            if (hargaArmada) hargaArmada.value = cleanRupiah(hargaArmada.value);

            console.log('Form Edit Submit - Cleaned values:', {
                harga_pabrik: hargaPabrik?.value,
                harga_armada: hargaArmada?.value
            });

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
                            text: response.message ||
                                'Data pengiriman berhasil diupdate',
                            confirmButtonText: 'OK'
                        })
                        .then(function() {
                            location.reload();
                        });
                },
                error: function(xhr) {
                    let errors = '';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        Object.keys(xhr.responseJSON.errors).forEach(function(key) {
                            errors += xhr.responseJSON.errors[key].join('<br>') +
                                '<br>';
                        });
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errors = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        html: errors || 'Terjadi kesalahan saat update'
                    });
                }
            });
        });

        // ==================== FORM TAMBAH SUBMIT ====================
        document.getElementById('formTambahPengiriman')?.addEventListener('submit', function(e) {
            e.preventDefault();

            // Clean harga values before submit
            const hargaPabrik = document.getElementById('tambah_harga_pabrik');
            const hargaArmada = document.getElementById('tambah_harga_armada');

            if (hargaPabrik) hargaPabrik.value = cleanRupiah(hargaPabrik.value);
            if (hargaArmada) hargaArmada.value = cleanRupiah(hargaArmada.value);

            console.log('Form Tambah Submit - Cleaned values:', {
                harga_pabrik: hargaPabrik?.value,
                harga_armada: hargaArmada?.value
            });

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
                        })
                        .then(function() {
                            location.reload();
                        });
                },
                error: function(xhr) {
                    let errors = '';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        Object.keys(xhr.responseJSON.errors).forEach(function(key) {
                            errors += xhr.responseJSON.errors[key].join('<br>') +
                                '<br>';
                        });
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Error!',
                        html: errors || 'Terjadi kesalahan saat menambah pengiriman'
                    });
                }
            });
        });

    }); // end DOMContentLoaded

    // ==================== GLOBAL FUNCTIONS (called from onclick in HTML) ====================

    function editData(data) {
        editPengirimanId = data.id;

        document.getElementById('edit_pt_id').value = data.pt_id;
        document.getElementById('edit_armada_id').value = data.armada_id;
        document.getElementById('edit_driver_id').value = data.driver_id;
        document.getElementById('edit_tanggal_ambil').value = data.tanggal_ambil;
        document.getElementById('edit_rute_from').value = data.rute_from;
        document.getElementById('edit_rute_to').value = data.rute_to;

        // Format harga values when loading into edit modal
        document.getElementById('edit_harga_pabrik').value = formatRupiah(data.harga_pabrik);
        document.getElementById('edit_harga_armada').value = formatRupiah(data.harga_armada);
        document.getElementById('edit_keterangan').value = data.keterangan;

        console.log('Edit Modal Loaded - Formatted values:', {
            harga_pabrik: formatRupiah(data.harga_pabrik),
            harga_armada: formatRupiah(data.harga_armada)
        });

        const route = "{{ route('pengiriman.update', ['id' => ':id']) }}".replace(':id', data.id);
        document.getElementById('formEditPengiriman').action = route;

        const modal = new bootstrap.Modal(document.getElementById('modalEditPengiriman'));
        modal.show();
    }

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
        }).then(function(result) {
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

    function setUploadFotoId(id, count) {
        uploadFotoId = id;
        document.getElementById('fotoPengiriman').value = '';
        document.getElementById('filePreview').innerHTML = '';

        const route = "{{ route('pengiriman.upload-foto', ['id' => ':id']) }}".replace(':id', id);
        document.getElementById('formUploadFoto').action = route;

        document.querySelector('#modalUploadFoto .modal-title').textContent = 'Upload Surat Jalan - ID: ' + id +
            ' (Foto: ' + count + ')';
    }

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
        }).then(function(result) {
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
</script>
