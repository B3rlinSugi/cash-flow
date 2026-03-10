<?php
require_once __DIR__ . '/config/database.php';
startSession();

// Redirect jika sudah login
if (isLoggedIn()) {
    header('Location: /index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        $error = 'Username dan password wajib diisi!';
    } else {
        $pdo  = getDB();
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id']   = $admin['id'];
            $_SESSION['admin_nama'] = $admin['nama'];
            $_SESSION['admin_foto'] = $admin['foto'];
            session_regenerate_id(true);
            header('Location: /index.php');
            exit;
        } else {
            $error = 'Username atau password salah!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — Cash Flow Class</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); min-height: 100vh; font-family: 'Nunito', sans-serif; display: flex; align-items: center; }
        .card { border: none; border-radius: 1rem; box-shadow: 0 1rem 3rem rgba(0,0,0,.175); }
        .btn-primary { background: #4e73df; border-color: #4e73df; font-weight: 700; }
        .btn-primary:hover { background: #224abe; border-color: #224abe; }
        .brand-icon { width: 64px; height: 64px; background: #4e73df; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="brand-icon"><i class="fas fa-money-bill-wave text-white fa-2x"></i></div>
                        <h4 class="fw-bold text-dark">Cash Flow Class</h4>
                        <p class="text-muted small">Masuk ke panel admin</p>
                    </div>

                    <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show py-2 small" role="alert">
                        <i class="fas fa-exclamation-circle me-1"></i><?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>

                    <form method="POST" novalidate>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                                <input type="text" name="username" class="form-control"
                                       placeholder="Masukkan username"
                                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                                       required autofocus>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePass" tabindex="-1">
                                    <i class="fas fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>
                    </form>
                    <div class="text-center mt-3">
                        <small class="text-muted">Default: <code>admin</code> / <code>password123</code></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('togglePass').addEventListener('click', function() {
        const pass = document.querySelector('input[name="password"]');
        const icon = document.getElementById('eyeIcon');
        if (pass.type === 'password') { pass.type = 'text'; icon.className = 'fas fa-eye-slash'; }
        else { pass.type = 'password'; icon.className = 'fas fa-eye'; }
    });
</script>
</body>
</html>
