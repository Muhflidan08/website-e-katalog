<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include 'config/koneksi.php';

$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

if (empty($id)) {
    header("Location: user.php");
    exit;
}

// Hanya boleh menghapus akun dengan role 'user' (member), bukan admin
$delete = mysqli_query($conn, "DELETE FROM users WHERE id='$id' AND role='user'");

if ($delete) {
    header("Location: user.php?status=success_hapus");
} else {
    header("Location: user.php");
}
exit;
?>
