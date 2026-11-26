<?php
session_start();

if (!isset($_SESSION['login'])) {
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
                header("Location: dashboard.php?status=success_add");
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
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Dashboard Admin</title>
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
        }

        .alert-error {
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

        .preview-img {
            display: none;
            margin-top: 12px;
            max-width: 120px;
            max-height: 120px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
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
            <h2>Tambah Produk Baru</h2>
            <p>Masukkan detail data produk affiliate ke dalam database</p>
        </div>

        <form action="" method="POST" enctype="multipart/form-data">

            <?php if ($pesan != ''): ?>
                <div class="alert alert-error">
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
                        <img id="imgPreview" class="preview-img" alt="Preview Gambar">
                    </center>
                </div>
            </div>

            <div class="form-group">
                <label>Link Affiliate</label>
                <input type="text" name="link_affiliate" placeholder="https://shope.ee/..." required>
            </div>

            <div class="btn-group">
                <a href="dashboard.php" class="btn btn-back">← Kembali</a>
                <button type="submit" name="submit" class="btn btn-submit">Simpan Produk</button>
            </div>

        </form>
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