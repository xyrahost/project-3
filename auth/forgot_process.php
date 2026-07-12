<?php

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../config/flash.php";
require_once "../mail/reset_email.php";
require_once "../config/csrf.php";

// Hanya menerima request POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../forgot-password.php");
    exit;
}

verifyCSRFToken($_POST["csrf_token"] ?? "");

// Ambil email
$email = trim($_POST["email"]);

if (empty($email)) {
    setFlash("error", "Email wajib diisi.");
    header("Location: ../forgot-password.php");
    exit;
}

// Cari user
$stmt = $conn->prepare("
SELECT
    id,
    full_name,
    email
FROM users
WHERE email = ?
LIMIT 1
");

$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {

    setFlash("error", "Email tidak ditemukan.");

    header("Location: ../forgot-password.php");
    exit;
}

$user = $result->fetch_assoc();

// Buat token reset
$reset_token = bin2hex(random_bytes(32));

// Token berlaku 1 jam
$expired_at = date("Y-m-d H:i:s", strtotime("+1 hour"));

// Simpan token ke database
$stmt = $conn->prepare("
UPDATE users
SET
    reset_token = ?,
    reset_token_expired_at = ?
WHERE id = ?
");

$stmt->bind_param(
    "ssi",
    $reset_token,
    $expired_at,
    $user["id"]
);

if ($stmt->execute()) {

    if (
        sendResetPasswordEmail(
            $user["email"],
            $user["full_name"],
            $reset_token
        )
    ) {

        setFlash(
            "success",
            "Link reset password berhasil dikirim ke email Anda."
        );

    } else {

        setFlash(
            "error",
            "Email gagal dikirim."
        );

    }

} else {

    setFlash(
        "error",
        "Terjadi kesalahan."
    );

}

header("Location: ../forgot-password.php");
exit;