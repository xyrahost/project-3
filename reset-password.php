<?php

require_once "config/guest.php";
require_once "config/database.php";
require_once "config/flash.php";
require_once "config/csrf.php";

if (!isset($_GET["token"]) || empty($_GET["token"])) {
    die("Token tidak ditemukan.");
}

$token = trim($_GET["token"]);

$stmt = $conn->prepare("
SELECT id
FROM users
WHERE reset_token = ?
AND reset_token_expired_at > NOW()
LIMIT 1
");

$stmt->bind_param("s", $token);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Token tidak valid atau sudah kedaluwarsa.");
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Reset Password</title>

<link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="container">

<h2>Reset Password</h2>

<?php showFlash(); ?>

<form action="auth/reset_process.php" method="POST">

<input
type="hidden"
name="token"
value="<?php echo htmlspecialchars($token); ?>"
>

<label>Password Baru</label>

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
Simpan Password Baru
</button>

</form>

</div>

</body>
</html>