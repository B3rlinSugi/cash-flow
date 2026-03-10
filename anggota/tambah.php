<?php
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Tambah Anggota';
$pdo = getDB();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama      = trim($_POST['nama'] ?? '');
    $alamat    = trim($_POST['alamat'] ?? '');
    $umur      = (int)($_POST['umur'] ?? 0);
    $level_kas = $_POST['level_kas'] ?? 'Reguler';
    $status    = $_POST['status'] ?? 'aktif';

    if (!$nama)            $errors[] = 'Nama wajib diisi.';
    if (!$alamat)          $errors[] = 'Alamat wajib diisi.';
    if ($umur < 1 || $umur > 100) $errors[] = 'Umur tidak valid.';

    if (empty($errors)) {
        $pdo->prepare("INSERT INTO anggota (nama, alamat, umur, level_kas, status) VALUES (?,?,?,?,?)")
            ->execute([$nama, $alamat, $umur, $level_kas, $status]);
        flash('success', "Anggota $nama berhasil ditambahkan.");
        header('Location: /anggota/index.php'); exit;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex align-items-center mb-4">
    <a href="/anggota/index.php" class="btn btn-sm btn-outline-secondary me-3"><i class="fas fa-arrow-left"></i></a>
    <h1 class="h4 mb-0 fw-bold text-dark"><i class="fas fa-user-plus me-2 text-info"></i>Tambah Anggota</h1>
</div>

<?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div><?php endif; ?>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-info text-white py-3">
                <h6 class="m-0 fw-bold"><i class="fas fa-user-plus me-2"></i>Form Tambah Anggota</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" placeholder="Nama anggota" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat <span class="text-danger">*</span></label>
                        <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap" required><?= htmlspecialchars($_POST['alamat'] ?? '') ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Umur <span class="text-danger">*</span></label>
                            <input type="number" name="umur" class="form-control" min="1" max="100" value="<?= htmlspecialchars($_POST['umur'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Level Kas</label>
                            <select name="level_kas" class="form-select">
                                <?php foreach (['Reguler','Silver','Gold','Platinum'] as $l): ?>
                                <option value="<?= $l ?>" <?= (($_POST['level_kas'] ?? 'Reguler') === $l) ? 'selected' : '' ?>><?= $l ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="aktif" <?= (($_POST['status'] ?? 'aktif') === 'aktif') ? 'selected' : '' ?>>Aktif</option>
                            <option value="nonaktif" <?= (($_POST['status'] ?? '') === 'nonaktif') ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-info text-white px-4"><i class="fas fa-save me-2"></i>Simpan</button>
                        <a href="/anggota/index.php" class="btn btn-secondary px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
