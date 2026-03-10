<?php
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Profil Admin';
$pdo = getDB();
$admin = currentAdmin();
$data  = $pdo->prepare("SELECT * FROM admin WHERE id=?");
$data->execute([$admin['id']]);
$data  = $data->fetch();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    if (!$nama) { $errors[] = 'Nama wajib diisi.'; }
    else {
        $pdo->prepare("UPDATE admin SET nama=? WHERE id=?")->execute([$nama, $admin['id']]);
        $_SESSION['admin_nama'] = $nama;
        flash('success','Profil berhasil diperbarui.');
        header('Location: /auth/profil.php'); exit;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="mb-4"><h1 class="h4 mb-0 fw-bold text-dark"><i class="fas fa-user-circle me-2 text-primary"></i>Profil Admin</h1></div>

<?php if ($errors): ?><div class="alert alert-danger"><?= htmlspecialchars($errors[0]) ?></div><?php endif; ?>

<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 text-center">
                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-3" style="width:80px;height:80px">
                    <i class="fas fa-user fa-2x text-primary"></i>
                </div>
                <h5 class="fw-bold"><?= htmlspecialchars($data['nama']) ?></h5>
                <p class="text-muted small">@<?= htmlspecialchars($data['username']) ?></p>
                <form method="POST" class="text-start mt-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama</label>
                        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($data['username']) ?>" disabled>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                </form>
                <a href="/auth/ganti-password.php" class="btn btn-outline-secondary w-100 mt-2"><i class="fas fa-key me-2"></i>Ganti Password</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
