<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include 'config/koneksi.php';

$pesan = '';
$status = '';

if (isset($_POST['submit'])) {
    // Amankan input dari SQL Injection
    $nama_produk    = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $kategori       = mysqli_real_escape_string($conn, $_POST['kategori']);
    $deskripsi      = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $harga          = mysqli_real_escape_string($conn, $_POST['harga']);
    $link_affiliate = mysqli_real_escape_string($conn, $_POST['link_affiliate']);

    // Proses Upload Gambar
    $filename = $_FILES['gambar']['name'];
    if ($filename != "") {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $gambar = time() . '_' . uniqid() . '.' . $ext;
        $tmp = $_FILES['gambar']['tmp_name'];

        if (move_uploaded_file($tmp, '../uploads/' . $gambar)) {
            $query = mysqli_query($conn, "INSERT INTO products (nama_produk, deskripsi, harga, gambar, link_affiliate, kategori) 
            VALUES ('$nama_produk', '$deskripsi', '$harga', '$gambar', '$link_affiliate', '$kategori')");

            if ($query) {
                header("Location: produk.php?status=success_add");
                exit;
            } else {
                $pesan = "Gagal menyimpan data ke database!";
                $status = "error";
            }
        } else {
            $pesan = "Gagal mengunggah file gambar!";
            $status = "error";
        }
    } else {
        $pesan = "Harap pilih gambar produk!";
        $status = "error";
    }
}

$active = 'produk';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Dashboard Admin</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="dashboard-body">

<div class="dashboard-layout">

<?php include 'partials/sidebar.php'; ?>

<main class="main-content form-page-body">

    <div class="form-container">
        <div class="form-header">
            <h2>Tambah Produk Baru</h2>
            <p>Masukkan detail data produk affiliate ke dalam database</p>
        </div>

        <form action="" method="POST" enctype="multipart/form-data">

            <?php if ($pesan != ''): ?>
                <div class="alert">
                    <?php echo $pesan; ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama_produk" placeholder="Contoh: Lip Serum Bibir 4ml Lightglam" required>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    
                    <!-- Kecantikan & Perawatan -->
                    <option value="Skincare">Skincare</option>
                    <option value="Make Up">Make Up</option>
                    <option value="Body Care">Body Care</option>
                    <option value="Perawatan Rambut">Perawatan Rambut</option>
                    <option value="Parfum & Wewangian">Parfum & Wewangian</option>

                    <!-- Fashion & Aksesoris -->
                    <option value="Fashion Pria">Fashion Pria</option>
                    <option value="Fashion Wanita">Fashion Wanita</option>
                    <option value="Fashion Muslim">Fashion Muslim</option>
                    <option value="Sepatu & Sandal">Sepatu & Sandal</option>
                    <option value="Tas & Dompet">Tas & Dompet</option>
                    <option value="Aksesoris & Perhiasan">Aksesoris & Perhiasan</option>

                    <!-- Elektronik & Gadget -->
                    <option value="Elektronik Rumah Tangga">Elektronik Rumah Tangga</option>
                    <option value="Gadget & Smartphone">Gadget & Smartphone</option>
                    <option value="Aksesoris HP & Komputer">Aksesoris HP & Komputer</option>
                    <option value="Kamera & Audio">Kamera & Audio</option>

                    <!-- Makanan & Kesehatan -->
                    <option value="Makanan & Minuman">Makanan & Minuman</option>
                    <option value="Kesehatan & Suplemen">Kesehatan & Suplemen</option>

                    <!-- Perlengkapan Rumah & Gaya Hidup -->
                    <option value="Kebutuhan Rumah Tangga">Kebutuhan Rumah Tangga</option>
                    <option value="Dapur & Alat Makan">Dapur & Alat Makan</option>
                    <option value="Ibu & Bayi">Ibu & Bayi</option>
                    <option value="Olahraga & Outdoor">Olahraga & Outdoor</option>
                    <option value="Otomotif & Aksesoris">Otomotif & Aksesoris</option>
                    <option value="Hobi & Mainan">Hobi & Mainan</option>
                </select>
            </div>

            <div class="form-group">
                <label>Deskripsi Produk</label>
                <textarea name="deskripsi" placeholder="Tuliskan deskripsi lengkap produk..."></textarea>
            </div>

            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="harga" placeholder="Contoh: 106000" required>
            </div>

            <div class="form-group">
                <label>Gambar Produk</label>
                <div class="file-upload-box">
                    <input type="file" name="gambar" id="inputGambar" accept="image/*" onchange="previewImage()" required>
                    <center>
                        <img id="imgPreview" class="preview-img" style="display: none;" alt="Preview Gambar">
                    </center>
                </div>
            </div>

            <div class="form-group">
                <label>Link Affiliate</label>
                <input type="text" name="link_affiliate" placeholder="https://shope.ee/..." required>
            </div>

            <div class="btn-group">
                <a href="produk.php" class="btn btn-back">← Kembali</a>
                <button type="submit" name="submit" class="btn btn-submit">Simpan Produk</button>
            </div>

        </form>
    </div>

</main>

</div>

<script>
    // Fungsi Preview Gambar sebelum diupload
    function previewImage() {
        const input = document.getElementById('inputGambar');
        const preview = document.getElementById('imgPreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

</body>

</html>
