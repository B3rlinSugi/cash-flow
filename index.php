<?php
require_once __DIR__ . '/config/database.php';
$pageTitle = 'Dashboard';
$pdo = getDB();

// ── Summary cards ──
$totalKas     = (float)$pdo->query("SELECT COALESCE(SUM(jumlah),0) FROM kas")->fetchColumn();
$totalKeluar  = (float)$pdo->query("SELECT COALESCE(SUM(jumlah),0) FROM pengeluaran")->fetchColumn();
$saldo        = $totalKas - $totalKeluar;
$totalAnggota = (int)$pdo->query("SELECT COUNT(*) FROM anggota WHERE status='aktif'")->fetchColumn();
$kasDitunda   = (int)$pdo->query("SELECT COUNT(*) FROM kas_ditunda WHERE status='pending'")->fetchColumn();

// ── Chart: 6 bulan terakhir ──
$chartLabels = [];
$chartKas    = [];
$chartKeluar = [];
for ($i = 5; $i >= 0; $i--) {
    $month  = date('Y-m', strtotime("-$i months"));
    $label  = date('M Y', strtotime("-$i months"));
    $chartLabels[] = $label;

    $stmt = $pdo->prepare("SELECT COALESCE(SUM(jumlah),0) FROM kas WHERE DATE_FORMAT(tanggal,'%Y-%m')=?");
    $stmt->execute([$month]);
    $chartKas[] = (float)$stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COALESCE(SUM(jumlah),0) FROM pengeluaran WHERE DATE_FORMAT(tanggal,'%Y-%m')=?");
    $stmt->execute([$month]);
    $chartKeluar[] = (float)$stmt->fetchColumn();
}

// ── 5 kas terbaru ──
$kasRecent = $pdo->query("
    SELECT k.*, a.nama AS nama_anggota
    FROM kas k
    JOIN anggota a ON k.anggota_id = a.id
    ORDER BY k.created_at DESC LIMIT 5
")->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<!-- Page Title -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h4 mb-0 fw-bold text-dark"><i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard</h1>
    <small class="text-muted"><?= date('l, d F Y') ?></small>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-left-success shadow-sm h-100 border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs fw-bold text-success text-uppercase mb-1">Total Kas Masuk</div>
                        <div class="h5 mb-0 fw-bold"><?= rupiah($totalKas) ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-arrow-circle-up fa-2x text-success opacity-50"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-left-danger shadow-sm h-100 border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs fw-bold text-danger text-uppercase mb-1">Total Pengeluaran</div>
                        <div class="h5 mb-0 fw-bold"><?= rupiah($totalKeluar) ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-arrow-circle-down fa-2x text-danger opacity-50"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-left-primary shadow-sm h-100 border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Saldo Bersih</div>
                        <div class="h5 mb-0 fw-bold <?= $saldo >= 0 ? 'text-success' : 'text-danger' ?>"><?= rupiah($saldo) ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-wallet fa-2x text-primary opacity-50"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-left-info shadow-sm h-100 border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs fw-bold text-info text-uppercase mb-1">Total Anggota Aktif</div>
                        <div class="h5 mb-0 fw-bold"><?= $totalAnggota ?></div>
                        <?php if ($kasDitunda > 0): ?>
                        <small class="text-warning"><i class="fas fa-clock me-1"></i><?= $kasDitunda ?> kas ditunda</small>
                        <?php endif; ?>
                    </div>
                    <div class="col-auto"><i class="fas fa-users fa-2x text-info opacity-50"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart + Recent Kas -->
<div class="row g-3 mb-4">
    <!-- Chart Arus Kas -->
    <div class="col-xl-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
                <h6 class="m-0 fw-bold text-primary"><i class="fas fa-chart-bar me-2"></i>Arus Kas 6 Bulan Terakhir</h6>
                <a href="/laporan/index.php" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-file-export me-1"></i>Ekspor
                </a>
            </div>
            <div class="card-body">
                <canvas id="chartArusKas" height="280"></canvas>
            </div>
        </div>
    </div>

    <!-- Kas Terbaru -->
    <div class="col-xl-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-primary"><i class="fas fa-clock me-2"></i>Kas Masuk Terbaru</h6>
            </div>
            <div class="card-body p-0">
                <?php if ($kasRecent): foreach ($kasRecent as $k): ?>
                <div class="d-flex align-items-center px-3 py-2 border-bottom">
                    <div class="rounded-circle bg-success bg-opacity-10 p-2 me-3">
                        <i class="fas fa-arrow-up text-success small"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold small"><?= htmlspecialchars($k['nama_anggota']) ?></div>
                        <div class="text-muted" style="font-size:.75rem"><?= tglIndo($k['tanggal']) ?></div>
                    </div>
                    <span class="badge bg-success"><?= rupiah($k['jumlah']) ?></span>
                </div>
                <?php endforeach; else: ?>
                <div class="text-center py-4 text-muted"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>Belum ada data</div>
                <?php endif; ?>
                <div class="text-center py-2">
                    <a href="/kas/index.php" class="btn btn-sm btn-outline-success">Lihat Semua</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$extraScript = '
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById("chartArusKas").getContext("2d");
new Chart(ctx, {
    type: "bar",
    data: {
        labels: ' . json_encode($chartLabels) . ',
        datasets: [
            {
                label: "Kas Masuk",
                data: ' . json_encode($chartKas) . ',
                backgroundColor: "rgba(28,200,138,.8)",
                borderColor: "#1cc88a",
                borderWidth: 1,
                borderRadius: 4,
            },
            {
                label: "Pengeluaran",
                data: ' . json_encode($chartKeluar) . ',
                backgroundColor: "rgba(231,74,59,.7)",
                borderColor: "#e74a3b",
                borderWidth: 1,
                borderRadius: 4,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: "top" },
            tooltip: {
                callbacks: {
                    label: ctx => " Rp " + ctx.raw.toLocaleString("id-ID")
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: val => "Rp " + val.toLocaleString("id-ID")
                }
            }
        }
    }
});
</script>';
require_once __DIR__ . '/includes/footer.php';
?>
