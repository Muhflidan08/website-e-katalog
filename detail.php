<?php
session_start();
include 'config/koneksi.php';

// Guest boleh langsung lihat detail produk tanpa login.
// Login hanya diwajibkan saat menekan tombol Favorit.

// ambil id dari URL
$id = $_GET['id'];

// ambil data berdasarkan id
$query = mysqli_query($conn, "SELECT * FROM products WHERE id = '$id'");

$data = mysqli_fetch_assoc($query);

// Cek status login (tanpa memaksa redirect di sini)
$isLoggedIn = isset($_SESSION['login']);
$user_id    = $isLoggedIn ? $_SESSION['user_id'] : null;

// Default: belum favorit (khusus untuk guest / belum login)
$isFav = false;

// Hanya cek status favorit kalau user sudah login
if ($isLoggedIn) {
    $cekFav = mysqli_query($conn, "SELECT * FROM favorites 
    WHERE user_id='$user_id' AND product_id='$id'");

    $isFav = mysqli_num_rows($cekFav) > 0;
}

// Proses toggle favorit
if (isset($_POST['toggle_fav'])) {

    // Kalau belum login, tendang ke halaman login dulu
    if (!$isLoggedIn) {
        header("Location: login.php");
        exit;
    }

    if ($isFav) {
        // hapus favorit
        mysqli_query($conn, "DELETE FROM favorites 
        WHERE user_id='$user_id' AND product_id='$id'");
    } else {
        // tambah favorit
        mysqli_query($conn, "INSERT INTO favorites (user_id, product_id) 
        VALUES ('$user_id', '$id')");
    }

    // refresh halaman
    header("Location: detail.php?id=$id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk</title>
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
    <div class="detail-container">
        <a href="index.php" class="btn-kembali">← Kembali</a>
        <div class="detail-top">

            <div class="detail-image">
                <img src="uploads/<?php echo $data['gambar']; ?>" alt="">
            </div>

            <div class="detail-info">
                <h1><?php echo $data['nama_produk']; ?></h1>
                <h3>Kategori: <?php echo htmlspecialchars($data['kategori'] ?? 'Umum'); ?></h3>

                <h2>Rp <?php echo number_format($data['harga'], 0, ',', '.'); ?></h2>

                <a href="<?php echo $data['link_affiliate']; ?>" target="_blank" class="btn-beli">
                    Beli Sekarang
                </a>

                <form method="POST">
                    <button type="submit" name="toggle_fav" class="btn-fav">
                        <?php
                            if (!$isLoggedIn) {
                                echo '❤ Favorit (Login dulu)';
                            } else {
                                echo $isFav ? '❤ Hapus Favorit' : '❤ Favorit';
                            }
                        ?>
                    </button>
                </form>

                <div class="info-box">
                    <h3>Kenapa harus beli disini?</h3>
                    <p>✔ Produk Original</p>
                    <p>✔ Produk Asli Dari Toko Langsung</p>
                    <p>✔ Terpercaya & Amanah</p>
                </div>
            </div>
        </div>
        <div class="detail-deskripsi">
            <h3>Deskripsi Produk</h3>
            <p><?php echo $data['deskripsi']; ?></p>
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