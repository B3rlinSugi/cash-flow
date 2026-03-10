<?php
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Edit Pengeluaran';
$pdo = getDB();

$id   = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM pengeluaran WHERE id=?");
$stmt->execute([$id]);
$data = $stmt->fetch();
if (!$data) { flash('error','Data tidak ditemukan.'); header('Location: /pengeluaran/index.php'); exit; }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama       = trim($_POST['nama'] ?? '');
    $jumlah     = (float)str_replace(['.', ','], ['', '.'], $_POST['jumlah'] ?? 0);
    $keterangan = trim($_POST['keterangan'] ?? '');
    $tanggal    = $_POST['tanggal'] ?? '';

    if (!$nama)       $errors[] = 'Nama pengeluaran wajib diisi.';
    if ($jumlah <= 0) $errors[] = 'Jumlah harus lebih dari 0.';
    if (!$tanggal)    $errors[] = 'Tanggal wajib diisi.';

    if (empty($errors)) {
        $pdo->prepare("UPDATE pengeluaran SET nama=?,jumlah=?,keterangan=?,tanggal=? WHERE id=?")
            ->execute([$nama, $jumlah, $keterangan, $tanggal, $id]);
        flash('success', 'Data pengeluaran berhasil diperbarui.');
        header('Location: /pengeluaran/index.php'); exit;
    }
    $data = array_merge($data, $_POST);
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex align-items-center mb-4">
    <a href="/pengeluaran/index.php" class="btn btn-sm btn-outline-secondary me-3"><i class="fas fa-arrow-left"></i></a>
    <h1 class="h4 mb-0 fw-bold text-dark"><i class="fas fa-edit me-2 text-warning"></i>Edit Pengeluaran</h1>
</div>

<?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div><?php endif; ?>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning py-3">
                <h6 class="m-0 fw-bold">Form Edit Pengeluaran</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Pengeluaran *</label>
                        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah (Rp) *</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" name="jumlah" id="jumlah" class="form-control" value="<?= number_format($data['jumlah'],0,',','.') ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal *</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= $data['tanggal'] ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" value="<?= htmlspecialchars($data['keterangan']) ?>">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning px-4"><i class="fas fa-save me-2"></i>Update</button>
                        <a href="/pengeluaran/index.php" class="btn btn-secondary px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$extraScript = '<script>
document.getElementById("jumlah").addEventListener("input", function(){
    let v = this.value.replace(/\D/g,"");
    this.value = v ? parseInt(v).toLocaleString("id-ID") : "";
});
</script>';
require_once __DIR__ . '/../includes/footer.php'; ?>
