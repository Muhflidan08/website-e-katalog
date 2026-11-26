<?php
session_start();
include 'config/koneksi.php';

// PROSES LOGIN
if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Login pakai username ATAU email
    $query = mysqli_query($conn, "
        SELECT * FROM users 
        WHERE username='$username' OR email='$username'
    ");

    $data = mysqli_fetch_assoc($query);

    if ($data) {
        if (password_verify($password, $data['password'])) {

            $_SESSION['login'] = true;
            $_SESSION['username'] = $data['username'];
            $_SESSION['role'] = $data['role'];
            $_SESSION['user_id'] = $data['id'];

            if ($data['role'] == 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit;

        } else {
            echo "<script>alert('Password salah!');</script>";
        }
    } else {
        echo "<script>alert('Username / Email tidak ditemukan!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Commerce Affiliate</title>
    <link rel="stylesheet" href="style1.css">
</head>

<body>

<div class="auth-container">
    <div class="auth-box">

        <h1>Login</h1>
        <a href="index.php" class="btn-back">← Kembali ke Beranda</a>

        <form action="" method="POST">
            <input type="text" name="username" placeholder="Username atau Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit" name="login">Login</button>
        </form>

        <p>Belum punya akun? <a href="register.php">Register</a></p>

    </div>
</div>

<footer class="footer">
    <div class="footer-container">
        <!-- KOLOM 1: Logo Sakubi -->
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