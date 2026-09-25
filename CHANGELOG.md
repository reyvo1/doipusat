# CHANGELOG

## Enterprise R3 RC — 2026-09-25

### Architecture
- Added PSR-4-style `Nexa\` autoloading and modular domain/application/infrastructure layers.
- Added 80+ focused PHP modules for accounting, organization, AR/AP, banking, tax, FX, assets, budget, consolidation, reports, security, integration and audit.
- Legacy UI/API remains compatible while selected rules delegate to typed enterprise services.

### Organization
- Added Branch and Department/Cost Center/Profit Center master data.
- Added real CRUD APIs and modern UI page `Cabang & Departemen`.

### Database
- Added clean fresh `schema_enterprise.sql` and `seed_enterprise.sql`.
- Added R2→R3 migration v6.
- Added journal batches, invoice lines, payment allocations, reconciliation sessions/matches, bank statement imports, depreciation schedules, tax transactions, FX rates, approval policies, consolidation runs/eliminations, integration events/outbox and settlement batches.
- Added normalized generated scope columns to enforce uniqueness when optional scope IDs are NULL.
- Added structural parity fields for upgrade path.

### Reliability/security fixes
- Fixed undefined production `$demoUsers` path.
- Removed duplicate/wrong `login_attempts` migration definition.
- Added `last_login_at` update on successful authentication.
- Preserved fail-closed production DB behavior.

### QA
- 62/62 legacy regression PASS.
- 35/35 enterprise domain PASS.
- 33/33 enterprise advanced PASS.
- Added enterprise schema and relational DB UAT for GitHub MySQL 8.4.
- Added R2→R3 migration preservation gate.
- Expanded route/browser UAT to 21 pages including Organization.
