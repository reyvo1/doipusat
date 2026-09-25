# HANDOFF — NEXA Group Finance Enterprise R3 RC

## Baseline
- Version: `5.0.0-rc3`
- Runtime: PHP + MySQL
- Production Node.js: tidak diperlukan
- Fresh DB: `database/schema_enterprise.sql`
- Upgrade R2: `database/migrations/20260925_v6_enterprise_r3.sql`
- Full workflow: `.github/workflows/full-uat.yml`

## Local evidence
- Legacy regression: 62/62 PASS
- Enterprise domain: 35/35 PASS
- Enterprise advanced: 33/33 PASS
- Architecture gate: PASS
- 21/21 demo route sweep: HTTP 200
- PHP lint: PASS
- JavaScript syntax: PASS
- Production fail-closed: PASS

## Perubahan penting R3
- Refactor business rules ke 80+ module PHP pada `src/`.
- Fresh enterprise schema dipisah dari upgrade migration.
- Branch, department, cost/profit center menjadi master data.
- Normalized unique scope untuk global COA dan budget nullable scope.
- Journal batch, invoice line, payment allocation, reconciliation session.
- Depreciation schedule, tax transaction, FX rate history.
- Consolidation run/elimination entry.
- Integration inbox/outbox, settlement batch, approval policy.
- Login `last_login_at`; rate-limit tetap hashed identifier.
- Bug production `demoUsers` undefined dari R2 diperbaiki.
- Production tetap fail-closed jika PDO MySQL/DB tidak tersedia.

## Next gate
Jangan menambah fitur sebelum GitHub Full UAT dijalankan. Push kandidat R3 ke `reyvo1/doipusat`, lalu seluruh 7 gate harus hijau. Jika ada merah, perbaiki root cause berdasarkan artifact/log gate tersebut tanpa melemahkan safety gate.
