<?php

/**
 * Membuat CSRF Token
 */
function generateCSRFToken()
{
    if (empty($_SESSION["csrf_token"])) {

        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));

    }

    return $_SESSION["csrf_token"];
}

/**
 * Mengecek CSRF Token
 */
function verifyCSRFToken($token)
{
    if (
        empty($_SESSION["csrf_token"]) ||
        !hash_equals($_SESSION["csrf_token"], $token)
    ) {

        die("CSRF Token tidak valid.");

    }

    // Hapus token setelah digunakan
    unset($_SESSION["csrf_token"]);
}