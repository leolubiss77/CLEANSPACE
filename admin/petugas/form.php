<?php
$basePath    = '../../';
$currentPage = 'petugas';
$editMode    = false;
$data        = ['nama_petugas'=>'','nomor_hp'=>'','status'=>'Available'];

include '../layout/header.php';

$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $row = $db->querySingle("SELECT * FROM workers WHERE id = $id", true);
    if ($row) { $editMode = true; $data = $row; }
}

$pageTitle = $editMode ? 'Edit Petugas' : 'Tambah Petugas';
$error = '';

if (isset($_POST['save'])) {
    $nama   = trim($_POST['nama_petugas'] ?? '');
    $hp     = trim($_POST['nomor_hp'] ?? '');
    $status = $_POST['status'] ?? 'Available';

    if (empty($nama)) {
        $error = 'Nama petugas wajib diisi.';
    } else {
        if ($editMode) {
            $stmt = $db->prepare("UPDATE workers SET nama_petugas=?,nomor_hp=?,status=? WHERE id=?");
            $stmt->bindValue(1,$nama,  SQLITE3_TEXT);
            $stmt->bindValue(2,$hp,    SQLITE3_TEXT);
            $stmt->bindValue(3,$status,SQLITE3_TEXT);
            $stmt->bindValue(4,$id,    SQLITE3_INTEGER);
        } else {
            $stmt = $db->prepare("INSERT INTO workers (nama_petugas,nomor_hp,status) VALUES (?,?,?)");
            $stmt->bindValue(1,$nama,  SQLITE3_TEXT);
            $stmt->bindValue(2,$hp,    SQLITE3_TEXT);
            $stmt->bindValue(3,$status,SQLITE3_TEXT);
        }
        $stmt->execute();
        header("Location: index.php?saved=1"); exit;
    }
    $data = ['nama_petugas'=>$nama,'nomor_hp'=>$hp,'status'=>$status];
}
?>

<div style="max-width:500px;">
  <div style="margin-bottom:1.25rem;">
    <a href="index.php" class="btn btn-outline btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
  </div>

  <?php if ($error): ?>
  <div class="admin-alert alert-danger" style="margin-bottom:1.25rem;"><i class="bi bi-exclamation-circle-fill"></i><span><?= htmlspecialchars($error) ?></span></div>
  <?php endif; ?>

  <div class="admin-card">
    <div class="admin-card-header">
      <div class="admin-card-title">
        <i class="bi bi-person-badge"></i>
        <?= $editMode ? 'Edit Petugas' : 'Tambah Petugas Baru' ?>
      </div>
    </div>
    <div class="admin-card-body">
      <form method="POST" style="display:flex;flex-direction:column;gap:1.1rem;">

        <div>
          <label class="admin-form-label">Nama Petugas <span style="color:#ef4444;">*</span></label>
          <input type="text" name="nama_petugas" class="admin-input"
                 value="<?= htmlspecialchars($data['nama_petugas']) ?>"
                 placeholder="Nama lengkap petugas" required>
        </div>

        <div>
          <label class="admin-form-label">No. HP / WhatsApp</label>
          <input type="text" name="nomor_hp" class="admin-input"
                 value="<?= htmlspecialchars($data['nomor_hp'] ?? '') ?>"
                 placeholder="contoh: 08123456789">
        </div>

        <div>
          <label class="admin-form-label">Status</label>
          <select name="status" class="admin-input">
            <option value="Available"    <?= $data['status']==='Available'    ? 'selected':'' ?>>Available</option>
            <option value="Tidak Aktif"  <?= $data['status']==='Tidak Aktif'  ? 'selected':'' ?>>Tidak Aktif</option>
          </select>
        </div>

        <div style="display:flex;gap:.75rem;padding-top:.5rem;">
          <button type="submit" name="save" class="btn btn-primary">
            <i class="bi bi-<?= $editMode ? 'check-lg' : 'plus-lg' ?>"></i>
            <?= $editMode ? 'Simpan Perubahan' : 'Tambah Petugas' ?>
          </button>
          <a href="index.php" class="btn btn-outline">Batal</a>
        </div>

      </form>
    </div>
  </div>
</div>

<?php include '../layout/footer.php'; ?>
