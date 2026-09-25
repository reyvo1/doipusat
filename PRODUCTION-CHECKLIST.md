# Production Checklist — NEXA Enterprise R3

## Sebelum UAT GitHub
- [x] Source modular enterprise
- [x] Fresh enterprise schema terpisah
- [x] R2→R3 migration terpisah
- [x] Local deterministic regression hijau
- [x] 21 route demo hijau
- [x] Workflow MySQL/browser/scale tersedia
- [x] Tidak ada secret/token di source scan

## Wajib hijau di GitHub sebelum label Production Final
- [ ] Core / Static Regression
- [ ] MySQL / Accounting UAT
- [ ] R2 → R3 Migration UAT
- [ ] HTTP / Security / Reports UAT
- [ ] Browser E2E / Visual UAT
- [ ] Multi-Entity / High-Volume UAT
- [ ] Production Candidate UAT Verdict

## Wajib sebelum data keuangan resmi
- [ ] Backup database/staging
- [ ] COA riil disetujui finance/accounting
- [ ] Saldo awal direkonsiliasi
- [ ] Daftar badan usaha/cabang/departemen riil
- [ ] Rekening bank & payment channels riil
- [ ] Tax profile riil
- [ ] User/role riil
- [ ] HTTPS aktif
- [ ] `NEXA_DEMO_MODE=false`
- [ ] DB credentials dan integration key tidak berada di repository
- [ ] UAT transaksi harian, closing, reversal, AR/AP, bank reconciliation dan laporan ditandatangani owner/finance
