<?php

$current_page = basename($_SERVER["PHP_SELF"]);

?>

<div class="container">

<h2><?php echo SITE_NAME; ?></h2>

<?php

$navbarPhoto = "uploads/profile/default.png";

if (!empty($_SESSION["profile_photo"])) {

    $navbarPhoto = "uploads/profile/" . $_SESSION["profile_photo"];

}

?>

<div style="display:flex;align-items:center;gap:10px;">

<img
    src="<?php echo htmlspecialchars($navbarPhoto); ?>"
    width="45"
    height="45"
    style="border-radius:50%;object-fit:cover;"
>

<p>
Halo,
<strong><?php echo htmlspecialchars($_SESSION["full_name"]); ?></strong> 👋
</p>

</div>

<hr>

<a
href="dashboard.php"
<?php if ($current_page == "dashboard.php") echo 'style="font-weight:bold;text-decoration:underline;"'; ?>
>
🏠 Dashboard
</a>

|

<a
href="profile.php"
<?php if ($current_page == "profile.php") echo 'style="font-weight:bold;text-decoration:underline;"'; ?>
>
👤 Profil
</a>

|

<a href="logout.php">
🚪 Logout
</a>

<hr>

</div>