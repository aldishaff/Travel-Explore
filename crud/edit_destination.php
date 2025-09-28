<?php
session_start();
require_once '../login/db_connect.php';

// Cek login
if (!isset($_SESSION['username'])) {
    header("Location: ../login/sign_in.php");
    exit();
}

// Ambil ID
if (!isset($_GET['id'])) {
    header("Location: ../login/admin.php#destinations");
    exit();
}

$id = $_GET['id'];

// Ambil data lama dari database
$stmt = $conn->prepare("SELECT * FROM destinations WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$destination = $result->fetch_assoc();
$stmt->close();
    
// Update data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = $_POST['name'];
    $description = $_POST['description'];
    $location    = $_POST['location'];
    $price       = $_POST['price'];
    $duration    = $_POST['duration'];
    $category    = $_POST['category'];
    $facilities  = $_POST['facilities'];
    $rating      = $_POST['rating'];
    $map_url     = $_POST['map_url'];

    $imagePath = $destination['image']; // default image lama

    // Upload gambar baru jika ada
    if (isset($_FILES['image']) && $_FILES['image']['name'] != "") {
        $targetDir = "../images/";
        $fileName = basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            $imagePath = "images/" . $fileName;
        }
    }

    // Update query dengan field tambahan
    $stmt = $conn->prepare("UPDATE destinations 
        SET name=?, description=?, image=?, location=?, price=?, duration=?, category=?, facilities=?, rating=?, map_url=? 
        WHERE id=?");
    $stmt->bind_param(
        "ssssssssisi",
        $name,
        $description,
        $imagePath,
        $location,
        $price,
        $duration,
        $category,
        $facilities,
        $rating,
        $map_url,
        $id
    );

    if ($stmt->execute()) {
        $_SESSION['message'] = "Destination updated successfully!";
        header("Location: ../login/admin.php#destinations");
        exit();
    } else {
        $_SESSION['error_message'] = "Failed to update: " . $stmt->error;
        header("Location: ../login/admin.php#destinations");
        exit();
    }

        // Ambil ID
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(!isset($_POST['id'])){
            die("No ID provided.");
        }
        $id = $_POST['id'];
    }else{
        if(!isset($_GET['id'])){
            header("Location: ../login/admin.php#destinations");
            exit();
        }
        $id = $_GET['id'];
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Destination</title>
<link rel="stylesheet" href="crud.css">
</head>
<body>
<div class="form-container">
    <h2>Edit Destination</h2>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="name" value="<?= htmlspecialchars($destination['name']) ?>" required>
        <textarea name="description" required><?= htmlspecialchars($destination['description']) ?></textarea>

        <!-- Field tambahan -->
        <input type="text" name="location" value="<?= htmlspecialchars($destination['location']) ?>" placeholder="Location / City / Country" required>
        <input type="text" name="price" value="<?= htmlspecialchars($destination['price']) ?>" placeholder="Ticket / Package Price" required>
        <input type="text" name="duration" value="<?= htmlspecialchars($destination['duration']) ?>" placeholder="Duration / Best Time to Visit" required>
        <select name="category" required>
            <option value="">Select Category</option>
            <?php
            $categories = ["Pantai","Gunung","Budaya","Kuliner","Alam / Snorkeling"];
            foreach($categories as $cat){
                $selected = ($destination['category'] === $cat) ? "selected" : "";
                echo "<option value='$cat' $selected>$cat</option>";
            }
            ?>
        </select>
        <textarea name="facilities" placeholder="Facilities (comma separated)" required><?= htmlspecialchars($destination['facilities']) ?></textarea>
        <div class="rating">
            <?php for($i=5;$i>=1;$i--): ?>
                <input type="radio" name="rating" id="star<?= $i ?>" value="<?= $i ?>" <?= ($destination['rating']==$i) ? "checked" : "" ?>>
                <label for="star<?= $i ?>">★</label>
            <?php endfor; ?>
        </div>
        <input type="text" name="map_url" value="<?= htmlspecialchars($destination['map_url']) ?>" placeholder="Maps URL (optional)">

        <p>Current Image:</p>
        <img src="../<?= $destination['image'] ?>" width="150">
        <p>Change Image:</p>
        <input type="file" name="image" accept="image/*">
        <button type="submit">Update Destination</button>
    </form>
    <span class="close-modal" onclick="window.location.href='../login/admin.php'">&times;</span>
    <a href="../login/admin.php">Back</a>
</div>
</body>
</html>