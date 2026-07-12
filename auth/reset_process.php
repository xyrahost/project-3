<?php

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../config/flash.php";
require_once "../config/csrf.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../login.php");
    exit;
}

verifyCSRFToken($_POST["csrf_token"] ?? "");

$token = trim($_POST["token"]);
$password = $_POST["password"];
$confirm_password = $_POST["confirm_password"];

if (empty($password) || empty($confirm_password)) {
    setFlash("error", "Semua data wajib diisi.");
    header("Location: ../reset-password.php?token=".$token);
    exit;
}

// Validasi Password
if (strlen($password) < 8) {

    setFlash(
        "error",
        "Password minimal 8 karakter."
    );

    header("Location: ../reset-password.php?token=" . urlencode($token));
    exit;

}

if (preg_match('/\s/', $password)) {

    setFlash(
        "error",
        "Password tidak boleh mengandung spasi."
    );

    header("Location: ../reset-password.php?token=" . urlencode($token));
    exit;

}

if ($password !== $confirm_password) {
    setFlash("error", "Konfirmasi password tidak sama.");
    header("Location: ../reset-password.php?token=".$token);
    exit;
}

if (strlen($password) < 8) {
    setFlash("error", "Password minimal 8 karakter.");
    header("Location: ../reset-password.php?token=".$token);
    exit;
}

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

    setFlash("error", "Token reset tidak valid.");

    header("Location: ../forgot-password.php");
    exit;
}

$user = $result->fetch_assoc();

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("
UPDATE users
SET
password = ?,
reset_token = NULL,
reset_token_expired_at = NULL
WHERE id = ?
");

$stmt->bind_param(
    "si",
    $password_hash,
    $user["id"]
);

if ($stmt->execute()) {

    setFlash(
        "success",
        "Password berhasil diubah. Silakan login."
    );

    header("Location: ../login.php");
    exit;

}

setFlash(
    "error",
    "Terjadi kesalahan."
);

header("Location: ../forgot-password.php");
exit;