@forelse($data as $key => $item)
    @php
        $hasInvoice = $item->invoiceDetails()->exists();
    @endphp
    <tr data-id="{{ $item->id }}" data-pt-id="{{ $item->pt_id }}"
        data-has-foto="{{ $item->fotos->count() > 0 ? '1' : '0' }}">
        <td>
            <input type="checkbox" class="form-check-input row-check" value="{{ $item->id }}"
                data-pt-id="{{ $item->pt_id }}" data-has-foto="{{ $item->fotos->count() > 0 ? '1' : '0' }}">
        </td>
        <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
        <td><strong>{{ $item->pt->name ?? '-' }}</strong></td>
        <td>{{ $item->rute_from }} - {{ $item->rute_to }}</td>
        <td>{{ $item->driver->name ?? '-' }}</td>
        <td>{{ $item->armada->nama_armada ?? '-' }} -
            {{ $item->armada->plat_nomor ?? '-' }}</td>
        <td>{{ \Carbon\Carbon::parse($item->tanggal_ambil)->format('d/m/Y') }}</td>
        <td>{{ formatRupiah($item->harga_pabrik) }}</td>
        <td>
            @if ($item->fotos->count() > 0)
                <button class="btn btn-sm btn-outline-info" type="button" onclick="openFotoModal({{ $item->id }})">
                    <i class="bi bi-image"></i> {{ $item->fotos->count() }} Foto
                </button>
            @else
                <span class="badge bg-danger"><i class="bi bi-exclamation-circle"></i> Belum Ada</span>
            @endif
        </td>
        <td>{{ $item->keterangan ?? 'Belum ada keterangan' }}</td>
        <td>
            @if ($hasInvoice)
                <span class="badge bg-info"><i class="bi bi-check-circle"></i> Sudah
                    Cetak</span>
            @else
                <span class="badge bg-warning"><i class="bi bi-clock"></i> Belum
                    Cetak</span>
            @endif
        </td>
        <td>
            <div class="action-buttons">
                <button class="btn btn-sm btn-warning" type="button" onclick="editData({{ json_encode($item) }})">
                    <i class="bi bi-pencil"></i> Edit
                </button>
                <button class="btn btn-sm btn-info" type="button" data-bs-toggle="modal"
                    data-bs-target="#modalUploadFoto"
                    onclick="setUploadFotoId({{ $item->id }}, {{ $item->fotos->count() }})">
                    <i class="bi bi-cloud-upload"></i> Upload
                </button>
                @if ($item->fotos->count() > 0)
                    <button class="btn btn-sm btn-outline-primary" type="button"
                        onclick="openFotoModal({{ $item->id }})">
                        <i class="bi bi-eye"></i> Lihat
                    </button>
                @endif
                <button class="btn btn-sm btn-danger" type="button" onclick="confirmDelete({{ $item->id }})">
                    <i class="bi bi-trash"></i> Hapus
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="12" class="text-center text-muted">Belum ada data pengiriman</td>
    </tr>
@endforelse
