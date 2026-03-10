<?php
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Catat Pengeluaran';
$pdo = getDB();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama       = trim($_POST['nama'] ?? '');
    $jumlah     = (float)str_replace(['.', ','], ['', '.'], $_POST['jumlah'] ?? 0);
    $keterangan = trim($_POST['keterangan'] ?? '');
    $tanggal    = $_POST['tanggal'] ?? date('Y-m-d');

    if (!$nama)        $errors[] = 'Nama pengeluaran wajib diisi.';
    if ($jumlah <= 0)  $errors[] = 'Jumlah harus lebih dari 0.';
    if (!$tanggal)     $errors[] = 'Tanggal wajib diisi.';

    if (empty($errors)) {
        $pdo->prepare("INSERT INTO pengeluaran (nama, jumlah, keterangan, tanggal) VALUES (?,?,?,?)")
            ->execute([$nama, $jumlah, $keterangan, $tanggal]);
        flash('success', 'Pengeluaran berhasil dicatat.');
        header('Location: /pengeluaran/index.php'); exit;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex align-items-center mb-4">
    <a href="/pengeluaran/index.php" class="btn btn-sm btn-outline-secondary me-3"><i class="fas fa-arrow-left"></i></a>
    <h1 class="h4 mb-0 fw-bold text-dark"><i class="fas fa-plus-circle me-2 text-danger"></i>Catat Pengeluaran</h1>
</div>

<?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div><?php endif; ?>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-danger text-white py-3">
                <h6 class="m-0 fw-bold"><i class="fas fa-minus-circle me-2"></i>Form Pengeluaran</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Pengeluaran <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: Pembelian ATK" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" name="jumlah" id="jumlah" class="form-control" placeholder="0" value="<?= htmlspecialchars($_POST['jumlah'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control" value="<?= htmlspecialchars($_POST['tanggal'] ?? date('Y-m-d')) ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="Keterangan tambahan (opsional)" value="<?= htmlspecialchars($_POST['keterangan'] ?? '') ?>">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger px-4"><i class="fas fa-save me-2"></i>Simpan</button>
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
