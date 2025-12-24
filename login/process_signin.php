<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($email === "admin@gmail.com" && $password === "admin123") {
        $_SESSION['username'] = "Admin";
        header("Location: admin.php");
        exit();
    }

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die('Error preparing the query: ' . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            header("Location: /demoWeb/index.php");
            exit();
        } else {
            echo "<p style='color:red;text-align:center;'>Invalid password. Please try again.</p>";
        }
    } else {
        echo "<p style='color:red;text-align:center;'>No user found with this email.</p>";
    }
}
?>
