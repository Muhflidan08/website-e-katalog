<?php
session_start();
include 'config/koneksi.php';

// ✅ WAJIB: cek login dulu
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

// ✅ Ambil user_id dari session
$user_id = $_SESSION['user_id'];

// ✅ Ambil data user
$query = mysqli_prepare($conn, "SELECT username, bio, foto FROM users WHERE id = ?");
mysqli_stmt_bind_param($query, "i", $user_id);
mysqli_stmt_execute($query);
$result = mysqli_stmt_get_result($query);
$user = mysqli_fetch_assoc($result);

// simpan data lama
$old_foto = $user['foto'] ?? 'default.jpg';
$current_bio = $user['bio'] ?? '';
$current_username = $user['username'] ?? '';

// =====================
// PROSES UPDATE
// =====================
if (isset($_POST['update'])) {

    $bio = trim($_POST['bio']);
    $new_username = trim($_POST['username']);

    // cek username sudah dipakai atau belum
    $cek = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ? AND id != ?");
    mysqli_stmt_bind_param($cek, "si", $new_username, $user_id);
    mysqli_stmt_execute($cek);
    $result_cek = mysqli_stmt_get_result($cek);

    if (mysqli_num_rows($result_cek) > 0) {
        echo "<script>alert('Username sudah digunakan!');</script>";
    } else {

        // ===== FOTO =====
        if (!empty($_FILES['foto']['name'])) {

            $foto_name = $_FILES['foto']['name'];
            $tmp_name  = $_FILES['foto']['tmp_name'];
            $file_size = $_FILES['foto']['size'];

            $ext = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($ext, $allowed) && $file_size <= 2000000) {

                $new_foto_name = uniqid('profile_', true) . '.' . $ext;
                move_uploaded_file($tmp_name, "uploads/" . $new_foto_name);

                // hapus foto lama
                if ($old_foto !== 'default.jpg' && file_exists("uploads/" . $old_foto)) {
                    unlink("uploads/" . $old_foto);
                }

                $stmt = mysqli_prepare(
                    $conn,
                    "UPDATE users SET username=?, bio=?, foto=? WHERE id=?"
                );
                mysqli_stmt_bind_param($stmt, "sssi", $new_username, $bio, $new_foto_name, $user_id);
            }
        } else {
            $stmt = mysqli_prepare(
                $conn,
                "UPDATE users SET username=?, bio=? WHERE id=?"
            );
            mysqli_stmt_bind_param($stmt, "ssi", $new_username, $bio, $user_id);
        }

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // ✅ update session
        $_SESSION['username'] = $new_username;

        header("Location: profile.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - E-Commerce</title>
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

    <!-- MAIN EDIT PROFILE CONTAINER -->
    <div class="edit-profile-wrapper">
        <div class="edit-profile-card">
            <div class="edit-header-banner">
                <a href="profile.php" class="btn-back-link">← Batal & Kembali</a>
                <h2>✏️ Edit Profil Saya</h2>
            </div>

            <form action="" method="post" enctype="multipart/form-data" class="edit-form-body">

                <!-- Preview Foto & Upload Input -->
                <div class="avatar-upload-group">
                    <div class="avatar-preview">
                        <img id="imgPreview" src="uploads/<?= !empty($old_foto) ? htmlspecialchars($old_foto) : 'default.jpg'; ?>" alt="Foto Profil">
                    </div>
                    <div class="avatar-input">
                        <label for="foto" class="btn-choose-file">📷 Pilih Foto Baru</label>
                        <input type="file" name="foto" id="foto" accept="image/*" onchange="previewImage(event)">
                        <span class="file-info">Format: JPG, PNG, WEBP (Maks. 2MB)</span>
                    </div>
                </div>

                <!-- Input Field Groups -->
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($current_username); ?>" required placeholder="Masukkan username baru...">
                </div>

                <div class="form-group">
                    <label for="bio">Bio / Deskripsi Singkat</label>
                    <textarea name="bio" id="bio" rows="4" placeholder="Tuliskan bio singkat tentang diri Anda..."><?= htmlspecialchars($current_bio); ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" name="update" class="btn-save" onclick="return confirm('Simpan perubahan profil?')">Simpan Perubahan</button>
                </div>

            </form>
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

    <!-- Script Preview Gambar Instan -->
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('imgPreview');
                output.src = reader.result;
            };
            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
</body>

</html>