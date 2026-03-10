<?php
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Kas Ditunda';
$pdo = getDB();

// Tandai lunas
if (isset($_GET['lunas'])) {
    $id = (int)$_GET['lunas'];
    $row = $pdo->prepare("SELECT * FROM kas_ditunda WHERE id=?");
    $row->execute([$id]); $row = $row->fetch();
    if ($row) {
        $pdo->prepare("INSERT INTO kas (anggota_id,jumlah,keterangan,tanggal) VALUES (?,?,?,?)")
            ->execute([$row['anggota_id'], $row['jumlah'], 'Pelunasan: '.($row['keterangan']?:'kas ditunda'), date('Y-m-d')]);
        $pdo->prepare("UPDATE kas_ditunda SET status='selesai' WHERE id=?")->execute([$id]);
        flash('success', 'Kas berhasil dilunasi dan dipindahkan ke kas masuk.');
    }
    header('Location: /kas/ditunda.php'); exit;
}

// Hapus
if (isset($_GET['hapus'])) {
    $pdo->prepare("DELETE FROM kas_ditunda WHERE id=?")->execute([(int)$_GET['hapus']]);
    flash('success', 'Data berhasil dihapus.');
    header('Location: /kas/ditunda.php'); exit;
}

$status  = $_GET['status'] ?? 'pending';
$stmt    = $pdo->prepare("SELECT d.*, a.nama AS nama_anggota FROM kas_ditunda d JOIN anggota a ON d.anggota_id=a.id WHERE d.status=? ORDER BY d.tanggal DESC");
$stmt->execute([$status]);
$ditundaList = $stmt->fetchAll();
$totalNominal = array_sum(array_column($ditundaList, 'jumlah'));

$anggotaList = $pdo->query("SELECT id, nama FROM anggota WHERE status='aktif' ORDER BY nama")->fetchAll();
$errors = [];

// Tambah kas ditunda
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $anggota_id = (int)$_POST['anggota_id'];
    $jumlah     = (float)str_replace(['.', ','], ['', '.'], $_POST['jumlah'] ?? 0);
    $keterangan = trim($_POST['keterangan'] ?? '');
    $tanggal    = $_POST['tanggal'] ?? date('Y-m-d');
    if (!$anggota_id) $errors[] = 'Pilih anggota.';
    if ($jumlah <= 0) $errors[] = 'Jumlah harus lebih dari 0.';
    if (empty($errors)) {
        $pdo->prepare("INSERT INTO kas_ditunda (anggota_id,jumlah,keterangan,tanggal) VALUES (?,?,?,?)")
            ->execute([$anggota_id, $jumlah, $keterangan, $tanggal]);
        flash('success', 'Kas ditunda berhasil dicatat.');
        header('Location: /kas/ditunda.php'); exit;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h4 mb-0 fw-bold text-dark"><i class="fas fa-clock me-2 text-warning"></i>Kas Ditunda</h1>
    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="fas fa-plus me-2"></i>Tambah Kas Ditunda
    </button>
</div>

<?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div><?php endif; ?>

<!-- Filter status -->
<div class="mb-3">
    <a href="?status=pending" class="btn btn-sm <?= $status==='pending' ? 'btn-warning' : 'btn-outline-warning' ?>">Pending (<?= $pdo->query("SELECT COUNT(*) FROM kas_ditunda WHERE status='pending'")->fetchColumn() ?>)</a>
    <a href="?status=selesai" class="btn btn-sm <?= $status==='selesai' ? 'btn-success' : 'btn-outline-success' ?> ms-1">Selesai (<?= $pdo->query("SELECT COUNT(*) FROM kas_ditunda WHERE status='selesai'")->fetchColumn() ?>)</a>
</div>

<?php if ($status === 'pending' && $totalNominal > 0): ?>
<div class="alert alert-warning py-2">
    <i class="fas fa-exclamation-triangle me-2"></i>
    Total tagihan belum lunas: <strong><?= rupiah($totalNominal) ?></strong>
</div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-warning">
                    <tr>
                        <th>No</th><th>Tanggal</th><th>Anggota</th><th>Keterangan</th><th>Jumlah</th><th>Status</th>
                        <?php if ($status==='pending'): ?><th class="text-center">Aksi</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                <?php if ($ditundaList): foreach ($ditundaList as $i => $d): ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><?= tglIndo($d['tanggal']) ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($d['nama_anggota']) ?></td>
                    <td class="text-muted"><?= htmlspecialchars($d['keterangan'] ?: '-') ?></td>
                    <td><?= rupiah($d['jumlah']) ?></td>
                    <td><span class="badge <?= $d['status']==='pending' ? 'bg-warning text-dark' : 'bg-success' ?>"><?= ucfirst($d['status']) ?></span></td>
                    <?php if ($status==='pending'): ?>
                    <td class="text-center">
                        <a href="?lunas=<?= $d['id'] ?>" class="btn btn-sm btn-success" onclick="return confirm('Tandai lunas dan pindahkan ke kas masuk?')">
                            <i class="fas fa-check me-1"></i>Lunas
                        </a>
                        <a href="?hapus=<?= $d['id'] ?>" class="btn btn-sm btn-danger ms-1" onclick="return confirm('Hapus data ini?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="7" class="text-center py-5 text-muted"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>Tidak ada data</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning">
                <h6 class="modal-title fw-bold"><i class="fas fa-clock me-2"></i>Tambah Kas Ditunda</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Anggota *</label>
                        <select name="anggota_id" class="form-select" required>
                            <option value="">-- Pilih Anggota --</option>
                            <?php foreach ($anggotaList as $a): ?>
                            <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah *</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" name="jumlah" id="jumlahModal" class="form-control" placeholder="0" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal *</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="Alasan ditunda...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save me-2"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$extraScript = '<script>
document.getElementById("jumlahModal").addEventListener("input", function(){
    let v = this.value.replace(/\D/g,"");
    this.value = v ? parseInt(v).toLocaleString("id-ID") : "";
});
</script>';
require_once __DIR__ . '/../includes/footer.php'; ?>
