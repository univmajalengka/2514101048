<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_diskon";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>
