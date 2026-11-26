<?php
session_start();
include 'config/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anggota PKL</title>
    <link rel="stylesheet" href="style1.css">
</head>

<body>

    <div class="navbar">
        <!-- Tengah -->
        <div class="nav-center">
            <form action="index.php" method="get">
                <input type="text" name="search" placeholder="Cari produk...">
                <button type="submit">Cari</button>
            </form>
        </div>

        <!-- Kanan -->
        <div class="nav-right">
            <?php if (isset($_SESSION['login'])) { ?>
                <a href="profile.php">Profil</a>
                <?php if ($_SESSION['role'] == 'admin') { ?>
                    <a href="admin/dashboard.php">Dashboard</a>
                <?php } ?>
                <?php if ($_SESSION['role'] == 'user') { ?>
                    <a href="favorit.php">Favorit</a>
                <?php } ?>
                <a href="logout.php" onclick="return confirm('Yakin ingin logout?')">Logout</a>
            <?php } else { ?>
                <a href="login.php">Login</a>
            <?php } ?>
        </div>
    </div>

    <!-- HERO ANGGOTA -->
    <section class="about-hero">
        <div class="about-hero-content">
            <h1>Anggota Tim PKL</h1>
            <p>Tim Siswa PKL SMK TI Dwiguna di PT. Sakubi Teknologi Indonesia</p>
        </div>
    </section>

    <div class="about-action">
            <a href="index.php" class="btn-primary-about">← Kembali Menjelajah Produk</a>
        </div>

    <!-- FOOTER -->
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
                    <a href="https://smkdwiguna.sch.id/profile-sekolah/" target="_blank">Profil Sekolah</a>
                    <a href="https://smkdwiguna.sch.id/kontak-kami/" target="_blank">Kontak Sekolah</a>
                    <a href="https://smkdwiguna.sch.id/" target="_blank">Website Sekolah</a>
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
