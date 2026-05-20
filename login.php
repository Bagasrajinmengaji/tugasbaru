<?php
require_once 'koneksi.php';
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ? AND password = ?");
    mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['login'] = true;
        $_SESSION['user']  = $username;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = true;
    }
    mysqli_stmt_close($stmt);
}
$page_title = 'Login Admin — NUSAGRID';
?>
<!DOCTYPE html>
<html lang="id">
<?php require_once 'partials/head.php'; ?>
<body>
    <div class="container-fluid">
        <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
            <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                <div class="bg-secondary rounded p-4 p-sm-5 my-4 mx-3 shadow-lg">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h3 class="text-primary"><i class="fa fa-server me-2"></i>NUSAGRID</h3>
                    </div>
                    <h5 class="text-white mb-4">Login Admin Dashboard</h5>
                    
                    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'registered'): ?>
                        <div class="alert alert-success bg-dark border-0 text-success py-2">Akun berhasil dibuat! Silakan login.</div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger bg-dark border-0 text-danger py-2">⚠ Username atau password salah!</div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="form-floating mb-3">
                            <input type="text" name="username" class="form-control" id="floatingInput" placeholder="Username" required>
                            <label for="floatingInput">Username</label>
                        </div>
                        <div class="form-floating mb-4">
                            <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required>
                            <label for="floatingPassword">Password</label>
                        </div>
                        <button type="submit" class="btn btn-primary py-3 w-100 mb-4 fw-bold">Login Sistem</button>
                        <p class="text-center mb-0">Belum punya akun? <a href="register.php" class="text-primary">Daftar di sini</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>