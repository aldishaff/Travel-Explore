<?php
// validation_popular_destinations.php
function validate_destination_input($data, $file) {
    $errors = [];

    $name        = trim($data['name'] ?? '');
    $description = trim($data['description'] ?? '');
    $location    = trim($data['location'] ?? '');
    $price       = trim($data['price'] ?? '');
    $duration    = trim($data['duration'] ?? '');
    $category    = trim($data['category'] ?? '');
    $facilities  = trim($data['facilities'] ?? '');
    $rating      = trim($data['rating'] ?? '');
    $map_url     = trim($data['map_url'] ?? '');

    if (!$name || !$description || !$location || !$price || !$duration || !$category || !$facilities || !$rating) {
        $errors[] = "All required fields must be filled!";
    }

    if (strlen($name) > 50) $errors[] = "Name too long!";
    if (strlen($description) > 100) $errors[] = "Description too long!";
    if (strlen($location) > 55) $errors[] = "Location too long!";

    if ($map_url) {
        $pattern = "/^(https:\/\/maps\.app\.goo\.gl\/.+|https:\/\/www\.google\.com\/maps\/.+)$/";
        if (!preg_match($pattern, $map_url)) {
            $errors[] = "Map URL must be a valid Google Maps link!";
        }
    }

    if (!is_numeric($price) || $price < 0) {
        $errors[] = "Price must be a positive number!";
    }

    if (!$file || $file['error'] != 0) {
        $errors[] = "Image upload failed!";
    } else {
        $allowedTypes = ['image/jpeg', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            $errors[] = "Image Only JPG!";
        }
    }

    return $errors;
}