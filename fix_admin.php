<?php
// File sementara untuk debug & reset admin — HAPUS setelah selesai
include 'config/koneksi.php';

$msg = '';

if (isset($_POST['reset'])) {
    $newPass = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $db->prepare("UPDATE users SET password = ? WHERE email = 'admin@cleanspace.com'");
    $stmt->bindValue(1, $newPass, SQLITE3_TEXT);
    $stmt->execute();
    $msg = 'Password admin berhasil direset ke: admin123';
}

$admin = $db->querySingle("SELECT id, nama, email, role FROM users WHERE email = 'admin@cleanspace.com'", true);
$total = $db->querySingle("SELECT COUNT(*) FROM users");
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Debug Admin</title>
<style>body{font-family:monospace;padding:2rem;background:#f1f5f9;}pre{background:#1e293b;color:#a3e635;padding:1rem;border-radius:8px;}button{padding:.5rem 1rem;background:#2563eb;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:1rem;}</style>
</head>
<body>
<h2>CleanSpace — Debug Admin</h2>

<?php if ($msg): ?>
<p style="color:green;font-weight:bold;"><?= htmlspecialchars($msg) ?></p>
<?php endif; ?>

<h3>Status Database</h3>
<pre>
Total users di DB : <?= (int)$total ?>

Admin account:
<?php if ($admin): ?>
  ID    : <?= $admin['id'] ?>
  Nama  : <?= htmlspecialchars($admin['nama']) ?>
  Email : <?= htmlspecialchars($admin['email']) ?>
  Role  : <?= htmlspecialchars($admin['role']) ?>
  Status: ADA ✓
<?php else: ?>
  Status: TIDAK ADA ✗
<?php endif; ?>
</pre>

<h3>Test Password Verify</h3>
<pre>
<?php
if ($admin) {
    $row = $db->querySingle("SELECT password FROM users WHERE email = 'admin@cleanspace.com'", true);
    $ok = password_verify('admin123', $row['password']);
    echo "password_verify('admin123', hash) = " . ($ok ? "TRUE ✓" : "FALSE ✗");
} else {
    echo "Admin tidak ditemukan.";
}
?>
</pre>

<form method="POST" style="margin-top:1rem;">
    <button type="submit" name="reset">Reset Password Admin → admin123</button>
</form>

<p style="margin-top:2rem;color:red;font-size:.8rem;">⚠️ Hapus file ini (fix_admin.php) setelah selesai!</p>
</body>
</html>
