# Changelog

## Production Final R2 — 2026-09-25
- Production DB fail-closed; tidak ada silent fallback ke Demo Mode.
- Dashboard production trend dan KPI dihitung dari ledger nyata.
- External approval override ditutup; bypass hanya dapat dipanggil internal approval flow.
- Atomic production posting untuk approval dan AR/AP payment allocation.
- Account/company ownership validation untuk posting jurnal.
- Bank reconciliation memvalidasi company, amount, dan cash-flow direction.
- COA lookup AR/AP berdasarkan account code, tidak bergantung ID hasil import.
- Company/User/Budget/Asset/Bank Account CRUD backend + UI actions.
- Financial report tabs diaktifkan: P&L, Balance Sheet, Cash Flow, Ledger, Trial Balance, Intercompany.
- Group/entity report scope + scoped export.
- Neraca memasukkan cumulative retained earnings/profit pada equity summary.
- Account number masking dan user-directory scope hardening.
- Optional `mbstring` fallback untuk shared-hosting portability.
- Future asset depreciation regression diperbaiki.
- Health endpoint `health.php`.
- Integration staging endpoint `integration.php` dengan separate key, idempotency, 1 MB payload cap.
- Login rate limiting + password rehash path.
- `.htaccess` hardening untuk source sensitif.
- Backup restore sekarang benar-benar memverifikasi SHA-256 dan size limit.
- Shared-hosting schema/seed dan `config.local.php` pattern.
- MySQL 8.4 GitHub Actions production simulation workflow.
- Demo/accounting/security suite naik menjadi **55 checks**.

## RC2 — Finance Completion & Recovery Hardening — 2026-09-25
- Controlled reversal, AR/AP allocation, elimination, tax, FX, bank import, backup, integration staging.

## RC1 — Production Hardening
- Authentication/RBAC, approvals, period close, AR/AP, bank reconciliation, multi-currency registry.

## Production Final R2 — Dynamic Daily Income
- Added dynamic Pendapatan Harian UI per entity.
- Added configurable income categories mapped to revenue accounts.
- Added split cash/bank receipt input and multi-line double-entry posting.
- Added approval/period/RBAC/entity-scope gates for daily income.
- Added migration 20260925_v5_daily_income.sql and regression coverage.
