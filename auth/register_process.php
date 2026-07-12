<?php

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../config/flash.php";
require_once "../config/csrf.php";

// Hanya menerima request POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../register.php");
    exit;
}

verifyCSRFToken($_POST["csrf_token"] ?? "");

// Ambil data dari form
$full_name = trim($_POST["full_name"]);
$username = trim($_POST["username"]);
$email = trim($_POST["email"]);
$password = $_POST["password"];
$confirm_password = $_POST["confirm_password"];

// Validasi input
if (
    empty($full_name) ||
    empty($username) ||
    empty($email) ||
    empty($password) ||
    empty($confirm_password)
) {
    setFlash("error", "Semua data wajib diisi.");
    header("Location: ../register.php");
    exit;
}

// Validasi Username
if (
    strlen($username) < 4 ||
    strlen($username) > 30
) {

    setFlash(
        "error",
        "Username harus 4-30 karakter."
    );

    header("Location: ../register.php");
    exit;

}

if (!preg_match('/^[A-Za-z0-9_]+$/', $username)) {

    setFlash(
        "error",
        "Username hanya boleh huruf, angka, dan underscore (_)."
    );

    header("Location: ../register.php");
    exit;

}

// Validasi Password
if (strlen($password) < 8) {

    setFlash(
        "error",
        "Password minimal 8 karakter."
    );

    header("Location: ../register.php");
    exit;

}

if (preg_match('/\s/', $password)) {

    setFlash(
        "error",
        "Password tidak boleh mengandung spasi."
    );

    header("Location: ../register.php");
    exit;

}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setFlash("error", "Format email tidak valid.");
    header("Location: ../register.php");
    exit;
}

if ($password !== $confirm_password) {
    setFlash("error", "Konfirmasi password tidak sama.");
    header("Location: ../register.php");
    exit;
}

if (strlen($password) < 8) {
    setFlash("error", "Password minimal 8 karakter.");
    header("Location: ../register.php");
    exit;
}

// Cek email atau username sudah digunakan
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
$stmt->bind_param("ss", $email, $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();

    setFlash("error", "Email atau Username sudah digunakan.");
    header("Location: ../register.php");
    exit;
}

$stmt->close();

// Hash password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Generate token verifikasi
$verify_token = bin2hex(random_bytes(32));

// Simpan ke database
$stmt = $conn->prepare("
INSERT INTO users (
    full_name,
    username,
    email,
    password,
    verify_token
) VALUES (?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "sssss",
    $full_name,
    $username,
    $email,
    $password_hash,
    $verify_token
);

if ($stmt->execute()) {

    require_once "../mail/verify_email.php";

    if (sendVerificationEmail($email, $full_name, $verify_token)) {

        setFlash(
            "success",
            "Pendaftaran berhasil. Silakan cek email Anda untuk verifikasi akun."
        );

    } else {

        setFlash(
            "warning",
            "Akun berhasil dibuat, tetapi email verifikasi gagal dikirim."
        );

    }

    header("Location: ../login.php");
    exit;

} else {

    setFlash(
        "error",
        "Terjadi kesalahan saat menyimpan data."
    );

    header("Location: ../register.php");
    exit;

}

$stmt->close();
$conn->close();