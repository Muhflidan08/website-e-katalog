<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include 'config/koneksi.php';

// Amankan ID dari URL
$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

if (empty($id)) {
    header("Location: dashboard.php");
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
        header("Location: dashboard.php?status=success_edit");
        exit;
    } else if (empty($pesan)) {
        $pesan = "Gagal memperbarui data produk!";
    }
}

// AMBIL DATA PRODUK BERDASARKAN ID
$query_get = mysqli_query($conn, "SELECT * FROM products WHERE id = '$id'");
$row = mysqli_fetch_assoc($query_get);

if (!$row) {
    header("Location: dashboard.php");
    exit;
}

// Fungsi pembantu agar opsi kategori terpilih secara otomatis
function is_selected($value, $db_value) {
    return ($value == $db_value) ? 'selected' : '';
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Dashboard Admin</title>
    <style>
        /* RESET & BASE */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f8fafc;
            color: #0f172a;
            min-height: 100vh;
            padding: 40px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* CONTAINER */
        .form-container {
            width: 100%;
            max-width: 580px;
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        /* HEADER BANNER */
        .form-header {
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            color: white;
            padding: 24px;
            text-align: center;
        }

        .form-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .form-header p {
            font-size: 0.88rem;
            color: rgba(255, 255, 255, 0.85);
            margin-top: 4px;
        }

        /* FORM BODY */
        form {
            padding: 28px 32px;
        }

        /* ALERT PESAN */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 0.88rem;
            margin-bottom: 20px;
            font-weight: 500;
            background: #fff1f2;
            color: #e11d48;
            border: 1px solid #fecdd3;
        }

        /* FORM GROUP */
        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.88rem;
            font-weight: 600;
            color: #1e293b;
        }

        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.9rem;
            color: #0f172a;
            background-color: #ffffff;
            font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #0ea5e9;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15);
        }

        textarea {
            resize: vertical;
            min-height: 90px;
        }

        /* CUSTOM FILE INPUT & PREVIEW */
        .file-upload-box {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            padding: 16px;
            border-radius: 8px;
            text-align: center;
        }

        .preview-wrapper {
            margin-top: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .preview-img {
            max-width: 120px;
            max-height: 120px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .preview-label {
            font-size: 0.75rem;
            color: #64748b;
        }

        /* BUTTONS */
        .btn-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 28px;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.25s ease;
            cursor: pointer;
            border: none;
        }

        .btn-submit {
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            color: white;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
            flex: 1;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
        }

        .btn-back {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }

        .btn-back:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        /* RESPONSIVE */
        @media (max-width: 600px) {
            body {
                padding: 16px;
            }

            form {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

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
                <a href="dashboard.php" class="btn btn-back">← Batal</a>
                <button type="submit" name="submit" class="btn btn-submit">Simpan Perubahan</button>
            </div>

        </form>
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