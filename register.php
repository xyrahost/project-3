<?php
require_once "config/guest.php";
require_once "config/flash.php";
require_once "config/csrf.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - <?php echo SITE_NAME; ?></title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">

    <h2>Daftar Akun</h2>
    <p>Silakan buat akun baru.</p>

    <?php showFlash(); ?>
    <form action="auth/register_process.php" method="POST">

        <label>Nama Lengkap</label>
        <input
            type="text"
            name="full_name"
            required
        >

        <label>Username</label>
        <input
            type="text"
            name="username"
            required
        >

        <label>Email Gmail</label>
        <input
            type="email"
            name="email"
            required
        >

        <label>Password</label>
        <input
            type="password"
            name="password"
            required
        >

        <label>Konfirmasi Password</label>
        <input
            type="password"
            name="confirm_password"
            required
        >

 <input
    type="hidden"
    name="csrf_token"
    value="<?php echo generateCSRFToken(); ?>"
 >

        <button type="submit">
            Daftar
        </button>

    </form>

    <br>

    Sudah punya akun?
    <a href="login.php">
        Login
    </a>

</div>

</body>
</html>