<?php
// Koneksi ke database
require_once 'db_connect.php';

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data dari form
$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash password

// Simpan ke database
$sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
if ($conn->query($sql) === TRUE) {
    // Redirect to the Sign In page after successful sign-up
    header("Location: sign_in.php");  // Redirect to sign_in.php
    exit(); // Make sure no further code is executed after the redirect
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
