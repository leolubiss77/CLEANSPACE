<?php
// File sementara untuk debug & reset admin — HAPUS setelah selesai
include 'config/koneksi.php';

$msg = '';

if (isset($_POST['reset'])) {
    $newPass = password_hash('admin123', PASSWORD_DEFAULT);
    // Coba INSERT dulu, kalau sudah ada baru UPDATE
    $ins = $db->prepare("INSERT OR IGNORE INTO users (nama, email, password, role) VALUES ('Admin', 'admin@cleanspace.com', ?, 'admin')");
    $ins->bindValue(1, $newPass, SQLITE3_TEXT);
    $ins->execute();

    $upd = $db->prepare("UPDATE users SET password = ?, role = 'admin' WHERE email = 'admin@cleanspace.com'");
    $upd->bindValue(1, $newPass, SQLITE3_TEXT);
    $upd->execute();

    $msg = 'Admin berhasil dibuat/direset! Email: admin@cleanspace.com | Password: admin123';
}

$admin = $db->querySingle("SELECT id, nama, email, role FROM users WHERE email = 'admin@cleanspace.com'", true);
$total = $db->querySingle("SELECT COUNT(*) FROM users");

// Cek DB path
$dbPath = getenv('RAILWAY_ENVIRONMENT') !== false ? '/tmp/cleanspace.db' : __DIR__ . '/database/cleanspace.db';
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Debug Admin</title>
<style>
body{font-family:monospace;padding:2rem;background:#f1f5f9;}
pre{background:#1e293b;color:#a3e635;padding:1rem;border-radius:8px;white-space:pre-wrap;}
button{padding:.75rem 1.5rem;background:#16a34a;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:1rem;font-weight:bold;}
.ok{color:#16a34a;font-weight:bold;} .err{color:#dc2626;font-weight:bold;}
</style>
</head>
<body>
<h2>CleanSpace — Debug Admin</h2>

<?php if ($msg): ?>
<p class="ok">✓ <?= htmlspecialchars($msg) ?></p>
<?php endif; ?>

<pre>
DB Path             : <?= $dbPath ?>

RAILWAY_ENVIRONMENT : <?= getenv('RAILWAY_ENVIRONMENT') ?: '(tidak ada)' ?>

Total users di DB   : <?= (int)$total ?>

Admin account       : <?= $admin ? 'ADA ✓' : 'TIDAK ADA ✗' ?>
<?php if ($admin): ?>
  ID   : <?= $admin['id'] ?>

  Role : <?= htmlspecialchars($admin['role']) ?>

<?php
    $row = $db->querySingle("SELECT password FROM users WHERE email = 'admin@cleanspace.com'", true);
    $ok  = password_verify('admin123', $row['password']);
    echo "  password_verify : " . ($ok ? "TRUE ✓" : "FALSE ✗");
?>

<?php endif; ?>
</pre>

<form method="POST" style="margin-top:1.5rem;">
    <button type="submit" name="reset">⚡ Buat / Reset Admin → admin123</button>
</form>

<p style="margin-top:2rem;color:#dc2626;font-size:.85rem;">⚠️ Hapus file ini setelah selesai!</p>
</body>
</html>
