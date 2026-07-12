<?php

require_once "config/auth.php";
require_once "config/database.php";
require_once "config/csrf.php";
require_once "config/flash.php";

$stmt = $conn->prepare("
SELECT
    full_name,
    username
FROM users
WHERE id = ?
LIMIT 1
");

$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

?>

<?php require_once "includes/header.php"; ?>

<?php require_once "includes/navbar.php"; ?>

<div class="container">

<h2>✏️ Edit Profil</h2>

<?php

$profilePhoto = "uploads/profile/default.png";

if (!empty($_SESSION["profile_photo"])) {

    $profilePhoto = "uploads/profile/" . $_SESSION["profile_photo"];

}

?>

<img
    src="<?php echo htmlspecialchars($profilePhoto); ?>"
    alt="Foto Profil"
    width="120"
    height="120"
    style="
        border-radius:50%;
        object-fit:cover;
        border:2px solid #ccc;
        margin-bottom:15px;
    "
>

<?php showFlash(); ?>

<form
    action="auth/update_profile_process.php"
    method="POST"
    enctype="multipart/form-data"
>

    <input
        type="hidden"
        name="csrf_token"
        value="<?php echo generateCSRFToken(); ?>"
    >

    <label>Nama Lengkap</label>

    <input
        type="text"
        name="full_name"
        value="<?php echo htmlspecialchars($user["full_name"]); ?>"
        required
    >

    <label>Username</label>

    <input
        type="text"
        name="username"
        value="<?php echo htmlspecialchars($user["username"]); ?>"
        required
    >
    
    <label>Foto Profil</label>

<input
    type="file"
    name="profile_photo"
    accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
>

    <button type="submit">
        Simpan Perubahan
    </button>

</f>

<br>

<a href="profile.php">
    ← Kembali
</a>

</div>

<?php require_once "includes/footer.php"; ?>