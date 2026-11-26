<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Website - E-Commerce Affiliate</title>
    <link rel="stylesheet" href="style1.css">
</head>

<body>

    <!-- NAVBAR -->
    <div class="navbar">


        <div class="nav-center">
            <form action="index.php" method="get">
                <input type="text" name="search" placeholder="Cari produk...">
                <button type="submit">Cari</button>
            </form>
        </div>

        <div class="nav-right">
            <?php if (isset($_SESSION['login'])) { ?>
                <a href="profile.php">Profil</a>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') { ?>
                    <a href="admin/dashboard.php">Dashboard</a>
                <?php } ?>
                <a href="logout.php" onclick="return confirm('Yakin ingin logout?')">Logout</a>
            <?php } else { ?>
                <a href="login.php">Login</a>
            <?php } ?>
        </div>
    </div>

    <!-- HERO HEADER ABOUT -->
    <div class="about-hero">
        <div class="about-hero-content">
            <h1>Tentang Website Ini</h1>
            <p>Platform E-Commerce Affiliate Modern hasil Praktik Kerja Lapangan (PKL)</p>
        </div>
    </div>

    <!-- MAIN CONTENT CONTAINER -->
    <div class="about-wrapper">
        
        <!-- CARD DESKRIPSI UTAMA -->
        <div class="about-card-modern">
            <h2>🚀 Latar Belakang & Tujuan</h2>
            <p>
                Website e-commerce ini dikembangkan sebagai bagian dari <b>Tugas Akhir Praktik Kerja Lapangan (PKL)</b>. 
                Platform ini dirancang khusus untuk mempermudah pengguna dalam menemukan produk-produk rekomendasi affiliate berkualitas, 
                sekaligus memberikan kemudahan bagi admin dalam mengelola katalog produk secara efisien.
            </p>
            <p>
                Pengguna dapat menjelajahi berbagai kategori produk, melihat detail spesifikasi, serta langsung menuju tautan pembelian resmi yang aman.
            </p>
        </div>

        <!-- GRID INFORMASI FITUR & TEKNOLOGI -->
        <div class="about-grid">
            
            <div class="about-grid-card">
                <div class="grid-icon">✨</div>
                <h3>Fitur Utama Platform</h3>
                <ul>
                    <li>🔐 Authentication (Login & Register User/Admin)</li>
                    <li>📦 Manajemen Produk & Kategori (CRUD Admin)</li>
                    <li>🔍 Pencarian & Filter</li>
                    <li>⭐ Fitur Produk Favorit / Wishlist</li>
                    <li>👤 Pengaturan Profil & Bio Pengguna</li>
                </ul>
            </div>

            <div class="about-grid-card">
                <div class="grid-icon">🛠️</div>
                <h3>Bahasa pemograman yang Digunakan</h3>
                <ul>
                    <li><b>PHP (Native)</b> - Backend Logic & Session Management</li>
                    <li><b>MySQL</b> - Database Relasional & Query Prepared Statement</li>
                    <li><b>HTML5 & CSS3</b> - Responsive Modern UI Design</li>
                </ul>
            </div>
        </div>
        <!-- CARD INSTITUSI & DUKUNGAN -->
        <div class="about-card-modern collaboration-card">
            <h2>🤝 Mitra Perusahaan & Instansi Sekolah 🤝</h2>
            <div class="collab-logos">
                <div class="collab-item">
                    <img src="sakubi.webp" alt="PT. Sakubi Teknologi Indonesia">
                    <h4>PT. Sakubi Teknologi Indonesia</h4>
                    <p>Perusahaan Mitra Tempat Pelaksanaan PKL</p>
                </div>
                <div class="collab-divider"></div>
                <div class="collab-item1">
                    <img src="dwigunada.png" alt="SMK TI Dwiguna">
                    <h4>SMK TI Dwiguna</h4>
                    <p>Instansi Pendidikan Penyelenggara</p>
                </div>
            </div>
        </div>

        <!-- ACTION BUTTON -->
        <div class="about-action">
            <a href="index.php" class="btn-primary-about">← Kembali Menjelajah Produk</a>
        </div>

    </div>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-col footer-left">
                <img src="sakubi.webp" alt="Logo Sakubi" class="footer-logo">
                <p class="footer-desc">PT. Sakubi Teknologi Indonesia</p>
                <nav class="footer-links">
                    <a href="https://api.whatsapp.com/send/?phone=6285770707243&text=Halo+Sakubi+Teknologi+Indonesia" target="_blank">Kontak Sakubi</a>
                    <a href="https://www.sakubi.id/#about" target="_blank">Tentang PT. Sakubi</a>
                    <a href="https://www.sakubi.id/" target="_blank">Situs Resmi</a>
                </nav>
            </div>

            <div class="footer-col footer-center">
                <div class="brand-badge">🛒 E-Commerce Platform</div>
                <p class="brand-tagline">Platform Produk Affiliate Terbaik 🚀</p>
                <nav class="footer-links">
                    <a href="about.php">Tentang Web Ini</a>
                    <a href="anggota.php">Anggota Tim PKL PT Sakubi</a>
                    <a href="index.php">Daftar Produk</a>
                </nav>
            </div>

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

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> E-Commerce Affiliate. Tugas Akhir PKL.</p>
        </div>
    </footer>

</body>

</html>