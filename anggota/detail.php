<?php
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Detail Anggota';
$pdo = getDB();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM anggota WHERE id=?");
$stmt->execute([$id]);
$anggota = $stmt->fetch();
if (!$anggota) { flash('error','Data tidak ditemukan.'); header('Location: /anggota/index.php'); exit; }

// Riwayat kas anggota ini
$kasHistory = $pdo->prepare("SELECT * FROM kas WHERE anggota_id=? ORDER BY tanggal DESC LIMIT 10");
$kasHistory->execute([$id]);
$kasHistory = $kasHistory->fetchAll();

$totalKas = $pdo->prepare("SELECT COALESCE(SUM(jumlah),0) FROM kas WHERE anggota_id=?");
$totalKas->execute([$id]);
$totalKas = (float)$totalKas->fetchColumn();

$kasDitunda = $pdo->prepare("SELECT COALESCE(SUM(jumlah),0) FROM kas_ditunda WHERE anggota_id=? AND status='pending'");
$kasDitunda->execute([$id]);
$kasDitunda = (float)$kasDitunda->fetchColumn();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex align-items-center mb-4">
    <a href="/anggota/index.php" class="btn btn-sm btn-outline-secondary me-3"><i class="fas fa-arrow-left"></i></a>
    <h1 class="h4 mb-0 fw-bold text-dark"><i class="fas fa-user me-2 text-info"></i>Detail Anggota</h1>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body p-4">
                <div class="rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-3" style="width:80px;height:80px">
                    <i class="fas fa-user fa-2x text-info"></i>
                </div>
                <h5 class="fw-bold"><?= htmlspecialchars($anggota['nama']) ?></h5>
                <?php $levelColor = ['Reguler'=>'secondary','Silver'=>'light text-dark','Gold'=>'warning text-dark','Platinum'=>'primary'][$anggota['level_kas']] ?? 'secondary'; ?>
                <span class="badge bg-<?= $levelColor ?> mb-2"><?= $anggota['level_kas'] ?></span>
                <p class="text-muted small mb-1"><i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($anggota['alamat']) ?></p>
                <p class="text-muted small"><i class="fas fa-birthday-cake me-1"></i><?= $anggota['umur'] ?> tahun</p>
                <span class="badge <?= $anggota['status']==='aktif' ? 'bg-success' : 'bg-secondary' ?>"><?= ucfirst($anggota['status']) ?></span>
                <div class="mt-3">
                    <a href="/anggota/edit.php?id=<?= $id ?>" class="btn btn-sm btn-warning me-2"><i class="fas fa-edit me-1"></i>Edit</a>
                    <a href="/kas/setor.php?anggota_id=<?= $id ?>" class="btn btn-sm btn-success"><i class="fas fa-plus me-1"></i>Setor Kas</a>
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Ringkasan Keuangan</h6>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Total Kas Disetor</span>
                    <span class="fw-semibold text-success"><?= rupiah($totalKas) ?></span>
                </div>
                <?php if ($kasDitunda > 0): ?>
                <div class="d-flex justify-content-between">
                    <span class="text-muted small">Kas Belum Lunas</span>
                    <span class="fw-semibold text-warning"><?= rupiah($kasDitunda) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="fas fa-history me-2 text-success"></i>Riwayat Kas Masuk (10 Terakhir)</h6>
            </div>
            <div class="card-body p-0">
                <?php if ($kasHistory): foreach ($kasHistory as $k): ?>
                <div class="d-flex align-items-center px-4 py-3 border-bottom">
                    <div class="rounded-circle bg-success bg-opacity-10 p-2 me-3">
                        <i class="fas fa-arrow-up text-success small"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold small"><?= htmlspecialchars($k['keterangan'] ?: 'Setoran kas') ?></div>
                        <div class="text-muted" style="font-size:.75rem"><?= tglIndo($k['tanggal']) ?></div>
                    </div>
                    <span class="badge bg-success"><?= rupiah($k['jumlah']) ?></span>
                </div>
                <?php endforeach; else: ?>
                <div class="text-center py-5 text-muted"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>Belum ada riwayat kas</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
