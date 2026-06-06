<?php
session_start();

if (isset($_SESSION['id'])) {
    $redirect = ($_SESSION['role'] === 'admin') ? '../admin/dashboard.php' : '../user/dashboard.php';
    header("Location: $redirect");
    exit;
}

include '../config/koneksi.php';

$error   = '';
$success = isset($_GET['registered']) ? 'Akun berhasil dibuat! Silakan masuk.' : '';

if (isset($_POST['login'])) {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->bindValue(1, $email, SQLITE3_TEXT);
    $user = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['id']   = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];
        $redirect = ($user['role'] === 'admin') ? '../admin/dashboard.php' : '../user/dashboard.php';
        header("Location: $redirect");
        exit;
    } else {
        $error = 'Email atau password salah. Silakan periksa kembali.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk — CleanSpace</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
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
      <h2 class="auth-side-title">Selamat datang kembali!</h2>
      <p class="auth-side-desc">
        Masuk ke akun Anda dan kelola layanan kebersihan dengan mudah kapan saja.
      </p>
      <div class="auth-side-features">
        <div class="auth-feature-item">
          <div class="auth-feature-icon"><i class="bi bi-calendar2-check"></i></div>
          Pesan layanan kapan saja
        </div>
        <div class="auth-feature-item">
          <div class="auth-feature-icon"><i class="bi bi-clock-history"></i></div>
          Pantau status pesanan real-time
        </div>
        <div class="auth-feature-item">
          <div class="auth-feature-icon"><i class="bi bi-shield-check"></i></div>
          Garansi kepuasan 100%
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

      <h1 class="auth-form-title">Masuk ke Akun</h1>
      <p class="auth-form-subtitle">Belum punya akun? <a href="register.php" style="color:var(--c-primary);font-weight:600;">Daftar gratis</a></p>

      <?php if ($success): ?>
      <div class="cs-alert cs-alert-success mb-4">
        <i class="bi bi-check-circle-fill"></i>
        <span><?= htmlspecialchars($success) ?></span>
      </div>
      <?php endif; ?>

      <?php if ($error): ?>
      <div class="cs-alert cs-alert-danger mb-4">
        <i class="bi bi-exclamation-circle-fill"></i>
        <span><?= htmlspecialchars($error) ?></span>
      </div>
      <?php endif; ?>

      <form method="POST" novalidate>
        <div class="form-field">
          <label for="email">Email</label>
          <div class="input-wrap">
            <i class="bi bi-envelope input-icon"></i>
            <input type="email" id="email" name="email" class="form-input"
                   placeholder="nama@email.com"
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                   required autofocus>
          </div>
        </div>

        <div class="form-field">
          <label for="password" style="display:flex;justify-content:space-between;">
            <span>Password</span>
            <a href="#" style="font-weight:500;color:var(--c-primary);font-size:.75rem;text-transform:none;letter-spacing:0;">Lupa password?</a>
          </label>
          <div class="input-wrap">
            <i class="bi bi-lock input-icon"></i>
            <input type="password" id="password" name="password" class="form-input"
                   placeholder="Masukkan password" required>
            <button type="button" id="togglePwd"
                    style="position:absolute;right:.875rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--c-gray-light);padding:0;">
              <i class="bi bi-eye" id="eyeIcon"></i>
            </button>
          </div>
        </div>

        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.5rem;">
          <input type="checkbox" id="remember" name="remember"
                 style="width:16px;height:16px;accent-color:var(--c-primary);cursor:pointer;">
          <label for="remember" style="font-size:var(--text-sm);color:var(--c-gray);cursor:pointer;font-weight:400;">
            Ingat saya selama 30 hari
          </label>
        </div>

        <button type="submit" name="login" class="btn btn-primary-gradient w-100 btn-lg" style="justify-content:center;">
          <i class="bi bi-box-arrow-in-right"></i> Masuk
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
const togglePwd = document.getElementById('togglePwd');
const pwdInput  = document.getElementById('password');
const eyeIcon   = document.getElementById('eyeIcon');

togglePwd.addEventListener('click', () => {
  const isText = pwdInput.type === 'text';
  pwdInput.type = isText ? 'password' : 'text';
  eyeIcon.className = isText ? 'bi bi-eye' : 'bi bi-eye-slash';
});
</script>
</body>
</html>
