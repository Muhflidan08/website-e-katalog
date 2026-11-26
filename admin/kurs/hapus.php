<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include 'config/koneksi.php';

// ambil id produk dari URL
$id = $_GET['id'];

// ambil data gambar berdasarkan id
$query = mysqli_query($conn, "SELECT * FROM products WHERE id = '$id'");

if (file_exists($gambar)) {
    unlink($gambar);
}

// hapus data dari database
$delete = mysqli_query($conn, "DELETE FROM products WHERE id = '$id'");

// cek hasil
if ($delete) {
    header("Location: dashboard.php");
} else {
    echo "Gagal menghapus produk!";
}
?>