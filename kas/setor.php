<?php
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Setor Kas';
$pdo = getDB();

// Ambil daftar anggota aktif
$anggotaList = $pdo->query("SELECT id, nama, level_kas FROM anggota WHERE status='aktif' ORDER BY nama")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $anggota_id  = (int)($_POST['anggota_id'] ?? 0);
    $jumlah      = (float)str_replace(['.', ','], ['', '.'], $_POST['jumlah'] ?? 0);
    $keterangan  = trim($_POST['keterangan'] ?? '');
    $tanggal     = $_POST['tanggal'] ?? date('Y-m-d');

    $errors = [];
    if (!$anggota_id)      $errors[] = 'Pilih anggota terlebih dahulu.';
    if ($jumlah <= 0)      $errors[] = 'Jumlah harus lebih dari 0.';
    if (!$tanggal)         $errors[] = 'Tanggal wajib diisi.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO kas (anggota_id, jumlah, keterangan, tanggal) VALUES (?,?,?,?)");
        $stmt->execute([$anggota_id, $jumlah, $keterangan, $tanggal]);
        flash('success', 'Kas berhasil disimpan!');
        header('Location: /kas/index.php');
        exit;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex align-items-center mb-4">
    <a href="/kas/index.php" class="btn btn-sm btn-outline-secondary me-3"><i class="fas fa-arrow-left"></i></a>
    <h1 class="h4 mb-0 fw-bold text-dark"><i class="fas fa-plus-circle me-2 text-success"></i>Setor Kas</h1>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success text-white py-3">
                <h6 class="m-0 fw-bold"><i class="fas fa-wallet me-2"></i>Form Setor Kas</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" novalidate>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Anggota <span class="text-danger">*</span></label>
                        <select name="anggota_id" class="form-select" required>
                            <option value="">-- Pilih Anggota --</option>
                            <?php foreach ($anggotaList as $a): ?>
                            <option value="<?= $a['id'] ?>" <?= (($_POST['anggota_id'] ?? '') == $a['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($a['nama']) ?> (<?= $a['level_kas'] ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" name="jumlah" id="jumlah" class="form-control"
                                   placeholder="0" value="<?= htmlspecialchars($_POST['jumlah'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control"
                               value="<?= htmlspecialchars($_POST['tanggal'] ?? date('Y-m-d')) ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control"
                               placeholder="Contoh: Kas bulan Januari"
                               value="<?= htmlspecialchars($_POST['keterangan'] ?? '') ?>">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success px-4"><i class="fas fa-save me-2"></i>Simpan</button>
                        <a href="/kas/index.php" class="btn btn-secondary px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$extraScript = '
<script>
// Format angka dengan titik ribuan
document.getElementById("jumlah").addEventListener("input", function(){
    let val = this.value.replace(/\D/g,"");
    this.value = val ? parseInt(val).toLocaleString("id-ID") : "";
});
</script>';
require_once __DIR__ . '/../includes/footer.php';
?>
