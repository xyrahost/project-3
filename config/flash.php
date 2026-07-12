<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function setFlash($type, $message)
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function showFlash()
{
    if (!isset($_SESSION['flash'])) {
        return;
    }

    $flash = $_SESSION['flash'];

    $background = "#e3f2fd";
    $color = "#0d47a1";

    if ($flash['type'] == "success") {
        $background = "#d4edda";
        $color = "#155724";
    }

    if ($flash['type'] == "error") {
        $background = "#f8d7da";
        $color = "#721c24";
    }

    if ($flash['type'] == "warning") {
        $background = "#fff3cd";
        $color = "#856404";
    }

    echo "
    <div style='
        background:$background;
        color:$color;
        padding:15px;
        border-radius:8px;
        margin-bottom:20px;
        font-size:15px;
    '>
        {$flash['message']}
    </div>
    ";

    unset($_SESSION['flash']);
}