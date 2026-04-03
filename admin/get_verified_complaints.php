<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include "../config/db.php";

// Fetch only complaints that have been "Verified" by members
$sql = "SELECT * FROM complaints WHERE status = 'Verified' ORDER BY created_at DESC";
$result = $conn->query($sql);

$data = [];
while($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
$conn->close();
?>