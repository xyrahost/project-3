<?php
require_once "config/auth.php";

?>

<?php require_once "includes/header.php"; ?>
<?php require_once "includes/navbar.php"; ?>

<div class="container">

<h2>🏠 Dashboard</h2>

<p>
Selamat datang kembali,
<b><?php echo htmlspecialchars($_SESSION["full_name"]); ?></b> 👋
</p>

<hr>

<p><strong>Email</strong></p>
<p><?php echo htmlspecialchars($_SESSION["email"]); ?></p>

<p><strong>Username</strong></p>
<p><?php echo htmlspecialchars($_SESSION["username"]); ?></p>

<hr>

<h3>Menu</h3>

<p>👤 <a href="profile.php">Profil Saya</a></p>

<p>⚙️ <span style="color:gray;">Pengaturan (Segera Hadir)</span></p>

<p>💳 <span style="color:gray;">Saldo (Segera Hadir)</span></p>

<p>📜 <span style="color:gray;">Riwayat Transaksi (Segera Hadir)</span></p>

<p>🚪 <a href="logout.php">Logout</a></p>

</div>

</div>

<?php require_once "includes/footer.php"; ?>