<?php
require_once 'db_connect.php';

// Ambil data form
$name        = $_POST['name'];
$description = $_POST['description'];
$location    = $_POST['location'];
$price       = $_POST['price'];
$duration    = $_POST['duration'];
$category    = $_POST['category'];
$facilities  = $_POST['facilities'];
$rating      = $_POST['rating'];
$map_url     = $_POST['map_url'] ?? null; // optional

// Folder tujuan simpan file
$targetDir = __DIR__ . "/../images/";
$fileName = basename($_FILES["image"]["name"]);
$targetFilePath = $targetDir . $fileName;

// Pindahkan file upload ke folder
if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
    $imagePath = "images/" . $fileName;

    // Prepare statement INSERT sesuai field di database
    $stmt = $conn->prepare("INSERT INTO destinations 
        (name, description, image, location, price, duration, category, facilities, rating, map_url) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters: 8 string + 1 double (rating) + 1 string (map_url)
    $stmt->bind_param(
        "ssssssssss",
        $name,
        $description,
        $imagePath,
        $location,
        $price,
        $duration,
        $category,
        $facilities,
        $rating,
        $map_url
    );

    // Eksekusi
    if ($stmt->execute()) {
        header("Location: admin.php?success=1");
        exit;
    } else {
        header("Location: admin.php?error=" . urlencode($stmt->error));
        exit;
    }

    $stmt->close();
} else {
    header("Location: admin.php?error=" . urlencode("File upload failed."));
    exit;
}

$conn->close();
?>
