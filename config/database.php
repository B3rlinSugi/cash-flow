<?php
// =============================================
// config/database.php — PDO connection & helpers
// =============================================

define('DB_HOST', getenv('MYSQLHOST') ?: 'localhost');
define('DB_PORT', getenv('MYSQLPORT') ?: '3306');
define('DB_NAME', getenv('MYSQLDATABASE') ?: 'cashflow_db');
define('DB_USER', getenv('MYSQLUSER') ?: 'root');
define('DB_PASS', getenv('MYSQLPASSWORD') ?: '');
define('APP_NAME', 'Cash Flow Class');

function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            die('<div style="font-family:sans-serif;padding:20px;color:red;">
                <h3>Koneksi Database Gagal</h3>
                <p>' . htmlspecialchars($e->getMessage()) . '</p>
                <p>Pastikan MySQL aktif dan database <b>' . DB_NAME . '</b> sudah dibuat.</p>
            </div>');
        }
    }
    return $pdo;
}

// ---- Session & Auth Helpers ----
function startSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function requireLogin(): void {
    startSession();
    if (empty($_SESSION['admin_id'])) {
        header('Location: /login.php');
        exit;
    }
}

function isLoggedIn(): bool {
    startSession();
    return !empty($_SESSION['admin_id']);
}

function currentAdmin(): array {
    startSession();
    return [
        'id'   => $_SESSION['admin_id']   ?? 0,
        'nama' => $_SESSION['admin_nama'] ?? '',
        'foto' => $_SESSION['admin_foto'] ?? 'admin.png',
    ];
}

// ---- Format Helpers ----
function rupiah(float $angka): string {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function tglIndo(string $tgl): string {
    $bulan = ['', 'Januari','Februari','Maret','April','Mei','Juni',
              'Juli','Agustus','September','Oktober','November','Desember'];
    [$y, $m, $d] = explode('-', $tgl);
    return (int)$d . ' ' . $bulan[(int)$m] . ' ' . $y;
}

function flash(string $key, string $msg = ''): ?string {
    startSession();
    if ($msg !== '') {
        $_SESSION['flash'][$key] = $msg;
        return null;
    }
    $val = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $val;
}
