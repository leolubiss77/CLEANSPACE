<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/koneksi.php';

$success = '';
$error   = '';

if (isset($_POST['booking'])) {
    $user_id    = (int)$_SESSION['id'];
    $service_id = (int)($_POST['service_id'] ?? 0);
    $tanggal    = $_POST['tanggal'] ?? '';
    $jam        = $_POST['jam'] ?? '';
    $alamat     = trim($_POST['alamat'] ?? '');

    if (!$service_id || empty($tanggal) || empty($jam) || empty($alamat)) {
        $error = 'Semua kolom wajib diisi.';
    } else {
        $stmt = $db->prepare("
            INSERT INTO orders (user_id, service_id, tanggal, jam, alamat, status)
            VALUES (?, ?, ?, ?, ?, 'Menunggu Konfirmasi')
        ");
        $stmt->bindValue(1, $user_id,    SQLITE3_INTEGER);
        $stmt->bindValue(2, $service_id, SQLITE3_INTEGER);
        $stmt->bindValue(3, $tanggal,    SQLITE3_TEXT);
        $stmt->bindValue(4, $jam,        SQLITE3_TEXT);
        $stmt->bindValue(5, $alamat,     SQLITE3_TEXT);

        if ($stmt->execute()) {
            $success    = 'Booking berhasil! Pesanan Anda sedang menunggu konfirmasi admin.';
            $_POST      = [];
        } else {
            $error = 'Booking gagal. Silakan coba lagi.';
        }
    }
}

$pageTitle = 'Booking Layanan';
$basePath  = '../';
include '../config/header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="mb-4">
                <h1 class="fw-bold mb-1" style="font-size:1.5rem;">
                    <i class="bi bi-calendar-plus me-2 text-primary"></i>Booking Layanan Kebersihan
                </h1>
                <p class="text-muted small">Isi formulir di bawah untuk memesan layanan kami</p>
            </div>

            <?php if ($success): ?>
            <div class="alert alert-success d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill flex-shrink-0"></i>
                <div>
                    <strong>Berhasil!</strong> <?= htmlspecialchars($success) ?>
                    <div class="mt-1">
                        <a href="history.php" class="alert-link small">Lihat riwayat pesanan →</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($error): ?>
            <div class="alert alert-danger d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body p-4">
                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">Jenis Layanan</label>
                            <select name="service_id" class="form-select" required>
                                <option value="">-- Pilih Layanan --</option>
                                <?php
                                $services = $db->query("SELECT * FROM services ORDER BY nama_layanan");
                                while ($svc = $services->fetchArray(SQLITE3_ASSOC)):
                                    $sel = (isset($_POST['service_id']) && $_POST['service_id'] == $svc['id']) ? 'selected' : '';
                                ?>
                                <option value="<?= (int)$svc['id'] ?>" <?= $sel ?>>
                                    <?= htmlspecialchars($svc['nama_layanan']) ?>
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-sm-6">
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" class="form-control"
                                       min="<?= date('Y-m-d') ?>"
                                       value="<?= htmlspecialchars($_POST['tanggal'] ?? '') ?>"
                                       required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">Jam</label>
                                <input type="time" name="jam" class="form-control"
                                       value="<?= htmlspecialchars($_POST['jam'] ?? '') ?>"
                                       required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control" rows="3"
                                      placeholder="Masukkan alamat lengkap tempat layanan..."
                                      required><?= htmlspecialchars($_POST['alamat'] ?? '') ?></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" name="booking" class="btn btn-primary px-4">
                                <i class="bi bi-calendar-check me-2"></i>Pesan Sekarang
                            </button>
                            <a href="history.php" class="btn btn-outline-secondary">
                                <i class="bi bi-clock-history me-1"></i>Riwayat
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include '../config/footer.php'; ?>
