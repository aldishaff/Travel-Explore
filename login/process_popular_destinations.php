<?php
require_once 'db_connect.php';
// process_popular_destinations.php

// Ambil data form dan trim
$name        = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$location    = trim($_POST['location'] ?? '');
$price       = trim($_POST['price'] ?? '');
$duration    = trim($_POST['duration'] ?? '');
$category    = trim($_POST['category'] ?? '');
$facilities  = trim($_POST['facilities'] ?? '');
$rating      = trim($_POST['rating'] ?? '');
$map_url     = trim($_POST['map_url'] ?? ''); // optional

$errors = [];

require_once 'validation_popular_destinations.php';
$errors = validate_destination_input($_POST, $_FILES['image'] ?? null);

// Validasi Required
if (!$name || !$description || !$location || !$price || !$duration || !$category || !$facilities || !$rating ) {
    $errors[] = "All required fields must be filled!";
}

// Validasi panjang string
if (strlen($name) > 50) $errors[] = "Name too long!";
if (strlen($description) > 100) $errors[] = "Description too long!";
if (strlen($location) > 55) $errors[] = "Location too long!";
// Validasi URL Google Maps
// Validasi URL Google Maps
if ($map_url !== "") {

    // 1. Validasi URL umum
    if (!filter_var($map_url, FILTER_VALIDATE_URL)) {
        $errors[] = "Map URL must be a valid URL!";
    } 
    // 2. Jika valid URL, baru cek apakah domainnya Google Maps
    else if (
        !str_contains($map_url, "maps.app.goo.gl/") &&
        !str_contains($map_url, "google.com/maps")
    ) {
        $errors[] = "Map URL must be a valid Google Maps link!";
    }
}

// Validasi price dan rating
if (!is_numeric($price) || $price < 0) $errors[] = "Price must be a positive number!";
//if (!in_array($rating, ['1','2','3','4','5'])) $errors[] = "Rating must be between 1-5!";

// Validasi file upload
if (!isset($_FILES['image']) || $_FILES['image']['error'] != 0) {
    $errors[] = "Image upload failed!";
} else {
    $allowedTypes = ['image/jpeg', 'image/gif'];
    if (!in_array($_FILES['image']['type'], $allowedTypes)) {
        $errors[] = "Image Only JPG!"; //GIF images allowed
    }
}

// Jika ada error, redirect dengan pesan error
if (!empty($errors)) {
    header("Location: admin.php?error=" . urlencode(implode(" | ", $errors)));
    exit;
}

// Folder tujuan simpan file
$targetDir = __DIR__ . "/../images/";
$fileName = basename($_FILES["image"]["name"]);
$targetFilePath = $targetDir . $fileName;

// Pindahkan file upload ke folder
if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
    $imagePath = "images/" . $fileName;

    // Prepare statement INSERT
    $stmt = $conn->prepare("INSERT INTO destinations 
        (name, description, image, location, price, duration, category, facilities, rating, map_url) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

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

    if ($stmt->execute()) {
        header("Location: admin.php?success=1");
        exit;
    } else {
        header("Location: admin.php?error=" . urlencode($stmt->error));
        exit;
    }

    $stmt->close();
} else {
    header("Location: admin.php?error=" . urlencode("Failed to move uploaded image."));
    exit;
}

$conn->close();
?>

