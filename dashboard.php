<?php
require_once 'koneksi.php';

if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}

$nama_user = htmlspecialchars($_SESSION['user']);
$data = mysqli_query($conn, "SELECT * FROM gpu_services ORDER BY id DESC");

$page_title = 'Dashboard — NUSAGRID';
?>
<!DOCTYPE html>
<html lang="id">
<?php require_once 'partials/head.php'; ?>
<body>
    <div class="container-fluid position-relative d-flex p-0">
        
        <div class="sidebar pb-3">
            <nav class="navbar bg-secondary navbar-dark w-100">
                <a href="dashboard.php" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary"><i class="fa fa-server me-2"></i>NUSAGRID</h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="ms-3">
                        <h6 class="mb-0 text-white"><?= $nama_user ?></h6>
                        <span>Admin</span>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="dashboard.php" class="nav-item nav-link active"><i class="fa fa-table me-2"></i>Kelola GPU</a>
                    <a href="tambah.php" class="nav-item nav-link"><i class="fa fa-plus-square me-2"></i>Tambah Layanan</a>
                    <a href="logout.php" class="nav-item nav-link text-danger mt-3"><i class="fa fa-sign-out-alt me-2"></i>Logout</a>
                </div>
            </nav>
        </div>

        <div class="content">
            <nav class="navbar navbar-expand bg-secondary navbar-dark px-4 py-3 mb-4">
                <h5 class="text-white mb-0">Dashboard Cloud GPU</h5>
            </nav>

            <div class="container-fluid px-4">
                <div class="bg-secondary rounded p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="mb-0 text-white">Daftar Layanan GPU Tersedia</h6>
                        <a href="tambah.php" class="btn btn-sm btn-primary"><i class="fa fa-plus me-1"></i> Tambah Layanan</a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0 text-white">
                            <thead class="text-light text-center bg-dark">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Foto</th>
                                    <th scope="col">Nama GPU</th>
                                    <th scope="col">Harga / Jam</th>
                                    <th scope="col">Kebutuhan</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; while($row = mysqli_fetch_array($data)) { ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td class="text-center">
                                        <img src="assets/img/<?= htmlspecialchars($row['foto']); ?>" alt="GPU" style="width: 80px; height: 50px; object-fit: cover; border-radius: 5px;">
                                    </td>
                                    <td><?= htmlspecialchars($row['nama_gpu']); ?></td>
                                    <td class="text-center">Rp <?= number_format((int)$row['harga'], 0, ',', '.'); ?></td>
                                    <td><?= htmlspecialchars($row['kebutuhan']); ?></td>
                                    <td class="text-center">
                                        <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-info text-dark mb-1"><i class="fa fa-edit"></i> Edit</a>
                                        <a href="hapus.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Yakin ingin menghapus layanan ini?')"><i class="fa fa-trash"></i> Hapus</a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>