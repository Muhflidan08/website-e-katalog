<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include 'config/koneksi.php';
$query = mysqli_query($conn, "SELECT * FROM users WHERE role='user' ORDER BY id DESC");
$total_user = mysqli_num_rows($query);
$active = 'user';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User - Dashboard Admin</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="dashboard-body">

<div class="dashboard-layout">

<?php include 'partials/sidebar.php'; ?>

<main class="main-content">

    <div class="admin-header">
        <h2>Kelola User</h2>
        <p><?php echo $total_user; ?> user terdaftar sebagai member</p>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'success_hapus'): ?>
            <div class="alert-success">User berhasil dihapus.</div>
        <?php endif; ?>
    </div>

    <div class="table-wrapper">

        <?php if ($total_user === 0): ?>

            <div class="empty-state">
                <strong>Belum ada user</strong>
                <p>Belum ada member yang mendaftar di situs ini.</p>
            </div>

        <?php else: ?>

        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($query)) {
                ?>
                <tr>
                    <td class="text-center"><strong><?php echo $no++; ?></strong></td>
                    <td><strong><?php echo htmlspecialchars($row['username']); ?></strong></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td>
                        <div class="aksi">
                            <a href="hapus_user.php?id=<?php echo $row['id']; ?>" class="btn btn-action hapus" onclick="return confirm('Yakin ingin menghapus user ini?')">Hapus</a>
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
