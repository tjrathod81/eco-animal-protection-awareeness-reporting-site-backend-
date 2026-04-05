<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include "../config/db.php";

// ONLY fetch events where the date is today or in the future
// AND only if the status is not 'Done' or 'Rejected'
$sql = "SELECT * FROM events WHERE date >= CURDATE() AND status != 'Done' ORDER BY date ASC";

$result = $conn->query($sql);
$events = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

echo json_encode($events);
$conn->close();
?>