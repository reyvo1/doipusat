# NEXA Group Finance — Enterprise R3 Release Candidate

NEXA Group Finance adalah pusat keuangan multi-badan-usaha berbasis **PHP + MySQL** untuk grup usaha seperti hotel, retail, kos/properti, F&B, jasa, dan unit bisnis lain. Production runtime tidak membutuhkan Node.js.

## Status

**Enterprise R3 Release Candidate (5.0.0-rc3)**. Source telah melewati seluruh gate lokal yang tersedia. Label Production Final hanya diberikan setelah workflow **NEXA Full UAT** pada GitHub hijau seluruhnya menggunakan MySQL 8.4 + browser E2E.

## Kemampuan utama

- Multi company, branch, department, cost center, profit center.
- Chart of Accounts dan double-entry ledger.
- Pendapatan Harian dinamis per jenis usaha dan split kas/bank.
- Approval policy, journal batch, controlled reversal, period closing.
- AR/AP, invoice detail, payment allocation, aging.
- Cash/bank, CSV bank import, reconciliation session dan matching.
- Budget/forecast dan variance.
- Fixed asset + depreciation schedule/posting.
- Pajak configurable dan tax transaction register.
- Multi-currency, FX rate history dan realized gain/loss.
- Intercompany matching, consolidation run dan elimination layer.
- Integration staging/inbox/outbox + idempotency.
- Settlement batch untuk OTA/payment channel.
- RBAC, entity scope, CSRF, prepared statements, login throttling, audit trail.
- Laba Rugi, Neraca, Arus Kas, Buku Besar, Neraca Saldo, Intercompany.
- CSV/XLS/Print-PDF compatible export.
- Dashboard modern, dark mode, grafik native tanpa CDN.
- Backup/restore snapshot dengan checksum.

## Arsitektur

Business rules dipisah dari UI/legacy adapter di `src/`:

- `Accounting/`, `Revenue/`, `ARAP/`, `Banking/`
- `Consolidation/`, `Tax/`, `FX/`, `Assets/`, `Budget/`
- `Reports/`, `Security/`, `Audit/`, `Integration/`
- `Organization/`, `Infrastructure/Persistence/`, `Application/`

Lihat `docs/ENTERPRISE-R3-ARCHITECTURE.md`.

## Fresh install

Persyaratan minimum yang disarankan:

- PHP 8.2+
- PDO MySQL
- MySQL 8.0+/8.4
- HTTPS untuk production

Buat database dengan:

```bash
mysql -u USER -p < database/schema_enterprise.sql
mysql -u USER -p nexa_group_finance < database/seed_enterprise.sql
```

`seed_enterprise.sql` hanya baseline/UAT. Untuk produksi resmi, ganti dengan badan usaha, COA, saldo awal, user dan master data riil.

Konfigurasi dapat menggunakan environment variable atau `config/config.local.php`. Jangan commit password database atau integration key.

Set minimal:

```text
NEXA_DEMO_MODE=false
NEXA_DB_HOST=127.0.0.1
NEXA_DB_PORT=3306
NEXA_DB_NAME=nexa_group_finance
NEXA_DB_USER=...
NEXA_DB_PASS=...
NEXA_SETUP_KEY=...
NEXA_INTEGRATION_KEY=...
NEXA_TIMEZONE=Asia/Makassar
```

## Upgrade dari Production Final R2

Backup database lebih dahulu, lalu jalankan:

```bash
mysql -u USER -p nexa_group_finance < database/migrations/20260925_v6_enterprise_r3.sql
```

Migration v6 mempertahankan data R2 dan menambah branch/department, journal batch, invoice lines, payment allocations, bank reconciliation session, depreciation schedule, tax transaction, FX rate, consolidation run, integration inbox/outbox, settlement, approval policy dan normalized uniqueness scopes.

## Local regression di Ubuntu

```bash
cd ~/Desktop/program/'keuangan sentral'
bash tests/run-all-local.sh
```

Gate MySQL produksi dan browser penuh dijalankan pada GitHub Actions karena membutuhkan service MySQL 8.4 dan Chromium/Playwright.

## Full UAT GitHub

Workflow: `.github/workflows/full-uat.yml`

Gate wajib:

1. Core / Static Regression
2. MySQL / Accounting UAT
3. R2 → R3 Migration UAT
4. HTTP / Security / Reports UAT
5. Browser E2E / Visual UAT
6. Multi-Entity / High-Volume UAT
7. Production Candidate UAT Verdict

Verdict hanya hijau jika semua dependency hijau. Screenshot browser dan server logs diunggah sebagai GitHub artifacts.

## Aturan produksi penting

- Jangan menjalankan production dengan `NEXA_DEMO_MODE=true`.
- Jangan menghapus jurnal posted; gunakan reversal.
- Jangan edit database dengan DDL ad-hoc; gunakan migration resmi.
- Jangan buka approval/period/security gate hanya agar test hijau.
- Jangan masukkan data keuangan resmi sebelum Full UAT GitHub hijau dan staging UAT selesai.
