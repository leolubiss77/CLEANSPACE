<?php
session_start();

if (isset($_SESSION['id'])) {
    $redirect = ($_SESSION['role'] === 'admin') ? '../admin/dashboard.php' : '../user/booking.php';
    header("Location: $redirect");
    exit;
}

include '../config/koneksi.php';

$error   = '';
$success = isset($_GET['registered']) ? 'Akun berhasil dibuat! Silakan masuk dengan akun Anda.' : '';

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

        $redirect = ($user['role'] === 'admin') ? '../admin/dashboard.php' : '../user/booking.php';
        header("Location: $redirect");
        exit;
    } else {
        $error = 'Email atau password salah. Silakan coba lagi.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | CleanSpace</title>
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
        <h4 class="text-center fw-bold mb-1">Selamat Datang</h4>
        <p class="text-center text-muted small mb-4">Masuk ke akun CleanSpace Anda</p>

        <?php if ($success): ?>
        <div class="alert alert-success d-flex align-items-center gap-2 py-2">
            <i class="bi bi-check-circle-fill flex-shrink-0"></i>
            <span><?= htmlspecialchars($success) ?></span>
        </div>
        <?php endif; ?>

        <?php if ($error): ?>
        <div class="alert alert-danger d-flex align-items-center gap-2 py-2">
            <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-icon-wrapper">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email" name="email" class="form-control"
                           placeholder="nama@email.com"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           required autofocus>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-icon-wrapper">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" name="password" class="form-control"
                           placeholder="Masukkan password"
                           required>
                </div>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100 py-2">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
            </button>
        </form>

        <hr class="my-4">
        <p class="text-center text-muted small mb-0">
            Belum punya akun?
            <a href="register.php" class="text-primary fw-semibold text-decoration-none">Daftar sekarang</a>
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
