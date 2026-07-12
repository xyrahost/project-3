<?php

require_once "../config/config.php";
require_once "../config/database.php";
require_once "../config/flash.php";
require_once "../config/csrf.php";

// Hanya menerima request POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../profile.php");
    exit;
}

// Cek CSRF
verifyCSRFToken($_POST["csrf_token"] ?? "");

// Ambil data
$full_name = trim($_POST["full_name"]);
$username  = trim($_POST["username"]);

$profile_photo = $_FILES["profile_photo"] ?? null;

// Validasi
if (empty($full_name) || empty($username)) {

    setFlash("error", "Nama lengkap dan username wajib diisi.");

    header("Location: ../edit-profile.php");
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

    header("Location: ../edit-profile.php");
    exit;

}

if (!preg_match('/^[A-Za-z0-9_]+$/', $username)) {

    setFlash(
        "error",
        "Username hanya boleh huruf, angka, dan underscore (_)."
    );

    header("Location: ../edit-profile.php");
    exit;

}

// Cek apakah username sudah dipakai user lain
$stmt = $conn->prepare("
SELECT id
FROM users
WHERE username = ?
AND id != ?
LIMIT 1
");

$stmt->bind_param(
    "si",
    $username,
    $_SESSION["user_id"]
);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    setFlash("error", "Username sudah digunakan.");

    header("Location: ../edit-profile.php");
    exit;

}

// =========================
// Upload Foto Profil
// =========================

$photoName = null;

$stmt = $conn->prepare("
SELECT profile_photo
FROM users
WHERE id = ?
LIMIT 1
");

$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();

$currentUser = $stmt->get_result()->fetch_assoc();

$photoName = $currentUser["profile_photo"];

if (
    isset($profile_photo) &&
    $profile_photo["error"] !== UPLOAD_ERR_NO_FILE
) {

    if ($profile_photo["error"] !== UPLOAD_ERR_OK) {

        setFlash(
            "error",
            "Upload foto gagal. Pastikan ukuran file tidak melebihi batas server."
        );

        header("Location: ../edit-profile.php");
        exit;
}
    }
{

    $allowedExtensions = [
        "jpg",
        "jpeg",
        "png",
        "webp"
    ];

    $extension = strtolower(
        pathinfo(
            $profile_photo["name"],
            PATHINFO_EXTENSION
        )
    );
    
    // Validasi MIME Type
$finfo = finfo_open(FILEINFO_MIME_TYPE);

$mimeType = finfo_file(
    $finfo,
    $profile_photo["tmp_name"]
);

finfo_close($finfo);

$allowedMimeTypes = [
    "image/jpeg",
    "image/png",
    "image/webp"
];

if (!in_array($mimeType, $allowedMimeTypes)) {

    setFlash(
        "error",
        "File yang diupload bukan gambar yang valid."
    );

    header("Location: ../edit-profile.php");
    exit;

}

    if (!in_array($extension, $allowedExtensions)) {

        setFlash(
            "error",
            "Foto harus berformat JPG, JPEG, PNG, atau WEBP."
        );

        header("Location: ../edit-profile.php");
        exit;

    }

    if ($profile_photo["size"] > (2 * 1024 * 1024)) {

        setFlash(
            "error",
            "Ukuran foto maksimal 2 MB."
        );

        header("Location: ../edit-profile.php");
        exit;

    }
    
    // Pastikan file benar-benar gambar
if (getimagesize($profile_photo["tmp_name"]) === false) {

    setFlash(
        "error",
        "File bukan gambar yang valid."
    );

    header("Location: ../edit-profile.php");
    exit;

}

    $photoName = uniqid("profile_", true)
        . "." . $extension;

    move_uploaded_file(
        $profile_photo["tmp_name"],
        "../uploads/profile/" . $photoName
    );

    if (
        !empty($currentUser["profile_photo"]) &&
        file_exists(
            "../uploads/profile/" .
            $currentUser["profile_photo"]
        )
    ) {

        unlink(
            "../uploads/profile/" .
            $currentUser["profile_photo"]
        );

    }

}

// Update profil
$stmt = $conn->prepare("
UPDATE users
SET
    full_name = ?,
    username = ?,
    profile_photo = ?,
    updated_at = NOW()
WHERE id = ?
");

$stmt->bind_param(
    "sssi",
    $full_name,
    $username,
    $photoName,
    $_SESSION["user_id"]
);

if ($stmt->execute()) {

    // Update session agar langsung berubah
    $_SESSION["full_name"] = $full_name;
    $_SESSION["username"] = $username;
    
    $_SESSION["profile_photo"] = $photoName;

    setFlash(
        "success",
        "Profil berhasil diperbarui."
    );

    header("Location: ../profile.php");
    exit;

}

setFlash(
    "error",
    "Terjadi kesalahan saat memperbarui profil."
);

header("Location: ../edit-profile.php");
exit;