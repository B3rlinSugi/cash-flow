<?php
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Daftar Pengeluaran';
$pdo = getDB();

if (isset($_GET['hapus'])) {
    $pdo->prepare("DELETE FROM pengeluaran WHERE id=?")->execute([(int)$_GET['hapus']]);
    flash('success','Data pengeluaran berhasil dihapus.');
    header('Location: /pengeluaran/index.php'); exit;
}

$search  = trim($_GET['search'] ?? '');
$perPage = 10;
$page    = max(1,(int)($_GET['page'] ?? 1));
$offset  = ($page-1)*$perPage;

$where = []; $params = [];
if ($search) { $where[] = "(nama LIKE ? OR keterangan LIKE ?)"; $params = ["%$search%","%$search%"]; }
$whereStr = $where ? 'WHERE '.implode(' AND ',$where) : '';

$total = $pdo->prepare("SELECT COUNT(*) FROM pengeluaran $whereStr");
$total->execute($params);
$totalRows  = (int)$total->fetchColumn();
$totalPages = ceil($totalRows/$perPage);

$stmt = $pdo->prepare("SELECT * FROM pengeluaran $whereStr ORDER BY tanggal DESC, id DESC LIMIT $perPage OFFSET $offset");
$stmt->execute($params);
$list = $stmt->fetchAll();

$totalNominal = (float)$pdo->query("SELECT COALESCE(SUM(jumlah),0) FROM pengeluaran")->fetchColumn();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h4 mb-0 fw-bold text-dark"><i class="fas fa-arrow-circle-down me-2 text-danger"></i>Daftar Pengeluaran</h1>
    <a href="/pengeluaran/tambah.php" class="btn btn-danger"><i class="fas fa-plus me-2"></i>Catat Pengeluaran</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm border-left-danger">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="text-xs fw-bold text-danger text-uppercase mb-1">Total Pengeluaran</div>
                    <div class="h5 fw-bold mb-0"><?= rupiah($totalNominal) ?></div>
                </div>
                <i class="fas fa-arrow-circle-down fa-2x text-danger opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau keterangan..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-danger"><i class="fas fa-search me-1"></i>Cari</button>
                <a href="/pengeluaran/index.php" class="btn btn-outline-secondary ms-1">Reset</a>
            </div>
            <div class="col-auto ms-auto">
                <a href="/laporan/index.php?action=pdf&jenis=pengeluaran&bulan=<?= date('Y-m') ?>" class="btn btn-outline-danger" target="_blank">
                    <i class="fas fa-file-pdf me-1"></i>Export PDF
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-danger">
                    <tr><th>No</th><th>Tanggal</th><th>Nama Pengeluaran</th><th>Keterangan</th><th>Jumlah</th><th class="text-center">Aksi</th></tr>
                </thead>
                <tbody>
                <?php if ($list): foreach ($list as $i => $p): ?>
                <tr>
                    <td><?= $offset+$i+1 ?></td>
                    <td><?= tglIndo($p['tanggal']) ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($p['nama']) ?></td>
                    <td class="text-muted"><?= htmlspecialchars($p['keterangan'] ?: '-') ?></td>
                    <td><span class="badge bg-danger"><?= rupiah($p['jumlah']) ?></span></td>
                    <td class="text-center">
                        <a href="/pengeluaran/edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <a href="?hapus=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="6" class="text-center py-5 text-muted"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>Belum ada data pengeluaran</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if ($totalPages > 1): ?>
    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
        <small class="text-muted">Halaman <?= $page ?> dari <?= $totalPages ?></small>
        <nav><ul class="pagination pagination-sm mb-0">
            <?php for ($i=1; $i<=$totalPages; $i++): ?>
            <li class="page-item <?= $i===$page?'active':'' ?>"><a class="page-link" href="?<?= http_build_query(['search'=>$search,'page'=>$i]) ?>"><?= $i ?></a></li>
            <?php endfor; ?>
        </ul></nav>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
