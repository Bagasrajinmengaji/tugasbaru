<?php
require_once 'koneksi.php';
$error = '';

if(isset($_POST['register'])){
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    // Cek apakah username / email sudah terpakai
    $cek = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? OR username = ?");
    mysqli_stmt_bind_param($cek, 'ss', $email, $username);
    mysqli_stmt_execute($cek);
    mysqli_stmt_store_result($cek);

    if(mysqli_stmt_num_rows($cek) > 0){
        $error = 'Username atau Email sudah terdaftar.';
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'sss', $username, $email, $password);
        if(mysqli_stmt_execute($stmt)){
            header("Location: login.php?msg=registered");
            exit;
        } else {
            $error = 'Gagal mendaftar.';
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_stmt_close($cek);
}
$page_title = 'Register Admin — NUSAGRID';
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
                    <h5 class="text-white mb-4">Buat Akun Baru</h5>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger bg-dark border-0 text-danger py-2">⚠ <?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="form-floating mb-3">
                            <input type="text" name="username" class="form-control" id="floatingUsername" placeholder="Username" required>
                            <label for="floatingUsername">Username</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com" required>
                            <label for="floatingInput">Email address</label>
                        </div>
                        <div class="form-floating mb-4">
                            <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required>
                            <label for="floatingPassword">Password</label>
                        </div>
                        <button type="submit" name="register" class="btn btn-primary py-3 w-100 mb-4 fw-bold">Daftar Sekarang</button>
                        <p class="text-center mb-0">Sudah punya akun? <a href="login.php" class="text-primary">Login di sini</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>