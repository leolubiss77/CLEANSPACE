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
            $stmt->bindValue(1, $nama, SQLITE3_TEXT);
            $stmt->bindValue(2, $email, SQLITE3_TEXT);
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
    <title>Daftar | CleanSpace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="../style.css" rel="stylesheet">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">

        <div class="auth-logo-icon">
            <i class="bi bi-droplet-half"></i>
        </div>
        <h4 class="text-center fw-bold mb-1">Buat Akun Baru</h4>
        <p class="text-center text-muted small mb-4">Bergabung dengan CleanSpace hari ini</p>

        <?php if ($error): ?>
        <div class="alert alert-danger d-flex align-items-center gap-2 py-2">
            <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <div class="input-icon-wrapper">
                    <i class="bi bi-person input-icon"></i>
                    <input type="text" name="nama" class="form-control"
                           placeholder="Nama Anda"
                           value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>"
                           required autofocus>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-icon-wrapper">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email" name="email" class="form-control"
                           placeholder="nama@email.com"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-icon-wrapper">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" name="password" class="form-control"
                           placeholder="Minimal 6 karakter"
                           required>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Konfirmasi Password</label>
                <div class="input-icon-wrapper">
                    <i class="bi bi-lock-fill input-icon"></i>
                    <input type="password" name="confirm_password" class="form-control"
                           placeholder="Ulangi password"
                           required>
                </div>
            </div>
            <button type="submit" name="register" class="btn btn-primary w-100 py-2">
                <i class="bi bi-person-plus me-2"></i>Buat Akun
            </button>
        </form>

        <hr class="my-4">
        <p class="text-center text-muted small mb-0">
            Sudah punya akun?
            <a href="login.php" class="text-primary fw-semibold text-decoration-none">Masuk di sini</a>
        </p>
        <p class="text-center mt-2 mb-0">
            <a href="../index.php" class="text-muted small text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda
            </a>
        </p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
