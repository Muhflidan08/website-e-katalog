<?php
session_start();
include 'config/koneksi.php';

// PROSES REGISTRASI
if (isset($_POST['register'])) {

    $username         = mysqli_real_escape_string($conn, $_POST['username']);
    $email            = mysqli_real_escape_string($conn, $_POST['email']);
    $password         = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "<script>alert('Konfirmasi password tidak cocok!');</script>";
    } else {
        // Cek ketersediaan username / email
        $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' OR email='$email'");
        if (mysqli_num_rows($check) > 0) {
            echo "<script>alert('Username atau Email sudah terdaftar!');</script>";
        } else {
            // Hash password untuk keamanan
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'user'; // Default role

            $insert = mysqli_query($conn, "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashed_password', '$role')");

            if ($insert) {
                echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='login.php';</script>";
            } else {
                echo "<script>alert('Gagal mendaftar, coba lagi!');</script>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - E-Commerce Affiliate</title>
    <link rel="stylesheet" href="style1.css">
</head>

<body>

<div class="auth-container">
    <div class="auth-box">

        <h1>Register</h1>

        <form action="" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>

            <button type="submit" name="register">Register</button>
        </form>

        <p>Sudah punya akun? <a href="login.php">Login</a></p>

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