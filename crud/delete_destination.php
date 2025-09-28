<?php
require_once '../login/db_connect.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM destinations WHERE id=?");
    $stmt->bind_param("i", $id);

    if($stmt->execute()) {
        // <-- Redirect setelah sukses
        header("Location: ../login/admin.php#destinations");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
