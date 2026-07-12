<?php

if (isset($_SESSION["user_id"])) {
    return;
}

if (!isset($_COOKIE["remember_token"])) {
    return;
}

require_once __DIR__ . "/database.php";

$remember_token = $_COOKIE["remember_token"];

$stmt = $conn->prepare("
SELECT
    id,
    full_name,
    username,
    email
FROM users
WHERE remember_token = ?
AND remember_expired_at > NOW()
LIMIT 1
");

$stmt->bind_param("s", $remember_token);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {

    setcookie(
        "remember_token",
        "",
        time() - 3600,
        "/"
    );

    return;
}

$user = $result->fetch_assoc();

$_SESSION["user_id"] = $user["id"];
$_SESSION["full_name"] = $user["full_name"];
$_SESSION["username"] = $user["username"];
$_SESSION["email"] = $user["email"];