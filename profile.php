<?php

require_once "config/auth.php";
require_once "config/database.php";

$stmt = $conn->prepare("
SELECT
    full_name,
    username,
    email,
    status,
    email_verified_at,
    profile_photo,
    created_at,
    updated_at
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

<h2>Profil Saya</h2>

<?php

$profilePhoto = "uploads/profile/default.png";

if (!empty($user["profile_photo"])) {

    $profilePhoto = "uploads/profile/" . $user["profile_photo"];

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

<p><strong>Nama Lengkap</strong></p>
<p><?php echo htmlspecialchars($user["full_name"]); ?></p>

<p><strong>Username</strong></p>
<p><?php echo htmlspecialchars($user["username"]); ?></p>

<p><strong>Email</strong></p>
<p><?php echo htmlspecialchars($user["email"]); ?></p>

<p><strong>Status</strong></p>
<p><?php echo ucfirst($user["status"]); ?></p>

<p><strong>Email Terverifikasi</strong></p>
<p>

<?php

echo $user["email_verified_at"]
? "✅ Sudah"
: "❌ Belum";

?>

</p>

<p><strong>Bergabung Sejak</strong></p>

<p>

<?php

echo date(
"d F Y H:i",
strtotime($user["created_at"])
);

?>

</p>

<br>

<a href="edit-profile.php">
✏️ Edit Profil
</a>

<br><br>

<a href="dashboard.php">
← Kembali ke Dashboard
</a>

<a href="change-password.php">
🔑 Ganti Password
</a>

<br><br>

</div>

<?php require_once "includes/footer.php"; ?>