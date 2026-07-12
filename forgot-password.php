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

<title>Lupa Password</title>

<link rel="stylesheet" href="css/style.css">

</head>
<body>

<div class="container">

<h2>Lupa Password</h2>

<p>Masukkan email akun Anda.</p>

<?php showFlash(); ?>

<form action="auth/forgot_process.php" method="POST">

<label>Email</label>

<input
type="email"
name="email"
required
>

<input
    type="hidden"
    name="csrf_token"
    value="<?php echo generateCSRFToken(); ?>"
>

<button type="submit">
Kirim Link Reset Password
</button>

</form>

<br>

<a href="login.php">
Kembali ke Login
</a>

</div>

</body>
</html>