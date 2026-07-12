<?php

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../config/flash.php";
require_once "../config/csrf.php";

// Hanya menerima request POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../login.php");
    exit;
}

verifyCSRFToken($_POST["csrf_token"] ?? "");

// Ambil data dari form
$email = trim($_POST["email"]);
$password = $_POST["password"];
$remember_me = isset($_POST["remember_me"]);

// Validasi input
if (empty($email) || empty($password)) {
    setFlash("error", "Email dan password wajib diisi.");
    header("Location: ../login.php");
    exit;
}

// Cari user
$stmt = $conn->prepare("
SELECT
    id,
    full_name,
    username,
    email,
    password,
    status,
    profile_photo,
    login_attempts,
    lock_until
FROM users
WHERE email = ?
LIMIT 1
");

$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    setFlash("error", "Email atau password salah.");
    header("Location: ../login.php");
    exit;
}

$user = $result->fetch_assoc();

// Cek apakah akun sedang dikunci
if (
    !empty($user["lock_until"]) &&
    strtotime($user["lock_until"]) > time()
) {

    setFlash(
        "error",
        "Terlalu banyak percobaan login. Silakan coba lagi dalam 20 detik."
    );

    header("Location: ../login.php");
    exit;

}

// Cek password
if (!password_verify($password, $user["password"])) {

    $attempts = $user["login_attempts"] + 1;

    // Jika sudah 3 kali gagal
    if ($attempts >= 3) {

        $lock_until = date(
            "Y-m-d H:i:s",
            strtotime("+20 seconds")
        );

        $stmt = $conn->prepare("
        UPDATE users
        SET
            login_attempts = ?,
            lock_until = ?
        WHERE id = ?
        ");

        $stmt->bind_param(
            "isi",
            $attempts,
            $lock_until,
            $user["id"]
        );

    } else {

        $stmt = $conn->prepare("
        UPDATE users
        SET
            login_attempts = ?
        WHERE id = ?
        ");

        $stmt->bind_param(
            "ii",
            $attempts,
            $user["id"]
        );

    }

    $stmt->execute();

    setFlash("error", "Email atau password salah.");
    header("Location: ../login.php");
    exit;

}

// Cek status akun
if ($user["status"] !== "active") {
    setFlash("warning", "Silakan verifikasi email terlebih dahulu.");
    header("Location: ../login.php");
    exit;
}

// Reset login attempts jika login berhasil
$stmt = $conn->prepare("
UPDATE users
SET
    login_attempts = 0,
    lock_until = NULL
WHERE id = ?
");

$stmt->bind_param(
    "i",
    $user["id"]
);

$stmt->execute();

session_regenerate_id(true);
// Simpan session
$_SESSION["user_id"] = $user["id"];
$_SESSION["full_name"] = $user["full_name"];
$_SESSION["username"] = $user["username"];
$_SESSION["email"] = $user["email"];
$_SESSION["profile_photo"] = $user["profile_photo"];
$_SESSION["last_activity"] = time();

// ==========================================
// REMEMBER ME
// ==========================================

if ($remember_me) {

    $remember_token = bin2hex(random_bytes(32));

    $expired_at = date(
        "Y-m-d H:i:s",
        strtotime("+3 days")
    );

    $stmt = $conn->prepare("
    UPDATE users
    SET
        remember_token = ?,
        remember_expired_at = ?
    WHERE id = ?
    ");

    $stmt->bind_param(
        "ssi",
        $remember_token,
        $expired_at,
        $user["id"]
    );

    $stmt->execute();

    setcookie(
        "remember_token",
        $remember_token,
        time() + (60 * 60 * 24 * 3),
        "/",
        "",
        isset($_SERVER["HTTPS"]),
        true
    );

}

// ==========================================

header("Location: ../dashboard.php");
exit;