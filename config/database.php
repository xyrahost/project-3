<?php

$host = "localhost";
$dbname = "techwebb_db";
$username = "techwebb_user";
$password = "@Xyra963";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");