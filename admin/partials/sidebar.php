<?php
// $active harus di-set di halaman pemanggil sebelum include file ini.
// Contoh: $active = 'produk';
if (!isset($active)) { $active = ''; }

function nav_active($page, $active) {
    return $page === $active ? 'active' : '';
}
?>
<aside class="sidebar">
    <div class="sidebar-brand">🛒 Admin Panel</div>

    <nav class="sidebar-menu">
        <a href="dashboard.php" class="<?php echo nav_active('dashboard', $active); ?>">
            <svg viewBox="0 0 24 24"><path d="M3 11.5 12 4l9 7.5"/><path d="M5 10v9h14v-9"/></svg>
            Dashboard
        </a>
        <a href="produk.php" class="<?php echo nav_active('produk', $active); ?>">
            <svg viewBox="0 0 24 24"><path d="M3 7l9-4 9 4-9 4-9-4z"/><path d="M3 7v10l9 4 9-4V7"/></svg>
            Kelola Produk
        </a>
        <a href="user.php" class="<?php echo nav_active('user', $active); ?>">
            <svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3.2"/><path d="M2.5 19c0-3.5 3-6 6.5-6s6.5 2.5 6.5 6"/><path d="M16 8.2a3 3 0 1 1 3.7 2.9"/><path d="M17 13.3c2.6.5 4.5 2.6 4.5 5.7"/></svg>
            Kelola User
        </a>
        <a href="profil.php" class="<?php echo nav_active('profil', $active); ?>">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg>
            Profil Admin
        </a>

        <div class="sidebar-divider"></div>

        <a href="../index.php">
            <svg viewBox="0 0 24 24"><path d="M3 11.5 12 4l9 7.5"/><path d="M5 10v9h14v-9"/></svg>
            Halaman Utama
        </a>
        <a href="../logout.php" class="sidebar-logout">
            <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
            Logout
        </a>
    </nav>
</aside>
