<?php
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Edit Anggota';
$pdo = getDB();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM anggota WHERE id=?");
$stmt->execute([$id]);
$anggota = $stmt->fetch();
if (!$anggota) { flash('error','Data tidak ditemukan.'); header('Location: /anggota/index.php'); exit; }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama      = trim($_POST['nama'] ?? '');
    $alamat    = trim($_POST['alamat'] ?? '');
    $umur      = (int)($_POST['umur'] ?? 0);
    $level_kas = $_POST['level_kas'] ?? 'Reguler';
    $status    = $_POST['status'] ?? 'aktif';

    if (!$nama)  $errors[] = 'Nama wajib diisi.';
    if (!$alamat) $errors[] = 'Alamat wajib diisi.';
    if ($umur < 1 || $umur > 100) $errors[] = 'Umur tidak valid.';

    if (empty($errors)) {
        $pdo->prepare("UPDATE anggota SET nama=?,alamat=?,umur=?,level_kas=?,status=? WHERE id=?")
            ->execute([$nama, $alamat, $umur, $level_kas, $status, $id]);
        flash('success', "Data anggota $nama berhasil diperbarui.");
        header('Location: /anggota/index.php'); exit;
    }
    $anggota = array_merge($anggota, $_POST);
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex align-items-center mb-4">
    <a href="/anggota/index.php" class="btn btn-sm btn-outline-secondary me-3"><i class="fas fa-arrow-left"></i></a>
    <h1 class="h4 mb-0 fw-bold text-dark"><i class="fas fa-user-edit me-2 text-warning"></i>Edit Anggota</h1>
</div>

<?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div><?php endif; ?>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning py-3">
                <h6 class="m-0 fw-bold"><i class="fas fa-user-edit me-2"></i>Form Edit Anggota</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap *</label>
                        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($anggota['nama']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat *</label>
                        <textarea name="alamat" class="form-control" rows="2" required><?= htmlspecialchars($anggota['alamat']) ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Umur *</label>
                            <input type="number" name="umur" class="form-control" min="1" max="100" value="<?= $anggota['umur'] ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Level Kas</label>
                            <select name="level_kas" class="form-select">
                                <?php foreach (['Reguler','Silver','Gold','Platinum'] as $l): ?>
                                <option value="<?= $l ?>" <?= $anggota['level_kas']===$l ? 'selected':'' ?>><?= $l ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="aktif"     <?= $anggota['status']==='aktif'     ? 'selected':'' ?>>Aktif</option>
                            <option value="nonaktif"  <?= $anggota['status']==='nonaktif'  ? 'selected':'' ?>>Nonaktif</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning px-4"><i class="fas fa-save me-2"></i>Update</button>
                        <a href="/anggota/index.php" class="btn btn-secondary px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
