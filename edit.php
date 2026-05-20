<?php
require_once 'koneksi.php';

if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}

$nama_user = htmlspecialchars($_SESSION['user'] ?? 'Admin');
$error = '';
$id = $_GET['id'] ?? '';

if (empty($id)) {
    header("Location: dashboard.php");
    exit;
}

$stmt_get = mysqli_prepare($conn, "SELECT * FROM gpu_services WHERE id = ?");
mysqli_stmt_bind_param($stmt_get, 'i', $id);
mysqli_stmt_execute($stmt_get);
$result = mysqli_stmt_get_result($stmt_get);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt_get);

if (!$row) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_gpu  = trim($_POST['nama_gpu'] ?? '');
    $harga     = trim($_POST['harga'] ?? '');
    $kebutuhan = trim($_POST['kebutuhan'] ?? '');
    $foto_baru = $row['foto'];

    if (empty($nama_gpu) || empty($harga) || empty($kebutuhan)) {
        $error = 'Semua field teks wajib diisi.';
    } elseif (!is_numeric($harga) || $harga < 0) {
        $error = 'Harga per jam harus berupa angka positif.';
    } else {
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $tmp = $_FILES['foto']['tmp_name'];
            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            $extensi_valid = ['png', 'jpg', 'jpeg'];

            if (!in_array($ext, $extensi_valid)) {
                $error = 'Format file tidak didukung! Hanya boleh JPG, JPEG, atau PNG.';
            } else {
                $foto_baru = time() . '_' . rand(100,999) . '.' . $ext;
                move_uploaded_file($tmp, 'assets/img/' . $foto_baru);
                
                if (file_exists('assets/img/' . $row['foto']) && !empty($row['foto'])) {
                    unlink('assets/img/' . $row['foto']);
                }
            }
        }

        if (empty($error)) {
            $stmt_update = mysqli_prepare($conn, "UPDATE gpu_services SET nama_gpu = ?, harga = ?, kebutuhan = ?, foto = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt_update, 'sissi', $nama_gpu, $harga, $kebutuhan, $foto_baru, $id);

            if (mysqli_stmt_execute($stmt_update)) {
                header('Location: dashboard.php?msg=updated');
                exit;
            } else {
                $error = 'Gagal mengupdate data ke database.';
            }
            mysqli_stmt_close($stmt_update);
        }
    }
}

$page_title = 'Edit Layanan GPU — NUSAGRID';
?>
<!DOCTYPE html>
<html lang="id">
<?php require_once 'partials/head.php'; ?>
<body>
    <div class="container-fluid position-relative d-flex p-0">
        <div class="sidebar pb-3">
            <nav class="navbar bg-secondary navbar-dark w-100">
                <a href="dashboard.php" class="navbar-brand mx-4 mb-3"><h3 class="text-primary"><i class="fa fa-server me-2"></i>NUSAGRID</h3></a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="ms-3"><h6 class="mb-0 text-white"><?= $nama_user ?></h6><span>Admin</span></div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="dashboard.php" class="nav-item nav-link"><i class="fa fa-table me-2"></i>Kelola GPU</a>
                    <a href="tambah.php" class="nav-item nav-link"><i class="fa fa-plus-square me-2"></i>Tambah Layanan</a>
                    <a href="logout.php" class="nav-item nav-link text-danger mt-3"><i class="fa fa-sign-out-alt me-2"></i>Logout</a>
                </div>
            </nav>
        </div>

        <div class="content">
            <nav class="navbar navbar-expand bg-secondary navbar-dark px-4 py-3 mb-4">
                <h5 class="text-white mb-0">Edit Data Layanan</h5>
            </nav>

            <div class="container-fluid px-4">
                <div class="row g-4">
                    <div class="col-sm-12 col-xl-8">
                        <div class="bg-secondary rounded p-4">
                            <h6 class="mb-4 text-white fs-5"><i class="fa fa-edit me-2 text-primary"></i>Form Edit Data GPU</h6>
                            
                            <?php if ($error): ?>
                                <div class="alert alert-danger bg-dark border-0 text-danger small">⚠ <?= htmlspecialchars($error) ?></div>
                            <?php endif; ?>

                            <form method="POST" action="" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label text-white">Nama GPU *</label>
                                    <input type="text" name="nama_gpu" class="form-control" value="<?= htmlspecialchars($row['nama_gpu']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-white">Harga per Jam (Rp) *</label>
                                    <input type="number" name="harga" class="form-control" value="<?= htmlspecialchars($row['harga']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-white">Kebutuhan GPU *</label>
                                    <textarea name="kebutuhan" class="form-control" rows="4" required><?= htmlspecialchars($row['kebutuhan']) ?></textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label text-white d-block">Foto Saat Ini</label>
                                    <img src="assets/img/<?= htmlspecialchars($row['foto']) ?>" alt="GPU" class="mb-3 rounded" style="max-height: 120px;">
                                    <input type="file" name="foto" class="form-control bg-dark" accept=".png, .jpg, .jpeg">
                                    <small class="text-muted mt-1 d-block">Biarkan kosong jika tidak ingin mengubah foto.</small>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-info text-white"><i class="fa fa-save me-1"></i> Update Data</button>
                                    <a href="dashboard.php" class="btn btn-dark">Batal</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>