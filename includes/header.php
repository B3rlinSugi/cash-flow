<?php
// includes/header.php — Shared sidebar + topbar layout
requireLogin();
$admin = currentAdmin();
$pdo   = getDB();

// Hitung saldo untuk topbar
$totalKas   = $pdo->query("SELECT COALESCE(SUM(jumlah),0) FROM kas")->fetchColumn();
$totalKeluar = $pdo->query("SELECT COALESCE(SUM(jumlah),0) FROM pengeluaran")->fetchColumn();
$saldo      = $totalKas - $totalKeluar;

// Current page untuk highlight menu aktif
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
function menuAktif(string $page, array $pages): string {
    return in_array($page, $pages) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= APP_NAME ?> — <?= $pageTitle ?? 'Dashboard' ?></title>
    <link rel="shortcut icon" href="/assets/img/favicon.png">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/assets/css/style.css" rel="stylesheet">
    <?= $extraHead ?? '' ?>
</head>
<body id="page-top">
<div id="wrapper">

    <!-- ===== SIDEBAR ===== -->
    <ul class="navbar-nav sidebar sidebar-dark accordion bg-gradient-primary" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center py-4" href="/index.php">
            <div class="sidebar-brand-icon me-2"><i class="fas fa-money-bill-wave fa-lg"></i></div>
            <div class="sidebar-brand-text fw-bold">Cash Flow<br><small class="fw-normal opacity-75">Class</small></div>
        </a>
        <hr class="sidebar-divider my-0">

        <!-- Dashboard -->
        <li class="nav-item <?= menuAktif($currentPage, ['index']) ?>">
            <a class="nav-link" href="/index.php"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a>
        </li>
        <hr class="sidebar-divider">

        <!-- Anggota -->
        <div class="sidebar-heading">Manajemen Anggota</div>
        <li class="nav-item <?= menuAktif($currentPage, ['anggota/index','anggota/tambah','anggota/edit','anggota/detail']) ?>">
            <a class="nav-link collapsed" href="#menuAnggota" data-bs-toggle="collapse" role="button" aria-expanded="false">
                <i class="fas fa-fw fa-users"></i><span>Anggota</span>
            </a>
            <div id="menuAnggota" class="collapse <?= str_contains($currentPage,'anggota') ? 'show' : '' ?>">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="/anggota/tambah.php"><i class="fas fa-plus fa-xs me-1"></i>Tambah Anggota</a>
                    <a class="collapse-item" href="/anggota/index.php"><i class="fas fa-list fa-xs me-1"></i>Daftar Anggota</a>
                </div>
            </div>
        </li>
        <hr class="sidebar-divider">

        <!-- Keuangan -->
        <div class="sidebar-heading">Manajemen Keuangan</div>
        <li class="nav-item <?= menuAktif($currentPage, ['kas/index','kas/setor','kas/edit']) ?>">
            <a class="nav-link collapsed" href="#menuKas" data-bs-toggle="collapse" role="button">
                <i class="fas fa-fw fa-wallet"></i><span>Kas Masuk</span>
            </a>
            <div id="menuKas" class="collapse <?= str_contains($currentPage,'kas/') ? 'show' : '' ?>">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="/kas/setor.php">Setor Kas</a>
                    <a class="collapse-item" href="/kas/index.php">Daftar Kas</a>
                    <a class="collapse-item" href="/kas/ditunda.php">Kas Ditunda</a>
                </div>
            </div>
        </li>
        <li class="nav-item <?= menuAktif($currentPage, ['pengeluaran/index','pengeluaran/tambah']) ?>">
            <a class="nav-link collapsed" href="#menuKeluar" data-bs-toggle="collapse" role="button">
                <i class="fas fa-fw fa-arrow-circle-down"></i><span>Pengeluaran</span>
            </a>
            <div id="menuKeluar" class="collapse <?= str_contains($currentPage,'pengeluaran') ? 'show' : '' ?>">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="/pengeluaran/tambah.php">Catat Pengeluaran</a>
                    <a class="collapse-item" href="/pengeluaran/index.php">Daftar Pengeluaran</a>
                </div>
            </div>
        </li>
        <hr class="sidebar-divider">

        <!-- Laporan -->
        <div class="sidebar-heading">Laporan</div>
        <li class="nav-item <?= menuAktif($currentPage, ['laporan/index']) ?>">
            <a class="nav-link" href="/laporan/index.php"><i class="fas fa-fw fa-file-export"></i><span>Export Laporan</span></a>
        </li>
        <hr class="sidebar-divider d-none d-md-block">
        <div class="text-center d-none d-md-inline pb-3">
            <button class="rounded-circle border-0 btn-sm" id="sidebarToggle" style="background:rgba(255,255,255,.2);color:#fff;width:2rem;height:2rem;">
                <i class="fas fa-angle-left"></i>
            </button>
        </div>
    </ul>
    <!-- End Sidebar -->

    <!-- ===== CONTENT WRAPPER ===== -->
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow-sm px-3">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle me-3">
                    <i class="fa fa-bars text-primary"></i>
                </button>

                <!-- Saldo Badge -->
                <span class="badge <?= $saldo >= 0 ? 'bg-success' : 'bg-danger' ?> fs-6 d-none d-sm-inline">
                    <i class="fas fa-coins me-1"></i>Saldo: <?= rupiah($saldo) ?>
                </span>

                <ul class="navbar-nav ms-auto align-items-center">
                    <div class="topbar-divider d-none d-sm-block"></div>
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <span class="me-2 d-none d-lg-inline text-gray-600 small fw-semibold"><?= htmlspecialchars($admin['nama']) ?></span>
                            <img class="img-profile rounded-circle border" src="/assets/img/<?= htmlspecialchars($admin['foto']) ?>" width="32" height="32" alt="profil">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="/auth/profil.php"><i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>Profil</a></li>
                            <li><a class="dropdown-item" href="/auth/ganti-password.php"><i class="fas fa-key fa-sm fa-fw me-2 text-gray-400"></i>Ganti Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <!-- End Topbar -->

            <!-- Flash Messages -->
            <?php
            $flashSuccess = flash('success');
            $flashError   = flash('error');
            if ($flashSuccess): ?>
            <div class="container-fluid px-4">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($flashSuccess) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
            <?php endif; ?>
            <?php if ($flashError): ?>
            <div class="container-fluid px-4">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($flashError) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
            <?php endif; ?>

            <!-- Page Content -->
            <div class="container-fluid px-4">
