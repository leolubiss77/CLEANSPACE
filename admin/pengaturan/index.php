<?php
$basePath    = '../../';
$currentPage = 'pengaturan';
$pageTitle   = 'Pengaturan';
include '../layout/header.php';

$flash = '';
$flashType = 'success';

// Ganti password
if (isset($_POST['ganti_password'])) {
    $passwordLama = $_POST['password_lama'] ?? '';
    $passwordBaru = $_POST['password_baru'] ?? '';
    $konfirmasi   = $_POST['konfirmasi']    ?? '';

    $adminId = (int)$_SESSION['id'];
    $row = $db->querySingle("SELECT password FROM users WHERE id = $adminId", true);

    if (!password_verify($passwordLama, $row['password'])) {
        $flash = 'Password lama tidak sesuai.';
        $flashType = 'danger';
    } elseif (strlen($passwordBaru) < 6) {
        $flash = 'Password baru minimal 6 karakter.';
        $flashType = 'danger';
    } elseif ($passwordBaru !== $konfirmasi) {
        $flash = 'Konfirmasi password tidak cocok.';
        $flashType = 'danger';
    } else {
        $hash = password_hash($passwordBaru, PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bindValue(1, $hash,    SQLITE3_TEXT);
        $stmt->bindValue(2, $adminId, SQLITE3_INTEGER);
        $stmt->execute();
        $flash = 'Password berhasil diperbarui.';
    }
}

// Update nama admin
if (isset($_POST['update_profil'])) {
    $namaBaru = trim($_POST['nama'] ?? '');
    if (!empty($namaBaru)) {
        $stmt = $db->prepare("UPDATE users SET nama = ? WHERE id = ?");
        $stmt->bindValue(1, $namaBaru,         SQLITE3_TEXT);
        $stmt->bindValue(2, (int)$_SESSION['id'], SQLITE3_INTEGER);
        $stmt->execute();
        $_SESSION['nama'] = $namaBaru;
        $flash = 'Profil berhasil diperbarui.';
    }
}
?>

<div style="max-width:600px;display:flex;flex-direction:column;gap:1.5rem;">

<?php if ($flash): ?>
<div class="admin-alert alert-<?= $flashType ?>"><i class="bi bi-<?= $flashType==='success'?'check-circle-fill':'exclamation-circle-fill' ?>"></i><span><?= htmlspecialchars($flash) ?></span></div>
<?php endif; ?>

  <!-- Profil Admin -->
  <div class="admin-card">
    <div class="admin-card-header"><div class="admin-card-title"><i class="bi bi-person-circle"></i> Profil Admin</div></div>
    <div class="admin-card-body">
      <form method="POST" style="display:flex;flex-direction:column;gap:1rem;">
        <div>
          <label class="admin-form-label">Nama Tampil</label>
          <input type="text" name="nama" class="admin-input"
                 value="<?= htmlspecialchars($_SESSION['nama'] ?? '') ?>"
                 placeholder="Nama admin" required>
        </div>
        <div>
          <label class="admin-form-label">Email</label>
          <input type="text" class="admin-input" value="admin@cleanspace.com" disabled
                 style="opacity:.6;cursor:not-allowed;">
          <div style="font-size:.72rem;color:var(--admin-muted);margin-top:.3rem;">Email tidak dapat diubah.</div>
        </div>
        <div>
          <button type="submit" name="update_profil" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Simpan Profil
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Ganti Password -->
  <div class="admin-card">
    <div class="admin-card-header"><div class="admin-card-title"><i class="bi bi-shield-lock"></i> Ganti Password</div></div>
    <div class="admin-card-body">
      <form method="POST" style="display:flex;flex-direction:column;gap:1rem;" autocomplete="off">

        <div>
          <label class="admin-form-label">Password Lama <span style="color:#ef4444;">*</span></label>
          <input type="password" name="password_lama" class="admin-input" placeholder="Masukkan password lama" required>
        </div>

        <div>
          <label class="admin-form-label">Password Baru <span style="color:#ef4444;">*</span></label>
          <input type="password" name="password_baru" class="admin-input" placeholder="Minimal 6 karakter" required>
        </div>

        <div>
          <label class="admin-form-label">Konfirmasi Password Baru <span style="color:#ef4444;">*</span></label>
          <input type="password" name="konfirmasi" class="admin-input" placeholder="Ulangi password baru" required>
        </div>

        <div>
          <button type="submit" name="ganti_password" class="btn btn-primary">
            <i class="bi bi-lock"></i> Perbarui Password
          </button>
        </div>

      </form>
    </div>
  </div>

  <!-- Info Sistem -->
  <div class="admin-card">
    <div class="admin-card-header"><div class="admin-card-title"><i class="bi bi-info-circle"></i> Informasi Sistem</div></div>
    <div class="admin-card-body">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;font-size:.845rem;">
        <?php foreach ([
          'Nama Aplikasi' => 'CleanSpace',
          'Versi'         => '1.0.0',
          'PHP'           => PHP_VERSION,
          'Database'      => 'SQLite 3',
          'Dibuat oleh'   => 'Tim CleanSpace',
          'Login sebagai' => htmlspecialchars($_SESSION['nama'] ?? ''),
        ] as $k => $v): ?>
        <div>
          <div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--admin-muted);margin-bottom:.2rem;"><?= $k ?></div>
          <div style="font-weight:600;"><?= $v ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

</div>

<?php include '../layout/footer.php'; ?>
