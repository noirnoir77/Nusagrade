<?php
/**
 * One-shot post-deploy bootstrapper for shared hosting without SSH.
 *
 * Usage:
 *   1. Edit .env via hPanel File Manager:
 *        - set DB_PASSWORD, MAIL_PASSWORD, ADMIN_EMAIL, ADMIN_PASSWORD
 *        - set SETUP_TOKEN to a long random string (any 40+ chars works)
 *   2. Hit  https://nusagrade.com/run-setup.php?token=THE_TOKEN
 *      If APP_KEY was empty, this generates it and exits — re-hit the URL
 *      to complete the rest of setup.
 *   3. DELETE this file from public/ via File Manager after a successful run.
 */

define('LARAVEL_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

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

header('Content-Type: text/plain; charset=utf-8');
echo "=== Nusagrade Setup Runner ===\n\n";

// --- Phase 1: APP_KEY ----------------------------------------------------
// If APP_KEY is empty we must NOT run config:cache in the same request,
// because Dotenv's createImmutable loader has already cached an empty
// APP_KEY in $_ENV for this process. config:cache would then bake the
// empty value into bootstrap/cache/config.php and break the app on every
// subsequent request. So we generate the key and exit — user re-runs.
$currentKey = $_ENV['APP_KEY'] ?? (getenv('APP_KEY') ?: '');
if (trim((string) $currentKey) === '') {
    echo "APP_KEY is empty. Generating one and exiting.\n";
    try {
        $status = $kernel->call('key:generate', ['--force' => true]);
        echo '  -> ' . ($status === 0 ? 'OK' : "Failed (exit: {$status})") . "\n\n";
    } catch (\Throwable $e) {
        echo '  -> ERROR: ' . $e->getMessage() . "\n\n";
        exit;
    }

    // Also clear any cached config that may have empty APP_KEY baked in.
    $cachedConfig = __DIR__ . '/../bootstrap/cache/config.php';
    if (file_exists($cachedConfig)) {
        @unlink($cachedConfig);
        echo "Removed stale bootstrap/cache/config.php\n\n";
    }

    echo ">>> APP_KEY generated. Now RE-HIT this URL to complete the rest of setup.\n";
    exit;
}

// --- Phase 2: everything else (APP_KEY already exists) -------------------
$commands = [
    ['config:clear',  []],
    ['route:clear',   []],
    ['view:clear',    []],
    ['migrate',       ['--force' => true]],
    ['db:seed',       ['--force' => true]],
    ['config:cache',  []],
    ['route:cache',   []],
    ['view:cache',    []],
    ['event:cache',   []],
];

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
// Hostinger disables symlink() and exec(), so config/filesystems.php points
// the "public" disk at public_path('storage') directly — no symlink needed.
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
