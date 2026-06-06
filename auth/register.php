<?php
session_start();

if (isset($_SESSION['id'])) {
    header("Location: ../user/booking.php");
    exit;
}

include '../config/koneksi.php';

$error = '';

if (isset($_POST['register'])) {
    $nama    = trim($_POST['nama'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $pass    = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (empty($nama) || empty($email) || empty($pass)) {
        $error = 'Semua kolom wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif (strlen($pass) < 6) {
        $error = 'Password minimal 6 karakter.';
    } elseif ($pass !== $confirm) {
        $error = 'Password dan konfirmasi password tidak cocok.';
    } else {
        $check = $db->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $check->bindValue(1, $email, SQLITE3_TEXT);
        if ($check->execute()->fetchArray()) {
            $error = 'Email sudah terdaftar. Gunakan email lain.';
        } else {
            $hashed = password_hash($pass, PASSWORD_DEFAULT);
            $stmt   = $db->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, 'user')");
            $stmt->bindValue(1, $nama,   SQLITE3_TEXT);
            $stmt->bindValue(2, $email,  SQLITE3_TEXT);
            $stmt->bindValue(3, $hashed, SQLITE3_TEXT);
            if ($stmt->execute()) {
                header("Location: login.php?registered=1");
                exit;
            } else {
                $error = 'Pendaftaran gagal. Silakan coba lagi.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar — CleanSpace</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="auth-page">

  <!-- Left panel -->
  <div class="auth-side-panel">
    <div class="auth-side-content">
      <div class="auth-side-logo">
        <div class="auth-side-logo-icon"><i class="bi bi-droplet-half"></i></div>
        CleanSpace
      </div>
      <h2 class="auth-side-title">Mulai perjalanan bersih Anda!</h2>
      <p class="auth-side-desc">
        Bergabung dengan 500+ pelanggan yang sudah mempercayakan kebersihan rumah mereka kepada kami.
      </p>
      <div class="auth-side-features">
        <div class="auth-feature-item">
          <div class="auth-feature-icon"><i class="bi bi-gift"></i></div>
          Registrasi gratis selamanya
        </div>
        <div class="auth-feature-item">
          <div class="auth-feature-icon"><i class="bi bi-people"></i></div>
          50+ petugas profesional terlatih
        </div>
        <div class="auth-feature-item">
          <div class="auth-feature-icon"><i class="bi bi-patch-check"></i></div>
          Garansi kepuasan atau ulangi gratis
        </div>
      </div>
    </div>
  </div>

  <!-- Right form -->
  <div class="auth-form-panel">
    <div class="auth-form-wrap">
      <div class="auth-form-logo">
        <div class="auth-form-logo-icon"><i class="bi bi-droplet-half"></i></div>
        CleanSpace
      </div>

      <h1 class="auth-form-title">Buat Akun Baru</h1>
      <p class="auth-form-subtitle">Sudah punya akun? <a href="login.php" style="color:var(--c-primary);font-weight:600;">Masuk di sini</a></p>

      <?php if ($error): ?>
      <div class="cs-alert cs-alert-danger mb-4">
        <i class="bi bi-exclamation-circle-fill"></i>
        <span><?= htmlspecialchars($error) ?></span>
      </div>
      <?php endif; ?>

      <form method="POST" novalidate id="regForm">
        <div class="form-field">
          <label for="nama">Nama Lengkap</label>
          <div class="input-wrap">
            <i class="bi bi-person input-icon"></i>
            <input type="text" id="nama" name="nama" class="form-input"
                   placeholder="Nama lengkap Anda"
                   value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>"
                   required autofocus>
          </div>
        </div>

        <div class="form-field">
          <label for="email">Email</label>
          <div class="input-wrap">
            <i class="bi bi-envelope input-icon"></i>
            <input type="email" id="email" name="email" class="form-input"
                   placeholder="nama@email.com"
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                   required>
          </div>
        </div>

        <div class="form-field">
          <label for="password">Password</label>
          <div class="input-wrap">
            <i class="bi bi-lock input-icon"></i>
            <input type="password" id="password" name="password" class="form-input"
                   placeholder="Minimal 6 karakter"
                   required oninput="checkStrength(this.value)">
            <button type="button" id="togglePwd"
                    style="position:absolute;right:.875rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--c-gray-light);padding:0;">
              <i class="bi bi-eye" id="eyeIcon"></i>
            </button>
          </div>
          <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
          <div class="strength-text" id="strengthText"></div>
        </div>

        <div class="form-field">
          <label for="confirm_password">Konfirmasi Password</label>
          <div class="input-wrap">
            <i class="bi bi-lock-fill input-icon"></i>
            <input type="password" id="confirm_password" name="confirm_password" class="form-input"
                   placeholder="Ulangi password" required>
          </div>
        </div>

        <div style="margin-bottom:1.5rem;">
          <label style="display:flex;align-items:flex-start;gap:.5rem;cursor:pointer;">
            <input type="checkbox" required
                   style="width:16px;height:16px;margin-top:.15rem;accent-color:var(--c-primary);flex-shrink:0;">
            <span style="font-size:var(--text-sm);color:var(--c-gray);font-weight:400;">
              Saya setuju dengan <a href="#" style="color:var(--c-primary);">Syarat Penggunaan</a>
              dan <a href="#" style="color:var(--c-primary);">Kebijakan Privasi</a>
            </span>
          </label>
        </div>

        <button type="submit" name="register" class="btn btn-primary-gradient w-100 btn-lg" style="justify-content:center;">
          <i class="bi bi-person-plus"></i> Buat Akun Gratis
        </button>
      </form>

      <div style="margin-top:2rem;padding-top:2rem;border-top:1px solid var(--c-border);text-align:center;">
        <a href="../index.php" style="font-size:var(--text-sm);color:var(--c-gray);display:inline-flex;align-items:center;gap:.4rem;">
          <i class="bi bi-arrow-left"></i> Kembali ke Beranda
        </a>
      </div>
    </div>
  </div>
</div>

<script>
// Toggle password visibility
const togglePwd = document.getElementById('togglePwd');
const pwdInput  = document.getElementById('password');
const eyeIcon   = document.getElementById('eyeIcon');
togglePwd.addEventListener('click', () => {
  const isText = pwdInput.type === 'text';
  pwdInput.type = isText ? 'password' : 'text';
  eyeIcon.className = isText ? 'bi bi-eye' : 'bi bi-eye-slash';
});

// Password strength
function checkStrength(val) {
  const fill = document.getElementById('strengthFill');
  const text = document.getElementById('strengthText');
  let score = 0;
  if (val.length >= 6)  score++;
  if (val.length >= 10) score++;
  if (/[A-Z]/.test(val)) score++;
  if (/[0-9]/.test(val)) score++;
  if (/[^A-Za-z0-9]/.test(val)) score++;

  const levels = [
    { pct: '0%',   color: '',          label: '' },
    { pct: '25%',  color: '#ef4444',   label: 'Sangat lemah' },
    { pct: '50%',  color: '#f97316',   label: 'Lemah' },
    { pct: '75%',  color: '#f59e0b',   label: 'Sedang' },
    { pct: '90%',  color: '#10b981',   label: 'Kuat' },
    { pct: '100%', color: '#059669',   label: 'Sangat kuat' },
  ];

  const lv = levels[Math.min(score, 5)];
  fill.style.width = val.length === 0 ? '0%' : lv.pct;
  fill.style.background = lv.color;
  text.textContent = val.length === 0 ? '' : lv.label;
  text.style.color = lv.color;
}
</script>
</body>
</html>
