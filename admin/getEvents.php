<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include "../config/db.php";

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