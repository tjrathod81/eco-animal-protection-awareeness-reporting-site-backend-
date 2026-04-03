<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include "../config/db.php";

$type = $_GET['type'] ?? 'complaints';

if ($type === 'complaints') {
    $sql = "SELECT id, title, description as desc FROM complaints WHERE status = 'pending'";
} elseif ($type === 'events') {
    $sql = "SELECT id, event_name as title, description as desc FROM events";
} else {
    $sql = "SELECT id, report_title as title, report_body as desc FROM reports";
}

$result = $conn->query($sql);
$data = [];

while($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
$conn->close();
?>