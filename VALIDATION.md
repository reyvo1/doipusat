# NEXA Group Finance — Production Final R1 Validation

Date: 2026-09-25
Version: 4.0.0

## Local regression

- Accounting/security/application regression: **55 passed, 0 failed**.
- PHP syntax: **PASS** for all PHP source files.
- JavaScript syntax (`assets/app.js`): **PASS**.
- Production fail-closed test: **PASS**. When `demo_mode=false` and PDO MySQL is unavailable, the application refuses to fall back to Demo Mode.
- HTTP route sweep: **19/19 pages HTTP 200**.
- Financial report views: **6/6 HTTP 200** (P&L, Balance Sheet, Cash Flow, Ledger, Trial Balance, Intercompany).
- Report exports: **18/18 HTTP 200** (CSV, XLS-compatible, Print/PDF view for all six reports).
- Health endpoint in Demo Mode: **HTTP 200**.
- Integration endpoint guard in Demo Mode: **HTTP 503 as designed**.
- Server route/report sweep: **no PHP warning, fatal error, parse error, or uncaught exception**.
- Backup restore: valid signed snapshot accepted; deliberately tampered snapshot rejected on SHA-256 mismatch.
- ZIP integrity: verified after packaging.

## Production-path controls covered by regression

Double-entry validation, approval threshold, approval posting, closed-period blocking, RBAC/entity scoping, controlled journal reversal, AR/AP allocation, bank reconciliation checks, intercompany elimination, configurable tax calculation, FX realized gain/loss, CSV bank import/idempotency, CRUD for companies/users/budgets/assets/bank accounts, account masking, trial balance equality, balance-sheet equation exposure, and protection against external approval override.

## MySQL runtime sign-off

The current execution container does **not** provide a PDO MySQL driver or MySQL/MariaDB server. Therefore a live `demo_mode=false` MySQL runtime run was not falsely marked PASS here.

The package includes the executable gate for that environment:

- `.github/workflows/mysql-production.yml`
- `tests/mysql-production.php`
- `tests/http-production.sh`
- `database/schema.sql`
- `database/schema_shared_hosting.sql`
- `database/migrations/20260925_v4_production_final.sql`

Before using the application for official bookkeeping, run the MySQL workflow/staging gate and require it to pass.

## Visual QA note

The UI, responsive CSS, Canvas chart code, routes, and rendered HTML were inspected. A Chromium screenshot run was attempted, but the available sandbox Chromium process could not start cleanly because of its container DBus/zygote environment. No screenshot-based visual PASS is claimed.

## R2 addendum — Dynamic Daily Income
See `VALIDATION-R2.md`. Regression result: **62/62 PASS** and runtime route sweep: **20/20 HTTP 200**. Daily income posting was exercised over HTTP with CSRF and created a balanced `daily_income` ledger entry.
