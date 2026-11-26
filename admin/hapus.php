<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include 'config/koneksi.php';

// Amankan ID dari URL
$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

if (empty($id)) {
    header("Location: produk.php");
    exit;
}

// ambil data produk (termasuk nama file gambar) berdasarkan id
$query = mysqli_query($conn, "SELECT * FROM products WHERE id = '$id'");
$row = mysqli_fetch_assoc($query);

if (!$row) {
    header("Location: produk.php");
    exit;
}

// hapus file gambar dari folder uploads jika ada
$path_gambar = '../uploads/' . $row['gambar'];
if (!empty($row['gambar']) && file_exists($path_gambar)) {
    unlink($path_gambar);
}

// hapus data dari database
$delete = mysqli_query($conn, "DELETE FROM products WHERE id = '$id'");

// cek hasil
if ($delete) {
    header("Location: produk.php?status=success_hapus");
} else {
    header("Location: produk.php");
}
exit;
?>
