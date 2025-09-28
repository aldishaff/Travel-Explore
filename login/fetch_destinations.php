<?php
require_once 'db_connect.php';

$query = "SELECT * FROM destinations";
$result = $conn->query($query);

$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Buat URL absolut biar gampang dipanggil dari index.php
        $row['image'] = "/demoWeb/" . $row['image'];
        $data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($data);

$conn->close();
?>
