<?php
session_start();
include 'config/koneksi.php';

// Pastikan user sudah login DAN user_id terdefinisi di session
if (!isset($_SESSION['login']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Mengambil data produk favorit milik user
$query = mysqli_query($conn, "
    SELECT products.*, favorites.id AS favorite_id 
    FROM favorites
    JOIN products ON favorites.product_id = products.id
    WHERE favorites.user_id = '$user_id'
    ORDER BY favorites.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk Favorit - E-Commerce</title>
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
                <?php if ($_SESSION['role'] == 'user') { ?>
                    <a href="favorit.php">Favorit</a>
                <?php } ?>
                <a href="logout.php" onclick="return confirm('Yakin ingin logout?')">
                    Logout
                </a>
            <?php } else { ?>
                <a href="login.php">Login</a>
            <?php } ?>
        </div>

    </div>
    <div class="page-container">
        <a href="index.php" class="back-link">← Kembali ke Beranda</a>
        <h1 class="page-title">Produk Favorit Saya ❤️</h1>

        <?php if (mysqli_num_rows($query) > 0): ?>
            <div class="favorite-grid">
                <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                    <div class="product-card">
                        <img src="uploads/<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['nama_produk']); ?>" class="product-img">
                        
                        <div class="product-info">
                            <span class="product-category"><?php echo htmlspecialchars($row['kategori']); ?></span>
                            <h3 class="product-name"><?php echo htmlspecialchars($row['nama_produk']); ?></h3>
                            <div class="product-price">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></div>
                            
                            <div class="product-actions">
                                <a href="detail.php?id=<?php echo $row['id']; ?>" class="btn-detail">Lihat Detail</a>
                                <a href="hapus_favorit.php?id=<?php echo $row['favorite_id']; ?>" class="btn-remove" onclick="return confirm('Hapus dari favorit?')">Hapus</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <p>Belum ada produk favorit yang disimpan.</p>
                <a href="index.php" class="btn-shop">Jelajahi Produk</a>
            </div>
        <?php endif; ?>
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