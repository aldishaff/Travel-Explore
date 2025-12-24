<?php
require_once 'db_connect.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password_raw = $_POST['password'];

$usernameRegex = "/^[A-Za-z]{3,}$/";
$emailRegex = "/^[^\s@]+@[^\s@]+\.[^\s@]+$/";
$passwordRegex = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/";

if (!preg_match($usernameRegex, $username)) {
    die("<p style='color:red;text-align:center;'>Username must be at least 3 letters.</p>");
}
if (!preg_match($emailRegex, $email)) {
    die("<p style='color:red;text-align:center;'>Invalid email format.</p>");
}
if (!preg_match($passwordRegex, $password_raw)) {
    die("<p style='color:red;text-align:center;'>Password must be at least 8 characters with letters and numbers.</p>");
}

$password = password_hash($password_raw, PASSWORD_BCRYPT);

$sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
if ($conn->query($sql) === TRUE) {
    header("Location: sign_in.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
