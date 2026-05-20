<?php
require_once 'koneksi.php';

if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? '';

if(!empty($id)){
    // Ambil info gambar dulu biar sekalian dihapus filenya
    $stmt = mysqli_prepare($conn, "SELECT foto FROM gpu_services WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if($row && file_exists("assets/img/".$row['foto']) && !empty($row['foto'])){
        unlink("assets/img/".$row['foto']);
    }

    // Hapus dari database
    $stmt_del = mysqli_prepare($conn, "DELETE FROM gpu_services WHERE id = ?");
    mysqli_stmt_bind_param($stmt_del, 'i', $id);
    mysqli_stmt_execute($stmt_del);
    mysqli_stmt_close($stmt_del);
}

header("Location: dashboard.php?msg=deleted");
exit;
?>