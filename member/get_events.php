<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle pre-flight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit; 
}

include "../config/db.php";

// Updated SQL: Added "WHERE date >= CURDATE()" to hide past events
$sql = "SELECT id, title, description, location, date, status 
        FROM events 
        WHERE date >= CURDATE() 
        ORDER BY date ASC";

$result = $conn->query($sql);

$events = [];
if ($result) {
    while($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

echo json_encode($events);
$conn->close();
?>