<?php
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Daftar Kas Masuk';
$pdo = getDB();

// Hapus
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $pdo->prepare("DELETE FROM kas WHERE id = ?")->execute([$id]);
    flash('success', 'Data kas berhasil dihapus.');
    header('Location: /kas/index.php'); exit;
}

// Filter & pagination
$search  = trim($_GET['search'] ?? '');
$perPage = 10;
$page    = max(1, (int)($_GET['page'] ?? 1));
$offset  = ($page - 1) * $perPage;

$where = []; $params = [];
if ($search) { $where[] = "(a.nama LIKE ? OR k.keterangan LIKE ?)"; $params = ["%$search%", "%$search%"]; }
$whereStr = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$total = $pdo->prepare("SELECT COUNT(*) FROM kas k JOIN anggota a ON k.anggota_id=a.id $whereStr");
$total->execute($params);
$totalRows  = (int)$total->fetchColumn();
$totalPages = ceil($totalRows / $perPage);

$stmt = $pdo->prepare("
    SELECT k.*, a.nama AS nama_anggota FROM kas k
    JOIN anggota a ON k.anggota_id = a.id
    $whereStr ORDER BY k.tanggal DESC, k.id DESC
    LIMIT $perPage OFFSET $offset
");
$stmt->execute($params);
$kasList = $stmt->fetchAll();

$totalKas = (float)$pdo->query("SELECT COALESCE(SUM(jumlah),0) FROM kas")->fetchColumn();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h4 mb-0 fw-bold text-dark"><i class="fas fa-wallet me-2 text-success"></i>Daftar Kas Masuk</h1>
    <a href="/kas/setor.php" class="btn btn-success"><i class="fas fa-plus me-2"></i>Setor Kas</a>
</div>

<!-- Summary -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm border-left-success">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="text-xs fw-bold text-success text-uppercase mb-1">Total Kas Masuk</div>
                    <div class="h5 fw-bold mb-0"><?= rupiah($totalKas) ?></div>
                </div>
                <i class="fas fa-arrow-circle-up fa-2x text-success opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm border-left-info">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="text-xs fw-bold text-info text-uppercase mb-1">Total Transaksi</div>
                    <div class="h5 fw-bold mb-0"><?= $totalRows ?> setoran</div>
                </div>
                <i class="fas fa-list fa-2x text-info opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm border-left-warning">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="text-xs fw-bold text-warning text-uppercase mb-1">Kas Ditunda</div>
                    <?php $ditunda = (int)$pdo->query("SELECT COUNT(*) FROM kas_ditunda WHERE status='pending'")->fetchColumn(); ?>
                    <div class="h5 fw-bold mb-0"><?= $ditunda ?> transaksi</div>
                </div>
                <i class="fas fa-clock fa-2x text-warning opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<!-- Search & Export -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Cari nama anggota atau keterangan..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i>Cari</button>
                <a href="/kas/index.php" class="btn btn-outline-secondary ms-1">Reset</a>
            </div>
            <div class="col-auto ms-auto">
                <a href="/laporan/index.php" class="btn btn-outline-danger"><i class="fas fa-file-pdf me-1"></i>Export PDF</a>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-success">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Anggota</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($kasList): foreach ($kasList as $i => $k): ?>
                <tr>
                    <td><?= $offset + $i + 1 ?></td>
                    <td><?= tglIndo($k['tanggal']) ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($k['nama_anggota']) ?></td>
                    <td class="text-muted"><?= htmlspecialchars($k['keterangan'] ?: '-') ?></td>
                    <td><span class="badge bg-success fs-6"><?= rupiah($k['jumlah']) ?></span></td>
                    <td class="text-center">
                        <a href="/kas/edit.php?id=<?= $k['id'] ?>" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                        <a href="/kas/index.php?hapus=<?= $k['id'] ?>" class="btn btn-sm btn-danger" title="Hapus"
                           onclick="return confirm('Hapus data kas ini?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="6" class="text-center py-5 text-muted"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>Belum ada data kas</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if ($totalPages > 1): ?>
    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
        <small class="text-muted">Halaman <?= $page ?> dari <?= $totalPages ?> (<?= $totalRows ?> data)</small>
        <nav><ul class="pagination pagination-sm mb-0">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                <a class="page-link" href="?<?= http_build_query(['search'=>$search,'page'=>$i]) ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
        </ul></nav>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
