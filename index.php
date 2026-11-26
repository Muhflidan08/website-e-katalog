<?php
session_start();
include 'config/koneksi.php';

// Ambil input pencarian & kategori jika ada
$keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
$kategori = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';

// 1. Susun query dasar
$sql = "SELECT * FROM products WHERE 1=1";
$min = isset($_GET['min']) ? (int)$_GET['min'] : 0;
$max = isset($_GET['max']) ? (int)$_GET['max'] : 0;

if ($min > 0) {
    $sql .= " AND harga >= $min";
}

if ($max > 0) {
    $sql .= " AND harga <= $max";
}
// 2. Tambahkan filter jika user mencari keyword
if (!empty($keyword)) {
    // Sanitasi input agar aman dari SQL Injection
    $keyword_clean = mysqli_real_escape_string($conn, $keyword);
    $sql .= " AND (nama_produk LIKE '%$keyword_clean%' OR deskripsi LIKE '%$keyword_clean%')";
}

// 3. Tambahkan filter jika user memilih kategori
if (!empty($kategori)) {
    $kategori_clean = mysqli_real_escape_string($conn, $kategori);
    $sql .= " AND kategori='$kategori_clean'";
}

// 4. Tambahkan ORDER BY RAND() agar produk muncul secara acak setiap refresh
$sql .= " ORDER BY RAND()";

// 5. Jalankan query
$query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk Affiliate</title>
    <link rel="stylesheet" href="style1.css">
</head>

<body>

    <div class="navbar">
        <!-- Tengah -->
        <div class="nav-center">
            <form action="index.php" method="get">
                <input type="text" name="search" placeholder="Cari produk..." value="<?php echo htmlspecialchars($keyword); ?>">
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

    <!-- Judul Ucapan -->
    <h2 class="welcome-title" style="text-align: center; margin: 24px 0 16px 0; color: #0f172a; font-size: 1.5rem;">
        Selamat Datang, 
        <span style="color: #0ea5e9;"><a href="profile.php"><?php echo isset($_SESSION['login']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?></a></span> 
        Di Website E-Commerce
    </h2>

    <div class="main-container">

        <!-- SIDEBAR KIRI -->
        <div class="sidebar">

            <!-- PROFILE MINI -->
            <?php if (isset($_SESSION['login'])) { ?>
                <div class="sidebar-box">
                    <h3>👤 Profil</h3>
                    <p style="margin: 0 0 8px 0; font-weight: 600; color: #334155;"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                    <a href="profile.php" style="color: #0ea5e9; font-weight: 600; font-size: 0.85rem;">Lihat Profil &rarr;</a>
                </div>
            <?php } ?>

            <!-- KATEGORI -->
            <div class="sidebar-box">
                <h3>📂 Kategori</h3>
                <ul>
                    <li><a href="index.php">Semua Produk</a></li>
                    <li><a href="index.php?kategori=Skincare">Skincare</a></li>
                    <li><a href="index.php?kategori=Make Up">Make Up</a></li>
                    <li><a href="index.php?kategori=Body Care">Body Care</a></li>
                    <li><a href="index.php?kategori=Fashion Pria">Fashion Pria</a></li>
                    <li><a href="index.php?kategori=Gadget & Smartphone">Gadget</a></li>
                </ul>
            </div>

            <!-- FILTER HARGA -->
            <div class="sidebar-box">
                <h3>💰 Filter Harga</h3>
                <form method="GET" action="index.php">
                    <?php if (!empty($keyword)) { ?><input type="hidden" name="search" value="<?php echo htmlspecialchars($keyword); ?>"><?php } ?>
                    <?php if (!empty($kategori)) { ?><input type="hidden" name="kategori" value="<?php echo htmlspecialchars($kategori); ?>"><?php } ?>
                    
                    <input type="number" name="min" placeholder="Harga Minimum (Rp)" value="<?php echo $min > 0 ? $min : ''; ?>">
                    <input type="number" name="max" placeholder="Harga Maksimum (Rp)" value="<?php echo $max > 0 ? $max : ''; ?>">
                    <button type="submit">Terapkan Filter</button>
                </form>
            </div>

            <!-- FAVORIT -->
            <?php if (isset($_SESSION['login'])) { ?>
                <div class="sidebar-box">
                    <h3>❤️ Favorit</h3>
                    <a href="favorit.php">Lihat Favorit Saya</a>
                </div>
            <?php } ?>

        </div>


        <!-- PRODUK KANAN -->
        <div class="produk-area">
            <div class="content-produk">
                <?php if (mysqli_num_rows($query) > 0) { ?>
                    <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                        <a href="detail.php?id=<?php echo $row['id']; ?>" class="card">
                            <img src="uploads/<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['nama_produk']); ?>">
                            <h3><?php echo htmlspecialchars($row['nama_produk']); ?></h3>
                            <p><?php echo htmlspecialchars($row['kategori']); ?></p>
                            <p>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                        </a>
                    <?php } ?>
                <?php } else { ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: white; border-radius: 12px; border: 1px dashed #cbd5e1;">
                        <p style="color: #64748b; font-size: 1rem; margin: 0;">Produk tidak ditemukan.</p>
                    </div>
                <?php } ?>
            </div>
        </div>

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