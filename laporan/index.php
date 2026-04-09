<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;
$pageTitle = 'Export Laporan';
$pdo = getDB();

// Handle export
$action = $_GET['action'] ?? '';
if ($action === 'pdf') {
    $bulan  = $_GET['bulan']  ?? date('Y-m');
    $jenis  = $_GET['jenis']  ?? 'semua';

    // Data kas
    $kas = [];
    if ($jenis === 'semua' || $jenis === 'kas') {
        $stmt = $pdo->prepare("
            SELECT k.tanggal, a.nama AS nama_anggota, k.jumlah, k.keterangan
            FROM kas k JOIN anggota a ON k.anggota_id = a.id
            WHERE DATE_FORMAT(k.tanggal,'%Y-%m') = ?
            ORDER BY k.tanggal
        ");
        $stmt->execute([$bulan]);
        $kas = $stmt->fetchAll();
    }

    // Data pengeluaran
    $keluar = [];
    if ($jenis === 'semua' || $jenis === 'pengeluaran') {
        $stmt = $pdo->prepare("SELECT * FROM pengeluaran WHERE DATE_FORMAT(tanggal,'%Y-%m')=? ORDER BY tanggal");
        $stmt->execute([$bulan]);
        $keluar = $stmt->fetchAll();
    }

    $totalKas    = array_sum(array_column($kas, 'jumlah'));
    $totalKeluar = array_sum(array_column($keluar, 'jumlah'));
    $saldo       = $totalKas - $totalKeluar;
    $bulanLabel  = date('F Y', strtotime($bulan . '-01'));

    // Generate HTML untuk PDF via Dompdf
    ob_start();
    echo '<!DOCTYPE html><html lang="id"><head>
    <meta charset="UTF-8">
    <title>Laporan ' . $bulanLabel . '</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        h2 { text-align:center; margin-bottom:4px; }
        p.sub { text-align:center; color:#666; margin-top:0; }
        table { width:100%; border-collapse:collapse; margin-top:12px; }
        th { background:#4e73df; color:#fff; padding:7px 10px; text-align:left; }
        td { padding:6px 10px; border-bottom:1px solid #eee; }
        tr:nth-child(even) td { background:#f8f9fc; }
        .total-row td { font-weight:bold; background:#e8f4e8; }
        .saldo { text-align:right; margin-top:10px; font-size:14px; font-weight:bold; }
        .saldo span { color:' . ($saldo >= 0 ? 'green' : 'red') . '; }
    </style>
    </head><body>';
    echo '<h2>Laporan Keuangan — Cash Flow Class</h2>';
    echo '<p class="sub">Periode: ' . $bulanLabel . '</p>';

    if (!empty($kas)) {
        echo '<h3>Kas Masuk</h3><table><tr><th>No</th><th>Tanggal</th><th>Anggota</th><th>Keterangan</th><th>Jumlah</th></tr>';
        foreach ($kas as $i => $k) {
            echo '<tr><td>' . ($i+1) . '</td><td>' . tglIndo($k['tanggal']) . '</td><td>' . htmlspecialchars($k['nama_anggota']) . '</td><td>' . htmlspecialchars($k['keterangan']) . '</td><td>' . rupiah($k['jumlah']) . '</td></tr>';
        }
        echo '<tr class="total-row"><td colspan="4">Total Kas Masuk</td><td>' . rupiah($totalKas) . '</td></tr></table>';
    }
    if (!empty($keluar)) {
        echo '<h3>Pengeluaran</h3><table><tr><th>No</th><th>Tanggal</th><th>Nama</th><th>Keterangan</th><th>Jumlah</th></tr>';
        foreach ($keluar as $i => $k) {
            echo '<tr><td>' . ($i+1) . '</td><td>' . tglIndo($k['tanggal']) . '</td><td>' . htmlspecialchars($k['nama']) . '</td><td>' . htmlspecialchars($k['keterangan']) . '</td><td>' . rupiah($k['jumlah']) . '</td></tr>';
        }
        echo '<tr class="total-row"><td colspan="4">Total Pengeluaran</td><td>' . rupiah($totalKeluar) . '</td></tr></table>';
    }
    echo '<p class="saldo">Saldo Bersih: <span>' . rupiah($saldo) . '</span></p>';
    echo '</body></html>';
    
    $html = ob_get_clean();

    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $options->set('defaultFont', 'Arial');
    
    $dompdf = new Dompdf($options);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->loadHtml($html);
    $dompdf->render();
    
    $filename = "Laporan_CashFlow_" . str_replace(' ', '_', $bulanLabel) . ".pdf";
    $dompdf->stream($filename, ["Attachment" => true]);
    exit;
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex align-items-center mb-4">
    <h1 class="h4 mb-0 fw-bold text-dark"><i class="fas fa-file-export me-2 text-primary"></i>Export Laporan</h1>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <h6 class="m-0 fw-bold"><i class="fas fa-filter me-2"></i>Filter Laporan</h6>
            </div>
            <div class="card-body p-4">
                <form method="GET" action="/laporan/index.php">
                    <input type="hidden" name="action" value="pdf">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Periode (Bulan)</label>
                        <input type="month" name="bulan" class="form-control" value="<?= date('Y-m') ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Jenis Laporan</label>
                        <select name="jenis" class="form-select">
                            <option value="semua">Semua (Kas + Pengeluaran)</option>
                            <option value="kas">Kas Masuk Saja</option>
                            <option value="pengeluaran">Pengeluaran Saja</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-file-pdf me-2"></i>Generate Laporan PDF
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
