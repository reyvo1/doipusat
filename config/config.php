<?php
$base = [
    'app_name' => getenv('NEXA_APP_NAME') ?: 'NEXA Group Finance',
    'version' => '5.0.0-rc3',
    'environment' => getenv('NEXA_ENV') ?: 'production',
    'timezone' => getenv('NEXA_TIMEZONE') ?: 'Asia/Makassar',
    'base_currency' => getenv('NEXA_CURRENCY') ?: 'IDR',
    'demo_mode' => filter_var(getenv('NEXA_DEMO_MODE') ?: 'true', FILTER_VALIDATE_BOOL),
    'approval_threshold' => (float)(getenv('NEXA_APPROVAL_THRESHOLD') ?: 25000000),
    'setup_key' => getenv('NEXA_SETUP_KEY') ?: '',
    'integration_key' => getenv('NEXA_INTEGRATION_KEY') ?: '',
    'db' => [
        'host' => getenv('NEXA_DB_HOST') ?: '127.0.0.1',
        'port' => getenv('NEXA_DB_PORT') ?: '3306',
        'name' => getenv('NEXA_DB_NAME') ?: 'nexa_group_finance',
        'user' => getenv('NEXA_DB_USER') ?: 'root',
        'pass' => getenv('NEXA_DB_PASS') ?: '',
        'charset' => 'utf8mb4',
    ],
];
$local = __DIR__.'/config.local.php';
if (is_file($local)) {
    $override = require $local;
    if (is_array($override)) $base = array_replace_recursive($base, $override);
}
return $base;
