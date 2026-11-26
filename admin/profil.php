<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

include 'config/koneksi.php';

$id = $_SESSION['user_id'];
$pesan = '';
$status = '';

// PROSES UPDATE PROFIL
if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Cek apakah username/email sudah dipakai akun lain
    $check = mysqli_query($conn, "SELECT id FROM users WHERE (username='$username' OR email='$email') AND id != '$id'");

    if (mysqli_num_rows($check) > 0) {
        $pesan = "Username atau Email sudah dipakai akun lain!";
        $status = "error";
    } elseif ($password !== '' && $password !== $confirm_password) {
        $pesan = "Konfirmasi password baru tidak cocok!";
        $status = "error";
    } else {
        if ($password !== '') {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = mysqli_query($conn, "UPDATE users SET username='$username', email='$email', password='$hashed_password' WHERE id='$id'");
        } else {
            $query = mysqli_query($conn, "UPDATE users SET username='$username', email='$email' WHERE id='$id'");
        }

        if ($query) {
            $_SESSION['username'] = $username;
            $pesan = "Profil berhasil diperbarui.";
            $status = "success";
        } else {
            $pesan = "Gagal memperbarui profil!";
            $status = "error";
        }
    }
}

// AMBIL DATA TERBARU
$query_get = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");
$row = mysqli_fetch_assoc($query_get);

if (!$row) {
    header("Location: dashboard.php");
    exit;
}

$active = 'profil';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin - Dashboard Admin</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="dashboard-body">

<div class="dashboard-layout">

<?php include 'partials/sidebar.php'; ?>

<main class="main-content form-page-body">

    <div class="form-container">
        <div class="form-header">
            <h2>Profil Admin</h2>
            <p>Kelola data akun admin yang sedang login</p>
        </div>

        <form action="" method="POST">

            <?php if (!empty($pesan)): ?>
                <div class="alert <?php echo $status === 'success' ? 'alert-ok' : ''; ?>">
                    <?php echo htmlspecialchars($pesan); ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
            </div>

            <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
            </div>

            <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <input type="password" name="confirm_password" placeholder="Ulangi password baru">
            </div>

            <div class="btn-group">
                <button type="submit" name="submit" class="btn btn-submit">Simpan Perubahan</button>
            </div>

        </form>
    </div>

</main>

</div>

</body>
</html>
