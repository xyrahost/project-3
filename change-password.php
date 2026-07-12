<?php

require_once "config/auth.php";
require_once "config/csrf.php";
require_once "config/flash.php";

?>

<?php require_once "includes/header.php"; ?>

<?php require_once "includes/navbar.php"; ?>

<div class="container">

<h2>🔑 Ganti Password</h2>

<?php showFlash(); ?>

<form action="auth/change_password_process.php" method="POST">

<input
    type="hidden"
    name="csrf_token"
    value="<?php echo generateCSRFToken(); ?>"
>

<label>Password Lama</label>

<input
    type="password"
    name="old_password"
    required
>

<label>Password Baru</label>

<input
    type="password"
    name="new_password"
    required
>

<label>Konfirmasi Password Baru</label>

<input
    type="password"
    name="confirm_password"
    required
>

<button type="submit">
Simpan Password
</button>

</form>

<br>

<a href="profile.php">
← Kembali ke Profil
</a>

</div>

<?php require_once "includes/footer.php"; ?>