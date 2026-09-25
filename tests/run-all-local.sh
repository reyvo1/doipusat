#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")/.."
echo '== PHP syntax =='
find . -name '*.php' -print0 | xargs -0 -n1 php -l >/tmp/nexa-r3-php-lint.log
echo 'PASS all PHP files'
echo '== JavaScript syntax =='
node --check assets/app.js
node --check tests/browser-uat.mjs
echo 'PASS JavaScript syntax'
echo '== Legacy regression =='
php tests/run.php
echo '== Enterprise domain =='
php tests/enterprise-domain.php
echo '== Enterprise advanced =='
php tests/enterprise-advanced.php
echo '== Architecture =='
php tests/architecture.php
echo '== Production fail-closed =='
php tests/production-fail-closed.php
echo 'LOCAL GATES PASS — MySQL/browser production gates run in GitHub Actions.'
