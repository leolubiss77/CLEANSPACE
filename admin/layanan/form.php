<?php
$basePath    = '../../';
$currentPage = 'layanan';
$editMode    = false;
$data        = ['nama_layanan'=>'','harga'=>'','deskripsi'=>''];

include '../layout/header.php';

$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $row = $db->querySingle("SELECT * FROM services WHERE id = $id", true);
    if ($row) { $editMode = true; $data = $row; }
}

$pageTitle = $editMode ? 'Edit Layanan' : 'Tambah Layanan';
$error = '';

if (isset($_POST['save'])) {
    $nama  = trim($_POST['nama_layanan'] ?? '');
    $harga = (int)($_POST['harga'] ?? 0);
    $desk  = trim($_POST['deskripsi'] ?? '');

    if (empty($nama) || $harga <= 0) {
        $error = 'Nama layanan dan harga wajib diisi.';
    } else {
        if ($editMode) {
            $stmt = $db->prepare("UPDATE services SET nama_layanan=?,harga=?,deskripsi=? WHERE id=?");
            $stmt->bindValue(1,$nama,  SQLITE3_TEXT);
            $stmt->bindValue(2,$harga, SQLITE3_INTEGER);
            $stmt->bindValue(3,$desk,  SQLITE3_TEXT);
            $stmt->bindValue(4,$id,    SQLITE3_INTEGER);
        } else {
            $stmt = $db->prepare("INSERT INTO services (nama_layanan,harga,deskripsi) VALUES (?,?,?)");
            $stmt->bindValue(1,$nama,  SQLITE3_TEXT);
            $stmt->bindValue(2,$harga, SQLITE3_INTEGER);
            $stmt->bindValue(3,$desk,  SQLITE3_TEXT);
        }
        $stmt->execute();
        header("Location: index.php?saved=1"); exit;
    }
    $data = ['nama_layanan'=>$nama,'harga'=>$harga,'deskripsi'=>$desk];
}
?>

<div style="max-width:560px;">
  <div style="margin-bottom:1.25rem;">
    <a href="index.php" class="btn btn-outline btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
  </div>

  <?php if ($error): ?>
  <div class="admin-alert alert-danger" style="margin-bottom:1.25rem;"><i class="bi bi-exclamation-circle-fill"></i><span><?= htmlspecialchars($error) ?></span></div>
  <?php endif; ?>

  <div class="admin-card">
    <div class="admin-card-header">
      <div class="admin-card-title">
        <i class="bi bi-<?= $editMode ? 'pencil' : 'plus-circle' ?>"></i>
        <?= $editMode ? 'Edit Layanan' : 'Tambah Layanan Baru' ?>
      </div>
    </div>
    <div class="admin-card-body">
      <form method="POST" style="display:flex;flex-direction:column;gap:1.1rem;">

        <div>
          <label class="admin-form-label">Nama Layanan <span style="color:#ef4444;">*</span></label>
          <input type="text" name="nama_layanan" class="admin-input"
                 value="<?= htmlspecialchars($data['nama_layanan']) ?>"
                 placeholder="contoh: Deep Cleaning" required>
        </div>

        <div>
          <label class="admin-form-label">Harga (Rp) <span style="color:#ef4444;">*</span></label>
          <input type="number" name="harga" class="admin-input" min="0"
                 value="<?= (int)($data['harga'] ?? 0) ?>"
                 placeholder="contoh: 250000" required>
        </div>

        <div>
          <label class="admin-form-label">Deskripsi</label>
          <textarea name="deskripsi" class="admin-input" rows="3"
                    style="resize:vertical;"
                    placeholder="Deskripsi singkat layanan..."><?= htmlspecialchars($data['deskripsi']) ?></textarea>
        </div>

        <div style="display:flex;gap:.75rem;padding-top:.5rem;">
          <button type="submit" name="save" class="btn btn-primary">
            <i class="bi bi-<?= $editMode ? 'check-lg' : 'plus-lg' ?>"></i>
            <?= $editMode ? 'Simpan Perubahan' : 'Tambah Layanan' ?>
          </button>
          <a href="index.php" class="btn btn-outline">Batal</a>
        </div>

      </form>
    </div>
  </div>
</div>

<?php include '../layout/footer.php'; ?>
