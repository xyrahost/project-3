<?php

require_once "config/config.php";
require_once "config/database.php";

// Hapus remember token dari database
if (isset($_SESSION["user_id"])) {

    $stmt = $conn->prepare("
    UPDATE users
    SET
        remember_token = NULL,
        remember_expired_at = NULL
    WHERE id = ?
    ");

    $stmt->bind_param(
        "i",
        $_SESSION["user_id"]
    );

    $stmt->execute();
}

// Hapus cookie Remember Me
setcookie(
    "remember_token",
    "",
    time() - 3600,
    "/"
);

// Hapus session
session_unset();
session_destroy();

// Kembali ke halaman login
header("Location: login.php");
exit;