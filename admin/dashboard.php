<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include 'config/koneksi.php';

$total_produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM products"))['total'];
$total_user   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='user'"))['total'];
$total_admin  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='admin'"))['total'];

$active = 'dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-body">

<div class="dashboard-layout">

    <!-- SIDEBAR -->
    <?php include 'partials/sidebar.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="main-content">

        <div class="dash-topbar">
            <h2>Dashboard</h2>
            <div class="dash-topbar-user">Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></strong></div>
        </div>

        <div class="stat-cards">

            <div class="stat-card stat-blue">
                <div class="stat-card-top">
                    <div class="stat-card-number"><?php echo $total_produk; ?></div>
                    <div class="stat-card-icon">
                        <svg viewBox="0 0 24 24"><path d="M3 7l9-4 9 4-9 4-9-4z"/><path d="M3 7v10l9 4 9-4V7"/></svg>
                    </div>
                </div>
                <div class="stat-card-label">Produk</div>
                <a href="produk.php" class="stat-card-link">Kelola Produk →</a>
            </div>

            <div class="stat-card stat-green">
                <div class="stat-card-top">
                    <div class="stat-card-number"><?php echo $total_user; ?></div>
                    <div class="stat-card-icon">
                        <svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3.2"/><path d="M2.5 19c0-3.5 3-6 6.5-6s6.5 2.5 6.5 6"/></svg>
                    </div>
                </div>
                <div class="stat-card-label">User Terdaftar</div>
                <a href="user.php" class="stat-card-link">Kelola User →</a>
            </div>

            <div class="stat-card stat-amber">
                <div class="stat-card-top">
                    <div class="stat-card-number"><?php echo $total_admin; ?></div>
                    <div class="stat-card-icon">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg>
                    </div>
                </div>
                <div class="stat-card-label">Administrator</div>
                <a href="profil.php" class="stat-card-link">Profil Admin →</a>
            </div>

        </div>

    </main>

</div>

</body>
</html>
