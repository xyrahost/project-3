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
    <title>Login - <?php echo SITE_NAME; ?></title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">

    <h2>Login</h2>

    <?php showFlash(); ?>
    <form action="auth/login_process.php" method="POST">

        <label>Email</label>
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
        
        <label style="display:flex;align-items:center;gap:8px;margin-bottom:15px;">
    <input
        type="checkbox"
        name="remember_me"
        value="1"
        style="width:auto;"
    >
    Remember Me (ingat saya selama 3 hari)
</label>

<input
    type="hidden"
    name="csrf_token"
    value="<?php echo generateCSRFToken(); ?>"
>

        <button type="submit">
            Login
        </button>

    </form>

    <br>

    <a href="forgot-password.php">
        Lupa Password?
    </a>

    <br><br>

    Belum punya akun?

    <a href="register.php">
        Daftar
    </a>

</div>

</body>
</html>