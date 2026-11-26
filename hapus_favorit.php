<?php
session_start();
include 'config/koneksi.php';

// 1. Pastikan user sudah login
if (!isset($_SESSION['login']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// 2. Periksa apakah parameter ID favorit tersedia di URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $favorite_id = (int)$_GET['id'];

    // 3. Hapus data menggunakan Prepared Statement
    // PENTING: Pengecekan 'AND user_id = ?' mencegah user A menghapus favorit milik user B
    $stmt = $conn->prepare("DELETE FROM favorites WHERE id = ? AND user_id = ?");
    
    if ($stmt) {
        $stmt->bind_param("ii", $favorite_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }
}

// 4. Kembalikan pengguna ke halaman favorit
header("Location: favorit.php");
exit;
?>