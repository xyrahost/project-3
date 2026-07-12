<?php

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../config/flash.php";
require_once "../config/csrf.php";

// Hanya menerima POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: ../change-password.php");
    exit;

}

// Cek CSRF
verifyCSRFToken($_POST["csrf_token"] ?? "");

// Ambil data
$old_password = $_POST["old_password"];
$new_password = $_POST["new_password"];
$confirm_password = $_POST["confirm_password"];

// Validasi
if (
    empty($old_password) ||
    empty($new_password) ||
    empty($confirm_password)
) {

    setFlash(
        "error",
        "Semua field wajib diisi."
    );

    header("Location: ../change-password.php");
    exit;

}

// Password minimal 8 karakter
if (strlen($new_password) < 8) {

    setFlash(
        "error",
        "Password minimal 8 karakter."
    );

    header("Location: ../change-password.php");
    exit;

}

// Password tidak boleh ada spasi
if (preg_match('/\s/', $new_password)) {

    setFlash(
        "error",
        "Password tidak boleh mengandung spasi."
    );

    header("Location: ../change-password.php");
    exit;

}

// Konfirmasi password
if ($new_password !== $confirm_password) {

    setFlash(
        "error",
        "Konfirmasi password tidak cocok."
    );

    header("Location: ../change-password.php");
    exit;

}

// Ambil password lama dari database
$stmt = $conn->prepare("
SELECT password
FROM users
WHERE id = ?
LIMIT 1
");

$stmt->bind_param(
    "i",
    $_SESSION["user_id"]
);

$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

// Cek password lama
if (!password_verify($old_password, $user["password"])) {

    setFlash(
        "error",
        "Password lama salah."
    );

    header("Location: ../change-password.php");
    exit;

}

// Hash password baru
$newHash = password_hash(
    $new_password,
    PASSWORD_DEFAULT
);

// Update password
$stmt = $conn->prepare("
UPDATE users
SET
    password = ?,
    updated_at = NOW()
WHERE id = ?
");

$stmt->bind_param(
    "si",
    $newHash,
    $_SESSION["user_id"]
);

if ($stmt->execute()) {

    session_regenerate_id(true);

    setFlash(
        "success",
        "Password berhasil diubah."
    );

    header("Location: ../profile.php");
    exit;

}

setFlash(
    "error",
    "Terjadi kesalahan saat mengubah password."
);

header("Location: ../change-password.php");
exit;
