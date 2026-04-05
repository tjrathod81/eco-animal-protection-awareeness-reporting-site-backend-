<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include "../config/db.php";

// AUTO-UPDATE: If current date is greater than event date, set status to 'Done'
$currentDate = date('Y-m-d');
$conn->query("UPDATE events SET status = 'Done' WHERE date < '$currentDate' AND status != 'Done'");

$sql = "SELECT * FROM events ORDER BY date DESC";
$result = $conn->query($sql);
$events = [];

if ($result) {
    while($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}
echo json_encode($events);
?>