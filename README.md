# NEXA Group Finance — Production Final R2

Aplikasi pusat keuangan **multi-badan-usaha** berbasis **PHP + MySQL** untuk mengonsolidasikan hotel, retail/toko, kos/properti, F&B, jasa, dan unit usaha lain dalam satu group.

## Modul utama
- Executive Dashboard dengan grafik Canvas modern, responsive, light/dark mode.
- Multi Company / Entity Management + laporan per entitas atau konsolidasi.
- Double-Entry Journal & General Ledger.
- Approval Center berdasarkan nominal.
- Controlled Journal Reversal; jurnal sumber tidak dihapus.
- Cash & Bank + rekening terdaftar dengan masking nomor rekening.
- Bank CSV Import + Reconciliation.
- AR / AP + aging + payment allocation ke ledger.
- Fiscal Period Closing / hard lock.
- Laporan Laba Rugi, Neraca, Arus Kas, Buku Besar, Neraca Saldo, Intercompany.
- Export CSV/Excel-compatible + Print/Save PDF.
- Budget & Forecast.
- Fixed Assets + straight-line book value.
- Intercompany Matching + Consolidation Elimination.
- Tax Profiles + kalkulator inclusive/exclusive.
- Multi-Currency + realized FX gain/loss.
- Integration Staging API dengan integration key + idempotency.
- Backup logical snapshot + SHA-256 verification + owner-only restore pada Demo Mode.
- Audit Trail.
- RBAC + company data scope.
- Health endpoint untuk deployment monitoring.

## Runtime
Server produksi **tidak membutuhkan Node.js**.

Minimum yang disarankan:
- PHP 8.1+ (PHP 8.2/8.3/8.4 direkomendasikan)
- PDO MySQL (`pdo_mysql`)
- MySQL 8.x atau MariaDB modern
- HTTPS
- Apache/Nginx dengan akses web hanya ke source publik aplikasi

`mbstring` bersifat opsional; aplikasi memiliki fallback untuk validasi string dasar.

## Jalankan lokal — Demo Mode
```bash
php -S 127.0.0.1:8080
```
Buka `http://127.0.0.1:8080`.

Demo Mode menggunakan `storage/demo-data.json` dan cocok untuk UAT UI/logic tanpa MySQL.

## Fresh install MySQL
### Server yang boleh membuat database
```bash
mysql -u USER -p < database/schema.sql
mysql -u USER -p < database/seed.sql
```

### Shared hosting/cPanel yang databasenya sudah dibuat
1. Buat database dan user dari panel hosting.
2. Import `database/schema_shared_hosting.sql` melalui phpMyAdmin.
3. Import `database/seed_shared_hosting.sql`.
4. Copy `config/config.local.example.php` menjadi `config/config.local.php`.
5. Isi kredensial database, `setup_key`, dan `integration_key`.
6. Pastikan `demo_mode => false`.
7. Buka `setup.php` untuk membuat Group Owner pertama.
8. Setelah user pertama dibuat, `setup.php` otomatis tidak dapat membuat owner kedua.

## Upgrade RC2 → Production Final R2
Backup RC2 lebih dulu, timpa source, lalu jalankan:
```sql
SOURCE database/migrations/20260925_v4_production_final.sql;
```
Di phpMyAdmin, cukup import file migration tersebut.

## Security behavior penting
- Mode produksi **fail-closed**. Jika PDO MySQL/koneksi DB gagal, aplikasi tidak diam-diam menampilkan data demo.
- Debit wajib sama dengan kredit sebelum jurnal terposting.
- Closed period memblokir mutation.
- Approval override tidak dapat dikirim melalui request eksternal.
- Entity Admin dibatasi ke company scope.
- Nomor rekening dimasking di dashboard payload.
- CSRF, prepared statements, secure session baseline, login throttling, password hashing, dan audit log aktif.
- Direktori `config`, `database`, `storage`, `tests`, `docs`, `lib`, dan `.github` dilindungi `.htaccess` pada Apache.

## Integration staging API
Endpoint: `POST /integration.php`

Header:
```text
Content-Type: application/json
X-Nexa-Key: <integration_key>
```

Contoh payload:
```json
{
  "source": "HOTEL-PMS",
  "company_id": 1,
  "external_ref": "SETTLEMENT-20260925-001",
  "amount": 48500000,
  "payload": {
    "note": "raw source payload dapat ikut disimpan untuk mapping"
  }
}
```

Endpoint hanya menulis ke **integration staging**, bukan langsung ke ledger. `(source, external_ref)` bersifat idempotent.

## Test
Demo/accounting regression:
```bash
php tests/run.php
```

Fail-closed production check:
```bash
php tests/production-fail-closed.php
```

MySQL production simulation disediakan di:
```text
.github/workflows/mysql-production.yml
```
Workflow tersebut menjalankan MySQL 8.4, import schema+seed, production function tests, login+CSRF HTTP test, journal mutation API, health endpoint, serta integration staging API.

## Deployment gate
Lihat `PRODUCTION-CHECKLIST.md` dan `VALIDATION.md` sebelum digunakan sebagai pembukuan resmi.

## Pendapatan Harian Dinamis (R2)
Menu **Pendapatan Harian** memberikan form operasional per badan usaha. Kategori pendapatan dapat dikonfigurasi dan dipetakan ke akun revenue; penerimaan dapat dibagi ke kanal kas/bank. Sistem hanya memposting bila total pendapatan = total pembayaran, periode masih open, user berwenang, dan approval threshold terpenuhi.
