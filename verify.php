<?php

require_once "config/config.php";
require_once "config/database.php";

if (!isset($_GET['token']) || empty($_GET['token'])) {
    die("Token tidak ditemukan.");
}

$token = trim($_GET['token']);

$stmt = $conn->prepare("
SELECT id
FROM users
WHERE verify_token = ?
LIMIT 1
");

$stmt->bind_param("s", $token);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Token verifikasi tidak valid.");
}

$user = $result->fetch_assoc();

$stmt = $conn->prepare("
UPDATE users
SET
    status = 'active',
    email_verified_at = NOW(),
    verify_token = NULL
WHERE id = ?
");

$stmt->bind_param("i", $user['id']);

if ($stmt->execute()) {
    echo "
    <h2>Email berhasil diverifikasi</h2>
    <p>Akun Anda sudah aktif.</p>
    <a href='login.php'>Login Sekarang</a>
    ";
} else {
    echo "Gagal memverifikasi akun.";
}

$stmt->close();
$conn->close();