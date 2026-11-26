<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include 'config/koneksi.php';

// Amankan ID dari URL
$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

if (empty($id)) {
    header("Location: produk.php");
    exit;
}

$pesan = '';

// PROSES UPDATE
if (isset($_POST['submit'])) {
    $nama_produk    = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $kategori       = mysqli_real_escape_string($conn, $_POST['kategori']);
    $deskripsi      = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $harga          = mysqli_real_escape_string($conn, $_POST['harga']);
    $link_affiliate = mysqli_real_escape_string($conn, $_POST['link_affiliate']);

    if ($_FILES['gambar']['name'] != "") {
        $filename = $_FILES['gambar']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $gambar = time() . '_' . uniqid() . '.' . $ext;
        $tmp = $_FILES['gambar']['tmp_name'];

        if (move_uploaded_file($tmp, '../uploads/' . $gambar)) {
            $query = mysqli_query($conn, "UPDATE products SET 
                nama_produk='$nama_produk',
                kategori='$kategori',
                deskripsi='$deskripsi',
                harga='$harga',
                gambar='$gambar',
                link_affiliate='$link_affiliate'
                WHERE id='$id'");
        } else {
            $pesan = "Gagal mengunggah file gambar baru!";
        }
    } else {
        $query = mysqli_query($conn, "UPDATE products SET 
            nama_produk='$nama_produk',
            kategori='$kategori',
            deskripsi='$deskripsi',
            harga='$harga',
            link_affiliate='$link_affiliate'
            WHERE id='$id'");
    }

    if (isset($query) && $query) {
        header("Location: produk.php?status=success_edit");
        exit;
    } else if (empty($pesan)) {
        $pesan = "Gagal memperbarui data produk!";
    }
}

// AMBIL DATA PRODUK BERDASARKAN ID
$query_get = mysqli_query($conn, "SELECT * FROM products WHERE id = '$id'");
$row = mysqli_fetch_assoc($query_get);

if (!$row) {
    header("Location: produk.php");
    exit;
}

// Fungsi pembantu agar opsi kategori terpilih secara otomatis
function is_selected($value, $db_value) {
    return ($value == $db_value) ? 'selected' : '';
}

$active = 'produk';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Dashboard Admin</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="dashboard-body">

<div class="dashboard-layout">

<?php include 'partials/sidebar.php'; ?>

<main class="main-content form-page-body">

    <div class="form-container">
        <div class="form-header">
            <h2>Edit Produk</h2>
            <p>Perbarui rincian informasi produk affiliate ini</p>
        </div>

        <form action="" method="POST" enctype="multipart/form-data">

            <?php if (!empty($pesan)): ?>
                <div class="alert">
                    <?php echo $pesan; ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama_produk" value="<?php echo htmlspecialchars($row['nama_produk']); ?>" required>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    
                    <!-- Kecantikan & Perawatan -->
                    <option value="Skincare" <?php echo is_selected('Skincare', $row['kategori']); ?>>Skincare</option>
                    <option value="Make Up" <?php echo is_selected('Make Up', $row['kategori']); ?>>Make Up</option>
                    <option value="Body Care" <?php echo is_selected('Body Care', $row['kategori']); ?>>Body Care</option>
                    <option value="Perawatan Rambut" <?php echo is_selected('Perawatan Rambut', $row['kategori']); ?>>Perawatan Rambut</option>
                    <option value="Parfum & Wewangian" <?php echo is_selected('Parfum & Wewangian', $row['kategori']); ?>>Parfum & Wewangian</option>

                    <!-- Fashion & Aksesoris -->
                    <option value="Fashion Pria" <?php echo is_selected('Fashion Pria', $row['kategori']); ?>>Fashion Pria</option>
                    <option value="Fashion Wanita" <?php echo is_selected('Fashion Wanita', $row['kategori']); ?>>Fashion Wanita</option>
                    <option value="Fashion Muslim" <?php echo is_selected('Fashion Muslim', $row['kategori']); ?>>Fashion Muslim</option>
                    <option value="Sepatu & Sandal" <?php echo is_selected('Sepatu & Sandal', $row['kategori']); ?>>Sepatu & Sandal</option>
                    <option value="Tas & Dompet" <?php echo is_selected('Tas & Dompet', $row['kategori']); ?>>Tas & Dompet</option>
                    <option value="Aksesoris & Perhiasan" <?php echo is_selected('Aksesoris & Perhiasan', $row['kategori']); ?>>Aksesoris & Perhiasan</option>

                    <!-- Elektronik & Gadget -->
                    <option value="Elektronik Rumah Tangga" <?php echo is_selected('Elektronik Rumah Tangga', $row['kategori']); ?>>Elektronik Rumah Tangga</option>
                    <option value="Gadget & Smartphone" <?php echo is_selected('Gadget & Smartphone', $row['kategori']); ?>>Gadget & Smartphone</option>
                    <option value="Aksesoris HP & Komputer" <?php echo is_selected('Aksesoris HP & Komputer', $row['kategori']); ?>>Aksesoris HP & Komputer</option>
                    <option value="Kamera & Audio" <?php echo is_selected('Kamera & Audio', $row['kategori']); ?>>Kamera & Audio</option>

                    <!-- Makanan & Kesehatan -->
                    <option value="Makanan & Minuman" <?php echo is_selected('Makanan & Minuman', $row['kategori']); ?>>Makanan & Minuman</option>
                    <option value="Kesehatan & Suplemen" <?php echo is_selected('Kesehatan & Suplemen', $row['kategori']); ?>>Kesehatan & Suplemen</option>

                    <!-- Perlengkapan Rumah & Gaya Hidup -->
                    <option value="Kebutuhan Rumah Tangga" <?php echo is_selected('Kebutuhan Rumah Tangga', $row['kategori']); ?>>Kebutuhan Rumah Tangga</option>
                    <option value="Dapur & Alat Makan" <?php echo is_selected('Dapur & Alat Makan', $row['kategori']); ?>>Dapur & Alat Makan</option>
                    <option value="Ibu & Bayi" <?php echo is_selected('Ibu & Bayi', $row['kategori']); ?>>Ibu & Bayi</option>
                    <option value="Olahraga & Outdoor" <?php echo is_selected('Olahraga & Outdoor', $row['kategori']); ?>>Olahraga & Outdoor</option>
                    <option value="Otomotif & Aksesoris" <?php echo is_selected('Otomotif & Aksesoris', $row['kategori']); ?>>Otomotif & Aksesoris</option>
                    <option value="Hobi & Mainan" <?php echo is_selected('Hobi & Mainan', $row['kategori']); ?>>Hobi & Mainan</option>
                </select>
            </div>

            <div class="form-group">
                <label>Deskripsi Produk</label>
                <textarea name="deskripsi"><?php echo htmlspecialchars($row['deskripsi']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="harga" value="<?php echo htmlspecialchars($row['harga']); ?>" required>
            </div>

            <div class="form-group">
                <label>Gambar Produk</label>
                <div class="file-upload-box">
                    <input type="file" name="gambar" id="inputGambar" accept="image/*" onchange="previewImage()">
                    <div class="preview-wrapper">
                        <img id="imgPreview" class="preview-img" src="../uploads/<?php echo htmlspecialchars($row['gambar']); ?>" alt="Preview Gambar">
                        <span class="preview-label" id="previewText">Gambar saat ini (Kosongkan jika tidak ingin diganti)</span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Link Affiliate</label>
                <input type="text" name="link_affiliate" value="<?php echo htmlspecialchars($row['link_affiliate']); ?>" required>
            </div>

            <div class="btn-group">
                <a href="produk.php" class="btn btn-back">← Batal</a>
                <button type="submit" name="submit" class="btn btn-submit">Simpan Perubahan</button>
            </div>

        </form>
    </div>

</main>

</div>

<script>
    // Preview Real-time saat mengganti file gambar
    function previewImage() {
        const input = document.getElementById('inputGambar');
        const preview = document.getElementById('imgPreview');
        const text = document.getElementById('previewText');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                text.innerText = "Preview Gambar Baru";
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

</body>

</html>
