<?php
header("Content-Type: application/json");
require_once 'db.php';

// GET: Retrieve all Destinations
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT * FROM destinations";
    $result = $conn->query($sql);
    $destinations = [];

    while ($row = $result->fetch_assoc()) {
        $destinations[] = $row;
    }

    echo json_encode($destinations);
}

// POST: Create a new Destination
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil input JSON
    $input = json_decode(file_get_contents("php://input"), true);

    if (isset($input['name']) && isset($input['description']) && isset($input['image'])) {
        $name = $input['name'];
        $description = $input['description'];
        $image = $input['image'];

        $sql = "INSERT INTO destinations (name, description, image) VALUES ('$name', '$description', '$image')";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Destination added successfully"]);
        } else {
            echo json_encode(["error" => $conn->error]);
        }
    } else {
        echo json_encode(["error" => "Missing required fields"]);
    }
}

// PUT: Update a Destination
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    parse_str(file_get_contents("php://input"), $_PUT);
    $id = $_PUT['id'];
    $name = $_PUT['name'];
    $description = $_PUT['description'];
    $image = $_PUT['image'];

    $sql = "UPDATE destinations SET name='$name', description='$description', image='$image' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Destination updated successfully"]);
    } else {
        echo json_encode(["error" => $conn->error]);
    }
}

// DELETE: Delete a Destination
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    parse_str(file_get_contents("php://input"), $_DELETE);
    $id = $_DELETE['id'];

    $sql = "DELETE FROM destinations WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Destination deleted successfully"]);
    } else {
        echo json_encode(["error" => $conn->error]);
    }
}

$conn->close();
?>