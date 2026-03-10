<?php
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Daftar Anggota';
$pdo = getDB();

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    // Cek apakah punya kas
    $cek = $pdo->prepare("SELECT COUNT(*) FROM kas WHERE anggota_id=?");
    $cek->execute([$id]);
    if ($cek->fetchColumn() > 0) {
        flash('error', 'Anggota tidak bisa dihapus karena memiliki data kas.');
    } else {
        $pdo->prepare("DELETE FROM anggota WHERE id=?")->execute([$id]);
        flash('success', 'Anggota berhasil dihapus.');
    }
    header('Location: /anggota/index.php'); exit;
}

$search = trim($_GET['search'] ?? '');
$where  = $search ? "WHERE nama LIKE ? OR level_kas LIKE ?" : '';
$params = $search ? ["%$search%", "%$search%"] : [];

$stmt = $pdo->prepare("SELECT * FROM anggota $where ORDER BY nama");
$stmt->execute($params);
$anggotaList = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h4 mb-0 fw-bold text-dark"><i class="fas fa-users me-2 text-info"></i>Daftar Anggota</h1>
    <a href="/anggota/tambah.php" class="btn btn-info text-white"><i class="fas fa-plus me-2"></i>Tambah Anggota</a>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau level..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-info text-white"><i class="fas fa-search me-1"></i>Cari</button>
                <a href="/anggota/index.php" class="btn btn-outline-secondary ms-1">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-info">
                    <tr><th>No</th><th>Nama</th><th>Alamat</th><th>Umur</th><th>Level Kas</th><th>Status</th><th class="text-center">Aksi</th></tr>
                </thead>
                <tbody>
                <?php if ($anggotaList): foreach ($anggotaList as $i => $a):
                    $levelColor = ['Reguler'=>'secondary','Silver'=>'light text-dark','Gold'=>'warning text-dark','Platinum'=>'primary'][$a['level_kas']] ?? 'secondary';
                ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($a['nama']) ?></td>
                    <td class="text-muted"><?= htmlspecialchars($a['alamat']) ?></td>
                    <td><?= $a['umur'] ?> th</td>
                    <td><span class="badge bg-<?= $levelColor ?>"><?= $a['level_kas'] ?></span></td>
                    <td><span class="badge <?= $a['status']==='aktif' ? 'bg-success' : 'bg-secondary' ?>"><?= ucfirst($a['status']) ?></span></td>
                    <td class="text-center">
                        <a href="/anggota/detail.php?id=<?= $a['id'] ?>" class="btn btn-sm btn-info text-white"><i class="fas fa-eye"></i></a>
                        <a href="/anggota/edit.php?id=<?= $a['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <a href="?hapus=<?= $a['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus anggota <?= htmlspecialchars($a['nama']) ?>?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="7" class="text-center py-5 text-muted"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>Belum ada data anggota</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
