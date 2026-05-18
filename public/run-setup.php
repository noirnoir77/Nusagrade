<?php
/**
 * One-shot post-deploy bootstrapper for shared hosting without SSH.
 *
 * Usage:
 *   1. Edit .env via hPanel File Manager:
 *        - set DB_PASSWORD, MAIL_PASSWORD, ADMIN_EMAIL, ADMIN_PASSWORD
 *        - set SETUP_TOKEN to a long random string (any 40+ chars works)
 *   2. Hit  https://nusagrade.com/run-setup.php?token=THE_TOKEN
 *   3. DELETE this file from public/ via File Manager after a successful run.
 *
 * What it does (in order):
 *   - Generates APP_KEY if not already set
 *   - Runs database migrations
 *   - Seeds the admin user from ADMIN_EMAIL / ADMIN_PASSWORD
 *   - Creates the storage symlink
 *   - Clears + rebuilds config / route / view / event caches
 */

define('LARAVEL_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

// Load .env so we can read SETUP_TOKEN before booting the framework.
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->safeLoad();
}

$expected = $_ENV['SETUP_TOKEN'] ?? (getenv('SETUP_TOKEN') ?: '');
$provided = $_GET['token'] ?? '';

if ($expected === '' || !hash_equals((string) $expected, (string) $provided)) {
    http_response_code(404);
    exit;
}

$app    = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$commands = [];

// Only generate APP_KEY if one is not already set, so re-runs don't
// invalidate existing sessions/encrypted data.
$currentKey = $_ENV['APP_KEY'] ?? (getenv('APP_KEY') ?: '');
if (trim((string) $currentKey) === '') {
    $commands[] = ['key:generate', ['--force' => true]];
}

// NOTE: `php artisan storage:link` is skipped here. Hostinger shared hosting
// disables exec(), which Laravel's storage:link can fall back to. We create
// the symlink directly via PHP's symlink() below.
$commands = array_merge($commands, [
    ['config:clear',  []],
    ['route:clear',   []],
    ['view:clear',    []],
    ['migrate',       ['--force' => true]],
    ['db:seed',       ['--force' => true]],
    ['config:cache',  []],
    ['route:cache',   []],
    ['view:cache',    []],
    ['event:cache',   []],
]);

header('Content-Type: text/plain; charset=utf-8');
echo "=== Nusagrade Setup Runner ===\n\n";

foreach ($commands as [$command, $args]) {
    echo "Running: php artisan {$command}\n";
    try {
        $status = $kernel->call($command, $args);
        echo '  -> ' . ($status === 0 ? 'OK' : "Failed (exit: {$status})") . "\n\n";
    } catch (\Throwable $e) {
        echo '  -> ERROR: ' . $e->getMessage() . "\n\n";
    }
}

// Ensure public/storage exists as a regular directory.
// Hostinger disables symlink() and exec(), so we skip symlinking entirely;
// config/filesystems.php points the "public" disk at public_path('storage')
// directly, so uploads land here without a symlink.
echo "Ensuring public/storage directory exists\n";
$dir = __DIR__ . '/storage';
if (is_dir($dir)) {
    echo "  -> already exists\n\n";
} elseif (@mkdir($dir, 0775, true)) {
    echo "  -> OK (directory created)\n\n";
} else {
    echo "  -> ERROR: could not create public/storage. Create it in hPanel File Manager.\n\n";
}

echo "=== Done ===\n";
echo "\n!! DELETE public/run-setup.php now via hPanel File Manager !!\n";
