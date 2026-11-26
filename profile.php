<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// ambil data user dari database
$query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil user</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <div class="navbar">
    <!-- tengah -->
    <div class="nav-center">
        <form action="index.php" method="get">
            <input type="text" name="search" placeholder="Cari produk...">
            <button type="submit">Cari</button>
        </form>
    </div>

    <!-- kanan -->
    <div class="nav-right">

        <?php if (isset($_SESSION['login'])) { ?>
            <a href="profile.php">Profil</a>
            <?php if ($_SESSION['role'] == 'admin') { ?>
                <a href="admin/dashboard.php">Dashboard</a>
            <?php } ?>
            <a href="logout.php" onclick="return confirm('Yakin ingin logout?')">
                Logout
            </a>
        <?php } else { ?>
            <a href="login.php">Login</a>
        <?php } ?>
    </div>

</div>
    <div class="profile-wrapper">
    <!-- Header Card Profil Utama -->
    <div class="profile-card-modern">
        <div class="profile-cover"></div>
        <div class="profile-body">
            <div class="avatar-wrapper">
                <img src="uploads/<?php echo $data['foto'] ? $data['foto'] : 'default.jpg'; ?>" alt="Foto Profil">
            </div>
            <span class="user-role-badge"><?php echo strtoupper($data['role'] ?? 'User'); ?></span>
            <h2><?php echo htmlspecialchars($data['username']); ?></h2>
            <p class="user-bio"><?php echo htmlspecialchars($data['email']); ?></p>
            <p class="user-bio"><?php echo htmlspecialchars($data['bio'] ? $data['bio'] : 'Belum ada bio singkat.'); ?></p>
            
            <div class="profile-actions">
                <a href="index.php" class="btn-secondary">← Kembali</a>
                <a href="edit_profile.php" class="btn-primary">✏️ Edit Profil</a>
            </div>
        </div>
    </div>

    <!-- Ringkasan Aktivitas / Stats (Membuat Halaman Terisi Seimbang) -->
    <div class="profile-stats-grid">
        <div class="stat-card">
            <div class="stat-icon">❤️</div>
            <div class="stat-info">
                <h3>Favorit Saya</h3>
                <p>Akses produk tersimpan</p>
            </div>
            <a href="favorit.php" class="stat-link">Lihat Favorit →</a>
        </div>

        <div class="stat-card">
            <div class="stat-icon">🛡️</div>
            <div class="stat-info">
                <h3>Keamanan Akun</h3>
                <p>Status: <span class="badge-active">Aktif</span></p>
            </div>
            <span class="stat-subtext">Terverifikasi</span>
        </div>

        <div class="stat-card">
            <div class="stat-icon">🛒</div>
            <div class="stat-info">
                <h3>E-Commerce</h3>
                <p>Jelajahi catalog affiliate</p>
            </div>
            <a href="index.php" class="stat-link">Cari Produk →</a>
        </div>
    </div>
</div>
<footer class="footer">
    <div class="footer-container">
        <!-- KOLOM 1: Logo Sakubi / Tempat PKL -->
        <div class="footer-col footer-left">
            <img src="sakubi.webp" alt="Logo Sakubi" class="footer-logo">
            <p class="footer-desc">PT. Sakubi Teknologi Indonesia</p>
            <nav class="footer-links">
                <a href="https://api.whatsapp.com/send/?phone=6285770707243&text=Halo+Sakubi+Teknologi+Indonesia+Saya+ingin+melakukan+konsultasi+mengenai+layanan+Sakubi+Teknologi+Indonesia&type=phone_number&app_absent=0" target="_blank">Kontak Sakubi</a>
                <a href="https://www.sakubi.id/#about" target="_blank">Tentang PT. Sakubi</a>
                <a href="https://www.sakubi.id/" target="_blank">Situs Resmi</a>
            </nav>
        </div>

        <!-- KOLOM 2: Informasi Platform -->
        <div class="footer-col footer-center">
            <div class="brand-badge">🛒 E-Commerce Platform</div>
            <p class="brand-tagline">Platform Produk Affiliate Terbaik 🚀</p>
            <nav class="footer-links">
                <a href="about.php">Tentang Web Ini</a>
                <a href="anggota.php">Anggota Tim PKL PT Sakubi</a>
                <a href="index.php">Daftar Produk</a>
            </nav>
        </div>

        <!-- KOLOM 3: Logo Sekolah -->
        <div class="footer-col footer-right">
            <img src="dwigunada.png" alt="Logo Sekolah" class="footer-logo logo-school">
            <p class="footer-desc">SMK TI Dwiguna</p>
            <nav class="footer-links">
                <a href="https://smkdwiguna.sch.id/profile-sekolah/">Profil Sekolah</a>
                <a href="https://smkdwiguna.sch.id/kontak-kami/">Kontak Sekolah</a>
                <a href="https://smkdwiguna.sch.id/">Website Sekolah</a>
            </nav>
        </div>
    </div>

    <!-- HAK CIPTA & CREDITS -->
    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> E-Commerce Affiliate. Tugas Akhir PKL.</p>
    </div>
</footer>
</body>
</html>