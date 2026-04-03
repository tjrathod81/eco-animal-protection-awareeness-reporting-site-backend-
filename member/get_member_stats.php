<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit; }

include "../config/db.php";

// 1. Count Pending Incidents (To be verified)
$res1 = $conn->query("SELECT COUNT(*) as total FROM incidents WHERE status = 'Pending'");
$pending = $res1->fetch_assoc()['total'];

// 2. Count Verified Incidents (Work already done)
$res2 = $conn->query("SELECT COUNT(*) as total FROM incidents WHERE status = 'Verified'");
$verified = $res2->fetch_assoc()['total'];

// 3. Count Upcoming Events
$res3 = $conn->query("SELECT COUNT(*) as total FROM events WHERE status != 'Done'");
$events = $res3->fetch_assoc()['total'];

echo json_encode([
    "pending" => (int)$pending,
    "verified" => (int)$verified,
    "events" => (int)$events
]);

$conn->close();
?>