# Production Checklist — NEXA Group Finance

## 1. Runtime
- [ ] PHP 8.1+ aktif.
- [ ] `pdo_mysql` aktif.
- [ ] HTTPS aktif dan redirect HTTP→HTTPS diterapkan di hosting/reverse proxy.
- [ ] `config/config.local.php` tidak dapat diakses publik.
- [ ] `storage/`, `database/`, `tests/`, `docs/`, `lib/`, `.github/` tidak dapat dibrowse publik.

## 2. Database
- [ ] Fresh schema atau migration v4 berhasil tanpa error.
- [ ] DB user memakai least privilege untuk database NEXA saja.
- [ ] `health.php` mengembalikan `database: reachable`.
- [ ] `tests/mysql-production.php` PASS pada staging MySQL target.
- [ ] GitHub Actions `NEXA MySQL Production Simulation` hijau.

## 3. Business master data
- [ ] Semua badan usaha/cabang dimasukkan.
- [ ] Chart of Accounts disepakati dengan akuntan/perusahaan.
- [ ] Opening balances diimpor dan Trial Balance = 0 selisih debit/kredit.
- [ ] Accounting equation direkonsiliasi.
- [ ] Rekening bank dipetakan ke COA yang benar.
- [ ] Approval threshold/matrix disepakati.
- [ ] Fiscal calendar dan closing policy disepakati.

## 4. Pajak & FX
- [ ] Tax profile direview sesuai kewajiban masing-masing badan usaha.
- [ ] Perlakuan pajak hotel/retail/kos/properti/F&B diverifikasi akuntan/pajak.
- [ ] Rate source FX dan prosedur revaluation/settlement disepakati.

## 5. Integration
- [ ] `integration_key` berbeda dari `setup_key` dan password database.
- [ ] Connector TAMASYA/Hotel mapping disetujui.
- [ ] POS/retail mapping disetujui.
- [ ] Kos/property mapping disetujui.
- [ ] Idempotency `source + external_ref` diuji.
- [ ] Failed payload tidak langsung menyentuh ledger.

## 6. Security/UAT
- [ ] Login Group Owner PASS.
- [ ] Group Finance PASS.
- [ ] Entity Admin hanya melihat company-nya.
- [ ] Auditor read-only.
- [ ] Viewer read-only.
- [ ] Jurnal > threshold masuk approval.
- [ ] Closed period menolak mutation.
- [ ] Reversal tidak menghapus jurnal asli.
- [ ] Backup file tampered ditolak.

## 7. Disaster recovery
- [ ] Backup MySQL otomatis hosting/VPS aktif.
- [ ] Backup disimpan di lokasi berbeda dari server utama.
- [ ] Restore drill MySQL berhasil pada database staging kosong.
- [ ] RPO/RTO bisnis ditetapkan.

## 8. Go-live
- [ ] Demo Mode = false.
- [ ] Demo data tidak tercampur ke produksi.
- [ ] Opening balance signed-off.
- [ ] Satu hari transaksi paralel direkonsiliasi dengan sistem lama/manual.
- [ ] Owner/Finance menerima laporan group dan per-entitas yang sama dengan ledger.
