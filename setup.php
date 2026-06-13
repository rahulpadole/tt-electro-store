<?php
/**
 * TT Electro Store — First-Run Setup Script
 *
 * Run this ONCE after importing schema.sql to create your admin account.
 * Then DELETE this file from your server for security.
 *
 * Usage: visit https://yourdomain.com/setup.php in your browser
 * (or run: php setup.php from CLI)
 */
declare(strict_types=1);

// Block if already set up (admin exists)
define('BOOTSTRAPPING', true);

$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
        [$k, $v] = explode('=', $line, 2);
        putenv(trim($k) . '=' . trim($v));
        $_ENV[trim($k)] = trim($v);
    }
}

$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: '3306';
$name = getenv('DB_NAME') ?: 'tt_electro_store';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';

$errors = [];
$success = false;

try {
    $pdo = new PDO(
        "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4",
        $user, $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (PDOException $e) {
    $errors[] = 'Database connection failed: ' . $e->getMessage();
    $pdo = null;
}

if ($pdo && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminName     = trim($_POST['name'] ?? '');
    $adminEmail    = trim($_POST['email'] ?? '');
    $adminPassword = $_POST['password'] ?? '';
    $adminConfirm  = $_POST['confirm'] ?? '';

    if (!$adminName)   $errors[] = 'Name is required.';
    if (!filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
    if (strlen($adminPassword) < 8) $errors[] = 'Password must be at least 8 characters.';
    if ($adminPassword !== $adminConfirm) $errors[] = 'Passwords do not match.';

    if (empty($errors)) {
        $existing = $pdo->prepare('SELECT id FROM users WHERE email=? AND role="admin"');
        $existing->execute([$adminEmail]);
        if ($existing->fetch()) {
            $errors[] = 'An admin with this email already exists.';
        } else {
            $hash = password_hash($adminPassword, PASSWORD_BCRYPT);
            $st = $pdo->prepare(
                'INSERT INTO users (name,email,password,role,is_active) VALUES (?,?,?,\'admin\',1)'
            );
            $st->execute([$adminName, $adminEmail, $hash]);
            $success = true;
        }
    }
}

// Check if tables exist
$tablesOk = false;
if ($pdo) {
    try {
        $pdo->query('SELECT 1 FROM users LIMIT 1');
        $tablesOk = true;
    } catch (PDOException $e) {
        $errors[] = 'Tables not found. Please import database/schema.sql first.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>TT Electro Store — Setup</title>
<style>
  body{font-family:system-ui,sans-serif;background:#0d1321;color:#e2e8f0;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem}
  .card{background:#1a2236;border:1px solid rgba(255,255,255,.08);border-radius:1rem;padding:2.5rem;width:100%;max-width:480px}
  h1{font-size:1.5rem;font-weight:700;color:#fff;margin:0 0 .25rem}
  p.sub{color:#64748b;font-size:.875rem;margin:0 0 1.5rem}
  label{display:block;font-size:.8rem;color:#94a3b8;margin-bottom:.35rem}
  input{width:100%;box-sizing:border-box;padding:.6rem .9rem;border-radius:.5rem;border:1px solid rgba(255,255,255,.1);background:rgba(255,255,255,.05);color:#fff;font-size:.9rem;outline:none;transition:border .2s}
  input:focus{border-color:#3b82f6}
  .field{margin-bottom:1rem}
  button{width:100%;padding:.7rem;border-radius:.5rem;background:#2563eb;color:#fff;font-weight:600;border:none;cursor:pointer;font-size:.9rem;margin-top:.5rem;transition:background .2s}
  button:hover{background:#1d4ed8}
  .error-box{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);border-radius:.5rem;padding:.75rem 1rem;margin-bottom:1rem;color:#fca5a5;font-size:.85rem}
  .error-box li{margin:.2rem 0}
  .success-box{background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.3);border-radius:.5rem;padding:1rem;color:#86efac;margin-bottom:1rem;text-align:center}
  .warn{background:rgba(234,179,8,.1);border:1px solid rgba(234,179,8,.3);border-radius:.5rem;padding:.75rem 1rem;color:#fde047;font-size:.85rem;margin-bottom:1rem}
  .logo{display:flex;align-items:center;gap:.5rem;margin-bottom:1.5rem}
  .logo img{height:2.5rem;width:auto}
</style>
</head>
<body>
<div class="card">
  <div class="logo">
    <img src="/assets/logo.png" alt="TT Electro Store">
  </div>
  <h1>First-Run Setup</h1>
  <p class="sub">Create your administrator account to get started.</p>

  <?php if (!$pdo): ?>
  <div class="error-box"><strong>Database error</strong><ul><?php foreach($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
  <?php elseif (!$tablesOk): ?>
  <div class="error-box">
    <strong>Tables not found.</strong> Please import <code>database/schema.sql</code> into your MySQL database first, then reload this page.
  </div>
  <?php elseif ($success): ?>
  <div class="success-box">
    <div style="font-size:2rem;margin-bottom:.5rem">✅</div>
    <strong>Admin account created successfully!</strong><br>
    You can now <a href="/admin/login" style="color:#06b6d4">log in to the admin panel</a>.
    <br><br>
    <strong style="color:#fde047">⚠️ Delete this file from your server now!</strong><br>
    <small>Remove <code>setup.php</code> via FTP/cPanel File Manager for security.</small>
  </div>
  <?php else: ?>
  <?php if (!empty($errors)): ?>
  <div class="error-box"><ul><?php foreach($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
  <?php endif; ?>
  <div class="warn">⚠️ Delete <code>setup.php</code> from your server after completing setup.</div>
  <form method="POST">
    <div class="field">
      <label>Full Name</label>
      <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? 'Admin') ?>" required>
    </div>
    <div class="field">
      <label>Email Address</label>
      <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? 'admin@ttelectro.in') ?>" required>
    </div>
    <div class="field">
      <label>Password (min 8 characters)</label>
      <input type="password" name="password" required minlength="8">
    </div>
    <div class="field">
      <label>Confirm Password</label>
      <input type="password" name="confirm" required>
    </div>
    <button type="submit">Create Admin Account →</button>
  </form>
  <?php endif; ?>
</div>
</body>
</html>
