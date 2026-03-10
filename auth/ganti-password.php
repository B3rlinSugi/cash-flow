<?php
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Ganti Password';
$pdo = getDB();
$admin = currentAdmin();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lama  = $_POST['password_lama'] ?? '';
    $baru  = $_POST['password_baru'] ?? '';
    $ulang = $_POST['password_ulang'] ?? '';

    $row = $pdo->prepare("SELECT password FROM admin WHERE id=?");
    $row->execute([$admin['id']]);
    $row = $row->fetch();

    if (!password_verify($lama, $row['password'])) $errors[] = 'Password lama tidak sesuai.';
    if (strlen($baru) < 6)  $errors[] = 'Password baru minimal 6 karakter.';
    if ($baru !== $ulang)   $errors[] = 'Konfirmasi password tidak cocok.';

    if (empty($errors)) {
        $pdo->prepare("UPDATE admin SET password=? WHERE id=?")->execute([password_hash($baru, PASSWORD_BCRYPT), $admin['id']]);
        flash('success','Password berhasil diubah. Silakan login kembali.');
        session_destroy();
        header('Location: /login.php'); exit;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex align-items-center mb-4">
    <a href="/auth/profil.php" class="btn btn-sm btn-outline-secondary me-3"><i class="fas fa-arrow-left"></i></a>
    <h1 class="h4 mb-0 fw-bold text-dark"><i class="fas fa-key me-2 text-warning"></i>Ganti Password</h1>
</div>

<?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div><?php endif; ?>

<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning py-3">
                <h6 class="m-0 fw-bold"><i class="fas fa-key me-2"></i>Form Ganti Password</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Lama *</label>
                        <input type="password" name="password_lama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Baru * <small class="text-muted">(min. 6 karakter)</small></label>
                        <input type="password" name="password_baru" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Konfirmasi Password Baru *</label>
                        <input type="password" name="password_ulang" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-warning w-100"><i class="fas fa-save me-2"></i>Ubah Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
