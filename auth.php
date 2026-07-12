<?php

require_once __DIR__ . "/config.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: " . BASE_URL . "/login.php");
    exit;

}

// Cek Session Timeout
if (isset($_SESSION["last_activity"])) {

    if (
        (time() - $_SESSION["last_activity"])
        > SESSION_TIMEOUT
    ) {

        session_unset();
        session_destroy();

        header("Location: " . BASE_URL . "/login.php");
        exit;

    }

}

// Update waktu aktivitas terakhir
$_SESSION["last_activity"] = time();