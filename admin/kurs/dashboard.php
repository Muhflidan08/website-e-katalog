<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include 'config/koneksi.php';
$query = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
$total_produk = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Produk - Dashboard Admin</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <div class="admin-header">
        <h2>Dashboard Admin</h2>
        <p>Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></strong> — <?php echo $total_produk; ?> produk terdaftar</p>

        <div class="top-bar">
            <div class="top-bar-left">
                <a href="tambah.php" class="btn btn-add">+ Tambah Produk</a>
            </div>
            <div class="top-bar-right">
                <a href="../index.php" class="btn btn-back">Halaman Utama</a>
                <a href="../logout.php" class="btn btn-logout">Logout</a>
            </div>
        </div>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'success_edit'): ?>
            <div class="alert-success">Produk berhasil diperbarui.</div>
        <?php elseif (isset($_GET['status']) && $_GET['status'] === 'success_add'): ?>
            <div class="alert-success">Produk baru berhasil ditambahkan.</div>
        <?php endif; ?>
    </div>

    <div class="table-wrapper">

        <?php if ($total_produk === 0): ?>

            <div class="empty-state">
                <strong>Belum ada produk</strong>
                <p>Klik "+ Tambah Produk" untuk mulai menambahkan produk affiliate.</p>
            </div>

        <?php else: ?>

        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th style="width: 90px;">Gambar</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($query)) {
                ?>
                <tr>
                    <td class="text-center"><strong><?php echo $no++; ?></strong></td>
                    <td>
                        <img src="../uploads/<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['nama_produk']); ?>">
                    </td>
                    <td><strong><?php echo htmlspecialchars($row['nama_produk']); ?></strong></td>
                    <td>
                        <span class="badge-kategori">
                            <?php echo htmlspecialchars($row['kategori'] ?? 'Umum'); ?>
                        </span>
                    </td>
                    <td class="price-text">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td>
                        <div class="aksi">
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-action edit">Edit</a>
                            <a href="hapus.php?id=<?php echo $row['id']; ?>" class="btn btn-action hapus" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <?php endif; ?>

    </div>

</div>

</body>

</html>
