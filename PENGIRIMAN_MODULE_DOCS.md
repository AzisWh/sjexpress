# 📦 Module Manajemen Pengiriman - Dokumentasi Lengkap

## 📋 Daftar Isi
1. [Overview](#overview)
2. [Fitur Utama](#fitur-utama)
3. [Struktur File](#struktur-file)
4. [Endpoint API](#endpoint-api)
5. [Database Schema](#database-schema)
6. [Panduan Penggunaan](#panduan-penggunaan)
7. [Troubleshooting](#troubleshooting)

---

## Overview

Module manajemen pengiriman adalah sistem lengkap untuk mengelola pengiriman barang, upload surat jalan (foto), dan generate invoice. Sistem ini menggunakan Laravel dengan Blade templating, AJAX, dan SweetAlert untuk UX yang baik.

### Tech Stack
- **Backend**: Laravel 11 (PHP 8.5)
- **Frontend**: Bootstrap 5, jQuery, SweetAlert2
- **Database**: MySQL
- **Storage**: Local disk (public/SuratJalan)

---

## Fitur Utama

### 1. 📊 Tampilan Data Pengiriman
- **Tabel interaktif** dengan informasi lengkap
- **Kolom**: PT, Rute, Driver, Armada, Tanggal, Foto, Status Invoice
- **Badge color coding**:
  - 🟢 Hijau: Foto sudah tersedia
  - 🔴 Merah: Belum ada foto
  - 🔵 Biru: Sudah ada invoice
  - 🟡 Kuning: Belum ada invoice

### 2. ➕ Tambah Pengiriman
- Modal form dengan validasi
- Field wajib diisi:
  - PT (dropdown)
  - Armada (dropdown)
  - Driver (dropdown)
  - Tanggal Ambil (date picker)
  - Rute From & To (text input)
  - Harga Pabrik & Harga Armada (numeric)
- Validasi otomatis sebelum submit
- Feedback dengan SweetAlert

### 3. ✏️ Edit Pengiriman
- Modal edit dengan data prefilled
- Field yang bisa diedit sama seperti tambah
- AJAX submit tanpa refresh halaman
- Validation error display

### 4. 📤 Upload Surat Jalan
- Multiple file upload
- Format: JPG, PNG (supported image types)
- Max size: 2MB per file
- File preview sebelum upload
- Nama file otomatis: `surat-jalan-{id}-{urut}.ext`
- Storage location: `storage/app/public/SuratJalan/`

### 5. 📄 Generate Invoice
- **Persyaratan**: Minimal 1 foto harus sudah diupload
- **Validasi otomatis**: Jika belum ada foto → warning SweetAlert
- **Format Invoice**:
  - Nomor Invoice: `INV-YYYYMMDDHHmmss`
  - Tanggal: Current timestamp
  - Nominal: Ambil dari harga_pabrik
  - PT: Dari pengiriman
- **DB Transaction**: Pastikan data konsisten
- **Relasi**: Invoice terhubung ke Pengiriman (invoice_id)

### 6. 🗑️ Hapus Pengiriman
- Confirmation dialog SweetAlert
- Hapus data pengiriman & semua foto terkait
- Otomatis delete file dari storage
- Feedback success/error

---

## Struktur File

### Controllers
```
app/Http/Controllers/Admin/PengirimanController.php
├── index()              → GET /admin-pengiriman
├── store()              → POST /admin-pengiriman/store
├── update($id)          → PATCH /admin-pengiriman/update/{id}
├── uploadFoto($id)      → POST /admin-pengiriman/upload-foto/{id}
├── generateInvoice($id) → GET /admin-pengiriman/generateinvoice/{id}
└── destroy($id)         → DELETE /admin-pengiriman/delete/{id}
```

### Views
```
resources/views/admin/pengiriman/
├── index.blade.php       → Main view dengan table & modal
└── javascript.blade.php  → Inline JavaScript (jQuery + AJAX)
```

### Models
```
app/Models/
├── PengirimanModel.php      → Model utama
├── InvoiceModel.php         → Model invoice
├── FotoPengirimanModel.php  → Model foto
└── [relasi: PT, Armada, Driver]
```

### Routes
```
routes/web.php
├── GET  /admin-pengiriman                    → index
├── POST /admin-pengiriman/store             → store
├── PATCH /admin-pengiriman/update/{id}      → update
├── POST /admin-pengiriman/upload-foto/{id}  → uploadFoto
├── GET  /admin-pengiriman/generateinvoice/{id} → generateInvoice
└── DELETE /admin-pengiriman/delete/{id}     → destroy
```

### Storage
```
storage/app/public/SuratJalan/
└── surat-jalan-{id}-{urut}.jpg/png
```

---

## Endpoint API

### 1. Get Data Pengiriman
```http
GET /admin-pengiriman
Response: View dengan data pengiriman + related data
```

### 2. Tambah Pengiriman
```http
POST /admin-pengiriman/store
Content-Type: application/x-www-form-urlencoded

Body:
pt_id=1
armada_id=2
driver_id=3
tanggal_ambil=2026-05-06
rute_from=Jakarta
rute_to=Bandung
harga_pabrik=500000
harga_armada=100000

Response: Redirect + SweetAlert notification
```

### 3. Upload Foto
```http
POST /admin-pengiriman/upload-foto/{id}
Content-Type: multipart/form-data

Body:
foto[]=<file1.jpg>
foto[]=<file2.jpg>
...

Response: JSON success/error + SweetAlert notification
```

### 4. Generate Invoice
```http
GET /admin-pengiriman/generateinvoice/{id}

Validasi:
- Harus ada minimal 1 foto
- Hanya bisa jika belum ada invoice

Response: Redirect + SweetAlert notification
Database: Create invoice + update pengiriman.invoice_id
```

### 5. Update Pengiriman
```http
PATCH /admin-pengiriman/update/{id}
Content-Type: application/x-www-form-urlencoded

Body: [sama seperti store, semua field opsional]

Response: JSON success/error via AJAX
```

### 6. Hapus Pengiriman
```http
DELETE /admin-pengiriman/delete/{id}

Side Effect:
- Delete semua foto dari storage
- Delete data pengiriman dari DB

Response: Redirect + SweetAlert notification
```

---

## Database Schema

### pengiriman_table
```sql
CREATE TABLE pengiriman_table (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    pt_id BIGINT,
    armada_id BIGINT,
    driver_id BIGINT,
    invoice_id BIGINT (nullable),
    tanggal_ambil DATE,
    rute_from VARCHAR(255),
    rute_to VARCHAR(255),
    harga_pabrik DECIMAL(10, 2),
    harga_armada DECIMAL(10, 2),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (pt_id) REFERENCES pt_table(id),
    FOREIGN KEY (armada_id) REFERENCES armada_table(id),
    FOREIGN KEY (driver_id) REFERENCES driver_table(id),
    FOREIGN KEY (invoice_id) REFERENCES invoice_table(id)
)
```

### foto_pengiriman_table
```sql
CREATE TABLE foto_pengiriman_table (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    pengiriman_id BIGINT,
    file_path VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (pengiriman_id) REFERENCES pengiriman_table(id) ON DELETE CASCADE
)
```

### invoice_table
```sql
CREATE TABLE invoice_table (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nomor_invoice VARCHAR(50),
    tanggal_invoice TIMESTAMP,
    pt_id BIGINT,
    nominal_invoice DECIMAL(12, 2),
    nominal_cair DECIMAL(12, 2) (nullable),
    status VARCHAR(50) (nullable),
    tanggal_cair TIMESTAMP (nullable),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (pt_id) REFERENCES pt_table(id)
)
```

---

## Panduan Penggunaan

### Skenario 1: Menambah Pengiriman Baru
1. Klik tombol "✙ Tambah Pengiriman"
2. Modal form terbuka
3. Isi semua field (PT, Armada, Driver, Tanggal, Rute, Harga)
4. Klik "Simpan"
5. Sistem validasi → Jika error, tampil pesan error
6. Jika success → SweetAlert success → Halaman reload

### Skenario 2: Upload Surat Jalan
1. Di tabel, cari baris pengiriman yang ingin diupload
2. Klik tombol "📤 Upload"
3. Modal upload terbuka
4. Pilih file (bisa multiple)
5. Preview muncul menunjukkan file yang dipilih
6. Klik "Upload"
7. File tersimpan → Modal tertutup → Halaman reload

### Skenario 3: Generate Invoice
**Cara 1: Klik tombol Invoice di tabel**
1. Pastikan ada minimal 1 foto (badge hijau)
2. Klik tombol "📄 Invoice"
3. Konfirmasi dialog → "Ya, Generate!"
4. Sistem buat invoice → redirect & SweetAlert success
5. Pengiriman sekarang linked ke invoice

**Cara 2: Direct route (jika foto kosong)**
- Tombol disabled (abu-abu)
- Hover → tooltip "Foto harus diupload dulu"

### Skenario 4: Edit Pengiriman
1. Klik tombol "✏️ Edit" pada baris pengiriman
2. Modal edit terbuka dengan data prefilled
3. Ubah field yang diperlukan
4. Klik "Update"
5. AJAX submit → Validasi → Success → Reload

### Skenario 5: Hapus Pengiriman
1. Klik tombol "🗑️ Hapus"
2. Konfirmasi dialog muncul
3. Klik "Ya, Hapus!"
4. Sistem hapus data + semua foto
5. SweetAlert success → Redirect

---

## Validasi Rules

### Tambah/Edit Pengiriman
```php
[
    'pt_id' => 'required',
    'armada_id' => 'required',
    'driver_id' => 'required',
    'tanggal_ambil' => 'required|date',
    'rute_from' => 'required|string',
    'rute_to' => 'required|string',
    'harga_pabrik' => 'required|numeric',
    'harga_armada' => 'required|numeric',
]
```

### Upload Foto
```php
[
    'foto.*' => 'required|image|max:2048',  // 2MB max
]
```

### Generate Invoice
```
- Minimal 1 foto tersimpan
- Belum ada invoice sebelumnya
- Data pengiriman harus valid
```

---

## JavaScript Functions

### editData(data)
```javascript
// Input: Objek pengiriman (dari data-pengiriman)
// Effect: Populate form edit + show modal

editData({
    id: 1,
    pt_id: 1,
    armada_id: 2,
    driver_id: 3,
    tanggal_ambil: "2026-05-06",
    rute_from: "Jakarta",
    rute_to: "Bandung",
    harga_pabrik: 500000,
    harga_armada: 100000
})
```

### confirmDelete(id)
```javascript
// Input: ID pengiriman
// Effect: Konfirmasi SweetAlert → Submit DELETE form

confirmDelete(1)
```

### setUploadFotoId(id, count)
```javascript
// Input: ID pengiriman + jumlah foto saat ini
// Effect: Set upload form action + show modal

setUploadFotoId(1, 3)  // ID 1, sudah ada 3 foto
```

### generateInvoice(id, totalFoto)
```javascript
// Input: ID pengiriman + total foto
// Effect: Validasi foto → Konfirmasi → Redirect

generateInvoice(1, 2)  // ID 1, ada 2 foto
```

---

## Troubleshooting

### ❌ Problem: Upload foto gagal dengan error 500
**Solution**:
```bash
# Check folder permission
ls -la storage/app/public/

# Set permission
chmod -R 755 storage/app/public/
chmod -R 755 storage/app/public/SuratJalan/

# Pastikan symlink sudah di-setup
php artisan storage:link
```

### ❌ Problem: Generate invoice tidak bisa, tapi foto sudah ada
**Solution**:
```bash
# Check di database, pastikan foto tersimpan
SELECT COUNT(*) FROM foto_pengiriman_table WHERE pengiriman_id = 1;

# Jika 0, user perlu re-upload
```

### ❌ Problem: CSRF token mismatch pada AJAX
**Solution**: Pastikan di layout main ada:
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

### ❌ Problem: Modal tidak terbuka
**Solution**: Pastikan Bootstrap JS sudah loaded:
```html
<!-- Di akhir body -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
```

### ❌ Problem: SweetAlert tidak muncul
**Solution**: Check CDN tersedia:
```html
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
```

### ❌ Problem: File storage tidak bisa diakses dari browser
**Solution**:
```bash
# Setup symlink storage
php artisan storage:link

# Check di public folder ada symlink
ls -la public/storage

# File harus di akses: /storage/SuratJalan/filename.jpg
```

### ❌ Problem: Form submit error "Validation error"
**Solution**: Check error message:
1. Buka browser DevTools (F12)
2. Lihat Console tab
3. Klik request yang gagal → lihat response
4. Check validation rules & field names

---

## Tips & Tricks

### 🎯 Performance Optimization
```php
// Gunakan select() di controller untuk kolom tertentu
$pengiriman = PengirimanModel::select(['id', 'pt_id', 'rute_from', 'rute_to'])
    ->with(['pt' => fn($q) => $q->select('id', 'nama_pt')])
    ->paginate(20);
```

### 🎯 Custom Invoice Numbering
```php
// Di controller generateInvoice(), ubah format:
'nomor_invoice' => 'INV-' . date('Ym') . '-' . str_pad($id, 5, '0', STR_PAD_LEFT),
// Hasil: INV-202605-00001
```

### 🎯 Limit File Size
```php
// Di controller uploadFoto(), validate bisa di update:
'foto.*' => 'required|image|max:1024',  // 1MB saja
```

### 🎯 Add Pagination
```php
// Di index method, ubah jadi paginated:
$data = PengirimanModel::with(['pt', 'armada', 'driver', 'fotos'])
    ->paginate(15);
```

---

## Kontak & Support

Untuk pertanyaan teknis atau bug report, silakan:
1. Check file ini dulu
2. Lihat error message di browser console
3. Check database query di Laravel debugbar (jika enable)
4. Report dengan screenshoot + error message

---

**Last Updated**: May 6, 2026
**Version**: 1.0
**Status**: ✅ Production Ready
