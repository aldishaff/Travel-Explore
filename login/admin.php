<?php
session_start();
require_once 'db_connect.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: sign_in.php");
    exit();
}

$username = $_SESSION['username'];

// Hitung total data dari database
$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$total_destinations = $conn->query("SELECT COUNT(*) AS total FROM destinations")->fetch_assoc()['total'];
$total_tours = $conn->query("SELECT COUNT(*) AS total FROM featured_tours")->fetch_assoc()['total'];

// Ambil data destinations
$destinations = $conn->query("SELECT * FROM destinations ORDER BY id DESC");

// Ambil data tours
$tours = $conn->query("SELECT * FROM featured_tours ORDER BY id DESC");

// Ambil data users
$users = $conn->query("SELECT id, username, email FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <h1>ADMIN PANEL</h1>
            <div class="welcome-text">
                <h2>👋 Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
            </div>
            
            <nav>
                <a href="#destinations">🌍 Destinations</a>
                <a href="#tours">🚌 Featured Tours</a>
                <a href="#users">👥 Users</a>
                <a href="logout.php" class="logout">🚪 Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="content">
            <!-- Dashboard Section -->
            <section id="dashboard" class="card-grid">
                <div class="card">
                    <h3>Total Users</h3>
                    <p><?php echo $total_users; ?></p>
                </div>
                <div class="card">
                    <h3>Total Destinations</h3>
                    <p><?php echo $total_destinations; ?></p>
                </div>
                <div class="card">
                    <h3>Total Tours</h3>
                    <p><?php echo $total_tours; ?></p>
                </div>
            </section>

            <!-- Manage Destinations -->
            <section id="destinations">
                 <h2>Manage Popular Destinations</h2>
                    <form action="process_popular_destinations.php" method="POST" enctype="multipart/form-data">
                    <input type="text" name="name" placeholder="Destination Name" required>
                    <textarea name="description" placeholder="Description" required></textarea>
                    <input type="file" name="image" accept="image/*" required>

                    <!-- Lokasi -->
                    <input type="text" name="location" placeholder="Location / City / Country" required>
                    
                    <!-- Harga -->
                    <input type="text" name="price" placeholder="Ticket / Package Price" required>
                    
                    <!-- Durasi -->
                    <input type="text" name="duration" placeholder="Duration / Best Time to Visit" required>
                    
                    <!-- Kategori (dropdown) -->
                    <select name="category" required>
                        <option value="">Select Category</option>
                        <option value="Pantai">Pantai</option>
                        <option value="Gunung">Gunung</option>
                        <option value="Budaya">Budaya</option>
                        <option value="Kuliner">Kuliner</option>
                        <option value="Alam / Snorkeling">Alam / Snorkeling</option>
                    </select>
                    
                    <!-- Fasilitas -->
                    <textarea name="facilities" placeholder="Facilities (comma separated)" required></textarea>
                    
                    <!-- Rating (bintang) -->
                    <div class="rating">
                        <input type="radio" name="rating" id="star5" value="5" required>
                        <label for="star5">★</label>

                        <input type="radio" name="rating" id="star4" value="4">
                        <label for="star4">★</label>

                        <input type="radio" name="rating" id="star3" value="3">
                        <label for="star3">★</label>

                        <input type="radio" name="rating" id="star2" value="2">
                        <label for="star2">★</label>

                        <input type="radio" name="rating" id="star1" value="1">
                        <label for="star1">★</label>
                    </div>

                    
                    <!-- Google Maps Location Search -->
                    <input type="text" id="map_location" placeholder="Url Maps">

                    <button type="submit">Add Destination</button>
                </form>


                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Destination</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $destinations->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td>
                                <?php if (!empty($row['image'])): ?>
                                    <img src="../<?= $row['image'] ?>" alt="<?= $row['name'] ?>" width="120">
                                <?php else: ?>
                                    <span style="color:red;">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td class="action-cell">
                                <a href="/demoWeb/crud/edit_destination.php?id=<?php echo $row['id']; ?>" class="btn edit">✏️ Edit</a> 
                                <a href="/demoWeb/crud/delete_destination.php?id=<?php echo $row['id']; ?>" class="btn delete" onclick="return confirm('Delete this destination?')">🗑️ Delete</a>
                            </td>

                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>

            <!-- Manage Tours -->
            <section id="tours">
                <h2>Manage Featured Tours</h2>
                <form action="process_featured_tours.php" method="POST" enctype="multipart/form-data">
                    <input type="text" name="tour_name" placeholder="Tour Name" required>
                    <textarea name="tour_details" placeholder="Tour Details" required></textarea>
                    <input type="file" name="image" required>
                    <button type="submit">Add Tour</button>
                </form>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tour</th>
                            <th>Details</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $tours->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['tour_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['tour_details']); ?></td>
                            <td><img src="uploads/<?php echo $row['image']; ?>" width="80"></td>
                            <td>
                                <a href="edit_tour.php?id=<?php echo $row['id']; ?>">✏️ Edit</a> | 
                                <a href="delete_tour.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this tour?')">🗑️ Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>

            <!-- Manage Users -->
            <section id="users">
                <h2>Manage Users</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $users->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $row['id']; ?>">✏️ Edit</a> | 
                                <a href="delete_user.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this user?')">🗑️ Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
        </main>
        <!-- Popup Notification -->
        <div id="popup" class="popup">
            <div class="popup-content">
                <p id="popup-message"></p>
                <button onclick="closePopup()">OK</button>
            </div>
        </div>

    </div>
                <?php if (isset($_GET['success'])): ?>
                <script>
                Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Destination added successfully!',
                confirmButtonColor: '#3085d6'
                })
                </script>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                <script>
                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?php echo $_GET['error']; ?>',
                confirmButtonColor: '#d33'
                })
                </script>
                <?php endif; ?>
               <script>
                function toggleSidebar() {
                    document.getElementById("sidebar").classList.toggle("active");
                }
                </script>


</body>
</html>
