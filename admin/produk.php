<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include 'config/koneksi.php';
$query = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
$total_produk = mysqli_num_rows($query);
$active = 'produk';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Dashboard Admin</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="dashboard-body">

<div class="dashboard-layout">

<?php include 'partials/sidebar.php'; ?>

<main class="main-content">

    <div class="admin-header">
        <h2>Kelola Produk</h2>
        <p><?php echo $total_produk; ?> produk terdaftar</p>

        <div class="top-bar">
            <div class="top-bar-left">
                <a href="tambah.php" class="btn btn-add">+ Tambah Produk</a>
            </div>
        </div>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'success_edit'): ?>
            <div class="alert-success">Produk berhasil diperbarui.</div>
        <?php elseif (isset($_GET['status']) && $_GET['status'] === 'success_add'): ?>
            <div class="alert-success">Produk baru berhasil ditambahkan.</div>
        <?php elseif (isset($_GET['status']) && $_GET['status'] === 'success_hapus'): ?>
            <div class="alert-success">Produk berhasil dihapus.</div>
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

</main>

</div>

</body>

</html>
