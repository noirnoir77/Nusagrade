<?php
// SECURITY: Delete this file immediately after use!
// Access via: https://yourdomain.com/run-setup.php

define('LARAVEL_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$commands = [
    ['key:generate', ['--force' => true]],
    ['migrate', ['--force' => true]],
    ['storage:link', []],
    ['config:cache', []],
    ['route:cache', []],
    ['view:cache', []],
];

echo "<pre style='font-family:monospace; background:#111; color:#0f0; padding:20px; font-size:14px;'>";
echo "=== Nusagrade Setup Runner ===\n\n";

foreach ($commands as [$command, $args]) {
    echo "Running: php artisan {$command}\n";
    try {
        $status = $kernel->call($command, $args);
        echo "  -> " . ($status === 0 ? "OK" : "Failed (exit: {$status})") . "\n\n";
    } catch (\Throwable $e) {
        echo "  -> ERROR: " . $e->getMessage() . "\n\n";
    }
}

echo "=== Done ===\n";
echo "\n!! DELETE THIS FILE NOW from File Manager !!";
echo "</pre>";
