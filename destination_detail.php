<?php
session_start(); // tambahkan di paling atas
include 'login/db_connect.php'; // koneksi database

// Ambil ID dari query string
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    $id = $_GET['id'];
} else {
    die("Destination not found.");
}

// Ambil data destinasi dari database
$query = "SELECT * FROM destinations WHERE id = $id";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0){
    die("Destination not found.");
}

$destination = mysqli_fetch_assoc($result);

// cek login
$isLoggedIn = isset($_SESSION['user_id']); 
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $destination['name']; ?> - Travel Explore</title>
<link rel="stylesheet" href="dsetdet.css">

</head>
<body>

<main>
    <div class="destination-container">
        <!-- Kotak Informasi Detail -->
        <div class="destination-info">
            <img src="<?php echo $destination['image']; ?>" alt="<?php echo $destination['name']; ?>">
            <h1><?php echo $destination['name']; ?></h1>
            <p><?php echo $destination['description']; ?></p>

            <table>
                <tr><td>Location </td><td><?php echo htmlspecialchars($destination['location']); ?></td></tr>
                <tr><td>Price </td><td><?php echo htmlspecialchars($destination['price']); ?></td></tr>
                <tr><td>Duration </td><td><?php echo htmlspecialchars($destination['duration']); ?></td></tr>
                <tr><td>Category </td><td><?php echo htmlspecialchars($destination['category']); ?></td></tr>
                <tr><td>Facilities </td><td><?php echo htmlspecialchars($destination['facilities']); ?></td></tr>
                <tr><td>Rating </td><td>
                    <?php 
                        $stars = (int)$destination['rating'];
                        for($i=0;$i<$stars;$i++){ echo '★'; }
                        for($i=$stars;$i<5;$i++){ echo '☆'; }
                    ?>
                </td></tr>
                <tr><td>Map </td><td>
                    <?php if(!empty($destination['map_url'])): ?>
                    <a href="<?php echo $destination['map_url']; ?>" target="_blank">View on Map</a>
                    <?php else: ?>N/A<?php endif; ?>
                </td></tr>
            </table>
            <!-- Button Back -->
            <button onclick="window.location.href='index.php#destinations';" style="margin-top:20px;">Back to Destinations</button>
        </div>

        <!-- Kotak Form Booking -->
        <div class="destination-booking">
            <h2>Book This Destination</h2>
            <form id="bookingForm" action="process_booking.php" method="POST">
                <input type="hidden" name="destination_id" value="<?php echo $destination['id']; ?>">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <input type="number" name="guests" placeholder="Number of Guests" min="1" required>
                <input type="date" name="date" required>
                <button type="submit">Book Now</button>
            </form>
        </div>
        <!-- Popup Modal -->
        <div id="loginPopup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closePopup()">&times;</span>
            <h3>Login Diperlukan</h3>
            <p>Anda harus login sebelum memesan destinasi ini</p>
            <a href="login/sign_in.php"><button>Go to Login</button></a>
        </div>
        </div>

    </div>
</main>
    <script>
    document.getElementById("bookingForm").addEventListener("submit", function(e){
        var isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
        if(!isLoggedIn){
            e.preventDefault(); // cegah form submit
            document.getElementById("loginPopup").style.display = "flex";
        }
    });

    function closePopup(){
        document.getElementById("loginPopup").style.display = "none";
    }
    </script>

</body>
</html>
