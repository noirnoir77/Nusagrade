<?php
/**
 * Nusagrade Web Installer
 *
 * Replaces the old two-pass run-setup.php.
 * - No terminal / SSH required.
 * - No manual .env editing needed.
 * - Generates APP_KEY in-process (no exec/proc_open).
 * - Single-pass: writes .env BEFORE bootstrapping Laravel,
 *   so there is no chicken-and-egg APP_KEY problem.
 *
 * States
 *   .env missing or APP_KEY empty  →  show install form
 *   .env present & APP_KEY filled  →  show "already installed" page
 */

define('BASE', dirname(__DIR__));

// ── State detection ──────────────────────────────────────────────────────────

function readEnvValue(string $file, string $key): string
{
    if (!is_file($file)) {
        return '';
    }
    foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#')) {
            continue;
        }
        [$k, $v] = array_pad(explode('=', $line, 2), 2, '');
        if (trim($k) === $key) {
            return trim($v, " \t\n\r\"'");
        }
    }
    return '';
}

$envFile      = BASE . '/.env';
$templateFile = BASE . '/.env.production.example';
$isInstalled  = is_file($envFile) && readEnvValue($envFile, 'APP_KEY') !== '';

// ── Handle POST (install) ────────────────────────────────────────────────────

$errors  = [];
$log     = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$isInstalled) {

    $dbPassword    = trim($_POST['db_password']    ?? '');
    $mailPassword  = trim($_POST['mail_password']  ?? '');
    $adminEmail    = trim($_POST['admin_email']    ?? '');
    $adminPassword = trim($_POST['admin_password'] ?? '');

    if ($dbPassword   === '') $errors[] = 'Database password wajib diisi.';
    if ($adminEmail   === '') $errors[] = 'Admin email wajib diisi.';
    if ($adminPassword === '') $errors[] = 'Admin password wajib diisi.';

    if (empty($errors)) {
        // 1. Generate APP_KEY (same algorithm as `php artisan key:generate`)
        $appKey = 'base64:' . base64_encode(random_bytes(32));

        // 2. Build .env content from template
        if (!is_file($templateFile)) {
            $errors[] = '.env.production.example tidak ditemukan di root project.';
        } else {
            $env = file_get_contents($templateFile);

            $replacements = [
                '/^APP_KEY=.*$/m'        => 'APP_KEY=' . $appKey,
                '/^DB_PASSWORD=.*$/m'    => 'DB_PASSWORD=' . $dbPassword,
                '/^MAIL_PASSWORD=.*$/m'  => 'MAIL_PASSWORD=' . $mailPassword,
                '/^ADMIN_EMAIL=.*$/m'    => 'ADMIN_EMAIL=' . $adminEmail,
                '/^ADMIN_PASSWORD=.*$/m' => 'ADMIN_PASSWORD=' . $adminPassword,
                '/^SETUP_TOKEN=.*$/m'    => '',  // not used anymore
            ];

            foreach ($replacements as $pattern => $replacement) {
                $env = preg_replace($pattern, $replacement, $env);
            }

            // 3. Write .env
            if (file_put_contents($envFile, $env) === false) {
                $errors[] = 'Gagal menulis file .env. Pastikan direktori root bisa ditulis (chmod 755).';
            }
        }
    }

    if (empty($errors)) {
        // 4. Set APP_KEY in current process so Laravel can boot
        putenv("APP_KEY={$appKey}");
        $_ENV['APP_KEY']    = $appKey;
        $_SERVER['APP_KEY'] = $appKey;

        // 5. Bootstrap Laravel and run setup commands
        try {
            require BASE . '/vendor/autoload.php';
            $app    = require BASE . '/bootstrap/app.php';
            $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

            $commands = [
                ['config:clear', []],
                ['route:clear',  []],
                ['view:clear',   []],
                ['migrate',      ['--force' => true]],
                ['db:seed',      ['--force' => true]],
                ['config:cache', []],
                ['route:cache',  []],
                ['view:cache',   []],
                ['event:cache',  []],
            ];

            foreach ($commands as [$cmd, $args]) {
                ob_start();
                $status = $kernel->call($cmd, $args);
                $output = trim(ob_get_clean());
                $ok     = ($status === 0);
                $log[]  = ['cmd' => "php artisan {$cmd}", 'ok' => $ok, 'out' => $output];
            }

        } catch (\Throwable $e) {
            $errors[] = 'Laravel bootstrap error: ' . $e->getMessage();
        }

        // 6. Ensure public/storage directory exists (Hostinger: symlink disabled)
        $storageDir = __DIR__ . '/storage';
        if (!is_dir($storageDir)) {
            if (@mkdir($storageDir, 0775, true)) {
                $log[] = ['cmd' => 'mkdir public/storage', 'ok' => true, 'out' => ''];
            } else {
                $log[] = ['cmd' => 'mkdir public/storage', 'ok' => false,
                    'out' => 'Buat folder ini manual via hPanel File Manager.'];
            }
        }

        if (empty($errors)) {
            $success = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Nusagrade Installer</title>
<style>
  *, *::before, *::after { box-sizing: border-box; }
  body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    background: #f4f6f9;
    margin: 0;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
  }
  .card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 24px rgba(0,0,0,.08);
    width: 100%;
    max-width: 480px;
    padding: 2.5rem 2rem;
  }
  .logo { text-align: center; margin-bottom: 1.75rem; }
  .logo h1 { font-size: 1.6rem; color: #1a1a2e; margin: 0; }
  .logo p  { color: #6b7280; font-size: .9rem; margin: .35rem 0 0; }
  label { display: block; font-size: .85rem; font-weight: 600; color: #374151; margin-bottom: .35rem; }
  input[type=text], input[type=password], input[type=email] {
    width: 100%;
    padding: .6rem .85rem;
    border: 1.5px solid #d1d5db;
    border-radius: 8px;
    font-size: .95rem;
    transition: border-color .15s;
    outline: none;
  }
  input:focus { border-color: #6366f1; }
  .field { margin-bottom: 1.1rem; }
  .hint { font-size: .78rem; color: #9ca3af; margin-top: .25rem; }
  .btn {
    width: 100%;
    padding: .75rem;
    background: #6366f1;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: background .15s;
    margin-top: .5rem;
  }
  .btn:hover { background: #4f46e5; }
  .errors {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 8px;
    padding: .85rem 1rem;
    margin-bottom: 1.25rem;
    color: #b91c1c;
    font-size: .88rem;
  }
  .errors ul { margin: .35rem 0 0; padding-left: 1.2rem; }
  .section-title {
    font-size: .75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #9ca3af;
    margin: 1.5rem 0 .75rem;
  }
  .divider { border: none; border-top: 1px solid #e5e7eb; margin: 1.25rem 0; }
  /* Log */
  .log-item {
    display: flex;
    align-items: flex-start;
    gap: .6rem;
    padding: .45rem 0;
    font-size: .85rem;
    border-bottom: 1px solid #f3f4f6;
  }
  .log-item:last-child { border-bottom: none; }
  .badge {
    flex-shrink: 0;
    font-size: .7rem;
    font-weight: 700;
    padding: .15rem .5rem;
    border-radius: 4px;
  }
  .badge-ok  { background: #d1fae5; color: #065f46; }
  .badge-err { background: #fee2e2; color: #991b1b; }
  .log-cmd { color: #374151; font-family: monospace; }
  .log-out { color: #6b7280; font-size: .78rem; margin-top: .15rem; white-space: pre-wrap; }
  /* Success / Already installed */
  .state-icon { text-align: center; font-size: 3rem; margin-bottom: .5rem; }
  .state-title { text-align: center; font-size: 1.3rem; font-weight: 700; color: #111827; margin-bottom: .5rem; }
  .state-desc  { text-align: center; color: #6b7280; font-size: .9rem; margin-bottom: 1.5rem; }
  .btn-outline {
    display: block;
    text-align: center;
    padding: .65rem;
    border: 2px solid #6366f1;
    border-radius: 8px;
    color: #6366f1;
    font-weight: 600;
    text-decoration: none;
    font-size: .95rem;
    transition: background .15s, color .15s;
  }
  .btn-outline:hover { background: #6366f1; color: #fff; }
</style>
</head>
<body>
<div class="card">
  <div class="logo">
    <h1>Nusagrade</h1>
    <p>Web Installer</p>
  </div>

<?php if ($isInstalled && !$success): ?>
  <!-- Already installed -->
  <div class="state-icon">✅</div>
  <div class="state-title">Aplikasi sudah terpasang</div>
  <div class="state-desc">
    File <code>.env</code> sudah ada dan APP_KEY sudah terisi.<br>
    Installer ini tidak akan melakukan apapun lagi.
  </div>
  <a class="btn-outline" href="/">Buka Nusagrade &rarr;</a>

<?php elseif ($success): ?>
  <!-- Success -->
  <div class="state-icon">🎉</div>
  <div class="state-title">Instalasi berhasil!</div>
  <div class="state-desc">Database sudah dimigrate, admin sudah dibuat, cache sudah dibangun.</div>

  <div class="section-title">Log Setup</div>
  <?php foreach ($log as $item): ?>
    <div class="log-item">
      <span class="badge <?= $item['ok'] ? 'badge-ok' : 'badge-err' ?>"><?= $item['ok'] ? 'OK' : 'ERR' ?></span>
      <div>
        <div class="log-cmd"><?= htmlspecialchars($item['cmd']) ?></div>
        <?php if ($item['out'] !== ''): ?>
          <div class="log-out"><?= htmlspecialchars($item['out']) ?></div>
        <?php endif ?>
      </div>
    </div>
  <?php endforeach ?>

  <hr class="divider">
  <a class="btn-outline" href="/">Buka Nusagrade &rarr;</a>

<?php else: ?>
  <!-- Install form -->
  <?php if (!empty($errors)): ?>
    <div class="errors">
      <strong>Ada masalah:</strong>
      <ul>
        <?php foreach ($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach ?>
      </ul>
    </div>
  <?php endif ?>

  <form method="POST">
    <div class="section-title">Database</div>

    <div class="field">
      <label>Database Password</label>
      <input type="password" name="db_password" autocomplete="off" required>
      <div class="hint">Password MySQL dari hPanel → Databases.</div>
    </div>

    <div class="section-title">Email (SMTP)</div>

    <div class="field">
      <label>Mail Password <span style="font-weight:400;color:#9ca3af">(opsional)</span></label>
      <input type="password" name="mail_password" autocomplete="off">
      <div class="hint">Password email contact@nusagrade.com dari Hostinger. Bisa diisi nanti.</div>
    </div>

    <div class="section-title">Akun Admin</div>

    <div class="field">
      <label>Admin Email</label>
      <input type="email" name="admin_email" value="contact@nusagrade.com" required>
    </div>

    <div class="field">
      <label>Admin Password</label>
      <input type="password" name="admin_password" autocomplete="new-password" required>
      <div class="hint">Buat password yang kuat. Bisa diganti dari admin panel setelah login.</div>
    </div>

    <button class="btn" type="submit">Install Sekarang</button>
  </form>
<?php endif ?>
</div>
</body>
</html>
