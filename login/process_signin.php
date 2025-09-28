<?php
session_start();

// Assuming you have a file for database connection like db_connect.php
require_once 'db_connect.php';  // Include your database connection file here

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $email = $_POST['email'];
    $password = $_POST['password'];

    // ==== CEK LOGIN ADMIN MANUAL ====
    if ($email === "admin@gmail.com" && $password === "admin123") {
        $_SESSION['username'] = "Admin";
        // $_SESSION['role'] = "admin"; // tambahkan role biar jelas
        header("Location: admin.php");
        exit();
    }

    // ==== CEK LOGIN USER BIASA (DATABASE) ====
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);  // Prepare the query

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
            // $_SESSION['role'] = "user"; // role user biasa
            
            header("Location: /demoWeb/index.php"); // redirect ke halaman user biasa
            exit();
        } else {
            echo "<p>Invalid credentials. Please try again.</p>";
        }
    } else {
        echo "<p>No user found with this email.</p>";
    }
}
?>
