<?php
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Edit Kas';
$pdo = getDB();

$id  = (int)($_GET['id'] ?? 0);
$kas = $pdo->prepare("SELECT k.*, a.nama AS nama_anggota FROM kas k JOIN anggota a ON k.anggota_id=a.id WHERE k.id=?");
$kas->execute([$id]);
$kas = $kas->fetch();
if (!$kas) { flash('error','Data tidak ditemukan.'); header('Location: /kas/index.php'); exit; }

$anggotaList = $pdo->query("SELECT id, nama FROM anggota WHERE status='aktif' ORDER BY nama")->fetchAll();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $anggota_id = (int)$_POST['anggota_id'];
    $jumlah     = (float)str_replace(['.', ','], ['', '.'], $_POST['jumlah'] ?? 0);
    $keterangan = trim($_POST['keterangan'] ?? '');
    $tanggal    = $_POST['tanggal'] ?? '';

    if (!$anggota_id) $errors[] = 'Pilih anggota.';
    if ($jumlah <= 0) $errors[] = 'Jumlah harus lebih dari 0.';
    if (!$tanggal)    $errors[] = 'Tanggal wajib diisi.';

    if (empty($errors)) {
        $pdo->prepare("UPDATE kas SET anggota_id=?, jumlah=?, keterangan=?, tanggal=? WHERE id=?")
            ->execute([$anggota_id, $jumlah, $keterangan, $tanggal, $id]);
        flash('success', 'Data kas berhasil diperbarui.');
        header('Location: /kas/index.php'); exit;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex align-items-center mb-4">
    <a href="/kas/index.php" class="btn btn-sm btn-outline-secondary me-3"><i class="fas fa-arrow-left"></i></a>
    <h1 class="h4 mb-0 fw-bold text-dark"><i class="fas fa-edit me-2 text-warning"></i>Edit Kas Masuk</h1>
</div>

<?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div><?php endif; ?>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning py-3">
                <h6 class="m-0 fw-bold"><i class="fas fa-edit me-2"></i>Form Edit Kas</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Anggota <span class="text-danger">*</span></label>
                        <select name="anggota_id" class="form-select" required>
                            <option value="">-- Pilih Anggota --</option>
                            <?php foreach ($anggotaList as $a): ?>
                            <option value="<?= $a['id'] ?>" <?= ($kas['anggota_id'] == $a['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($a['nama']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" name="jumlah" id="jumlah" class="form-control"
                                   value="<?= number_format($kas['jumlah'], 0, ',', '.') ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control" value="<?= $kas['tanggal'] ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" value="<?= htmlspecialchars($kas['keterangan']) ?>">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning px-4"><i class="fas fa-save me-2"></i>Update</button>
                        <a href="/kas/index.php" class="btn btn-secondary px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$extraScript = '<script>
document.getElementById("jumlah").addEventListener("input", function(){
    let val = this.value.replace(/\D/g,"");
    this.value = val ? parseInt(val).toLocaleString("id-ID") : "";
});
</script>';
require_once __DIR__ . '/../includes/footer.php'; ?>
