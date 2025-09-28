<?php
// db_connect.php

$host = 'localhost';  // Ganti dengan host database Anda, misalnya 'localhost'
$username = 'root';   // Ganti dengan username database Anda
$password = '';       // Ganti dengan password database Anda jika ada
$dbname = 'travel_website';  // Ganti dengan nama database Anda

// Membuat koneksi ke database
$conn = new mysqli($host, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
