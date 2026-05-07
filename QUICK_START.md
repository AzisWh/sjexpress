# 🚀 QUICK START - Manajemen Pengiriman Module

## ✅ Apa yang sudah dibuat?

**Complete Module** untuk mengelola pengiriman barang dengan 3 fitur utama:
1. **CRUD Pengiriman** - Tambah, Edit, Hapus pengiriman
2. **Upload Surat Jalan** - Multiple file upload dengan validasi
3. **Generate Invoice** - Otomatis buat invoice jika sudah ada foto

---

## 📂 File yang Dibuat/Dimodifikasi

### Sudah Exist (Diperbaiki):
- ✅ `app/Http/Controllers/Admin/PengirimanController.php`
  - Fix typo `FotoPengirimanmoDEL` → `FotoPengirimanModel`
  - Fix invoice format `INV-YYYYMMDDHHMMSS`
  - Semua method: index, store, update, uploadFoto, generateInvoice, destroy

### Baru Dibuat:
- ✅ `resources/views/admin/pengiriman/index.blade.php`
  - Table dengan 3 modal (Tambah, Edit, Upload)
  - Badge color coding untuk status
  - Action buttons dengan AJAX
  
- ✅ `resources/views/admin/pengiriman/javascript.blade.php`
  - jQuery AJAX untuk semua form
  - SweetAlert untuk confirmation & feedback
  - File preview sebelum upload

### Existing (Sudah OK):
- ✅ `app/Models/PengirimanModel.php` - Lengkap dengan relasi
- ✅ `app/Models/InvoiceModel.php` - Relasi invoice
- ✅ `app/Models/FotoPengirimanModel.php` - Relasi foto
- ✅ `routes/web.php` - 6 routes untuk pengiriman

### Storage:
- ✅ `storage/app/public/SuratJalan/` - Folder untuk foto

---

## 🎯 Testing Checklist

```bash
# 1. Check controller syntax
php -l app/Http/Controllers/Admin/PengirimanController.php
# Output: No syntax errors detected ✓

# 2. Check view syntax  
php -l resources/views/admin/pengiriman/index.blade.php
# Output: No syntax errors detected ✓

# 3. Run artisan tinker untuk test models
php artisan tinker
> new App\Models\PengirimanModel()
> exit

# 4. Check routes
php artisan route:list | grep pengiriman
```

---

## 🎨 UI Flow

```
┌─────────────────────────────────────┐
│  Pengiriman Index Page              │
├─────────────────────────────────────┤
│ [+ Tambah Pengiriman] Button        │
│                                     │
│ Table (Data Pengiriman)             │
│ ┌──────────────────────────────────┐│
│ │ PT | Rute | Driver | Foto │ ... ││
│ ├──────────────────────────────────┤│
│ │ Row 1   [Edit][Upload][Invoice][Hapus]
│ │ Row 2   [Edit][Upload][Invoice][Hapus]
│ │ Row 3   [Edit][Upload][Invoice][Hapus]
│ └──────────────────────────────────┘│
└─────────────────────────────────────┘

MODAL 1: Tambah Pengiriman
├─ PT (dropdown)
├─ Armada (dropdown)
├─ Driver (dropdown)
├─ Tanggal Ambil (date)
├─ Rute From/To (text)
└─ Harga Pabrik/Armada (number)

MODAL 2: Edit Pengiriman
└─ (Same form, prefilled data)

MODAL 3: Upload Surat Jalan
├─ File input (multiple)
├─ File preview
└─ Submit button
```

---

## 🔄 Feature Breakdown

### Feature 1: Tambah Pengiriman
```
User klik [+ Tambah Pengiriman]
    ↓
Modal form terbuka
    ↓
User isi form + klik Simpan
    ↓
AJAX submit → Validasi → Create DB
    ↓
SweetAlert success → Page reload
```

### Feature 2: Upload Surat Jalan
```
User klik [Upload] button
    ↓
Modal upload terbuka
    ↓
User select file(s) → preview muncul
    ↓
Klik Upload → AJAX submit
    ↓
File tersimpan: storage/app/public/SuratJalan/surat-jalan-{id}-{no}.jpg
DB: foto_pengiriman_table insert
    ↓
SweetAlert success → Page reload
```

### Feature 3: Generate Invoice
```
User klik [Invoice] button
    ↓
Cek: Apakah ada foto?
    ├─ Tidak → SweetAlert warning "Upload foto dulu!"
    └─ Ya → Continue
    ↓
Konfirmasi dialog muncul
    ↓
User klik "Ya, Generate!"
    ↓
Buat record di invoice_table:
├─ nomor_invoice: INV-YYYYMMDDHHmmss
├─ tanggal_invoice: now()
├─ pt_id: dari pengiriman
└─ nominal_invoice: harga_pabrik
    ↓
Update pengiriman.invoice_id
    ↓
SweetAlert success → Page reload
```

---

## 🛠️ Validation & Error Handling

### Add/Edit Pengiriman
```
✓ PT, Armada, Driver: required
✓ Tanggal Ambil: required + date format
✓ Rute From/To: required + string
✓ Harga: required + numeric
✗ Validation error → SweetAlert error message
```

### Upload Foto
```
✓ File: required + image type + max 2MB
✓ Multiple files allowed
✗ File error → SweetAlert error message
```

### Generate Invoice
```
✓ Foto count ≥ 1
✓ Belum ada invoice sebelumnya
✗ Foto kosong → SweetAlert warning
```

---

## 📊 Database Relations

```
PengirimanModel
├─ belongsTo PtModel
├─ belongsTo ArmadaModel
├─ belongsTo DriverModel
├─ belongsTo InvoiceModel (nullable)
└─ hasMany FotoPengirimanModel

FotoPengirimanModel
└─ belongsTo PengirimanModel

InvoiceModel
├─ belongsTo PtModel
└─ hasMany PengirimanModel
```

---

## 🔗 Routes & Endpoints

```
GET    /admin-pengiriman                    → Show page
POST   /admin-pengiriman/store              → Create
PATCH  /admin-pengiriman/update/{id}        → Update
DELETE /admin-pengiriman/delete/{id}        → Delete
POST   /admin-pengiriman/upload-foto/{id}   → Upload foto
GET    /admin-pengiriman/generateinvoice/{id} → Generate invoice
```

---

## 🎯 Next Steps (Optional Enhancements)

- [ ] Add pagination (15 rows per page)
- [ ] Add search/filter by PT atau tanggal
- [ ] Add foto viewer gallery
- [ ] Add invoice PDF download
- [ ] Add export pengiriman ke Excel
- [ ] Add email notification saat generate invoice
- [ ] Add image compression sebelum upload
- [ ] Add payment tracking status invoice

---

## ⚡ Quick Commands

```bash
# Setup storage link
php artisan storage:link

# Run server (development)
php artisan serve

# Run tinker untuk test
php artisan tinker

# Clear cache
php artisan cache:clear
php artisan config:clear

# Check routes
php artisan route:list | grep pengiriman

# View logs
tail -f storage/logs/laravel.log
```

---

## 📞 Troubleshoot Common Issues

| Issue | Solution |
|-------|----------|
| Upload gagal | Check `storage/app/public/SuratJalan` permission |
| Modal tidak buka | Bootstrap JS harus di-load |
| AJAX error | Check CSRF token di `<meta>` tag |
| File tidak bisa diakses | Run `php artisan storage:link` |
| SweetAlert tidak muncul | Check CDN koneksi |
| Form validation error | Check input name di form vs controller |

---

## 📝 File References

- **Controller**: `app/Http/Controllers/Admin/PengirimanController.php`
- **View**: `resources/views/admin/pengiriman/index.blade.php`
- **JavaScript**: `resources/views/admin/pengiriman/javascript.blade.php`
- **Full Docs**: `PENGIRIMAN_MODULE_DOCS.md`
- **Models**: `app/Models/[Pengiriman|Invoice|FotoPengiriman]Model.php`
- **Routes**: `routes/web.php` (lines: admin-pengiriman prefix)

---

**Status**: ✅ Ready to Use
**Last Updated**: May 6, 2026
**Version**: 1.0
