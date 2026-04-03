<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include "../config/db.php";

$stats = [];

// 1. Count Total Complaints
$res1 = $conn->query("SELECT COUNT(*) as total FROM complaints");
$stats['total_complaints'] = $res1->fetch_assoc()['total'];

// 2. Count Pending Complaints
$res2 = $conn->query("SELECT COUNT(*) as total FROM complaints WHERE status = 'Pending'");
$stats['pending'] = $res2->fetch_assoc()['total'];

// 3. Count Approved/Verified Actions
$res3 = $conn->query("SELECT COUNT(*) as total FROM events WHERE status = 'Done'");
$stats['done_events'] = $res3->fetch_assoc()['total'];

// 4. Count Active Events
$res4 = $conn->query("SELECT COUNT(*) as total FROM events");
$stats['events'] = $res4->fetch_assoc()['total'];

// 5. COUNT TOTAL MEMBERS (This is what you asked for)
$res5 = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'member'");
$stats['members'] = $res5->fetch_assoc()['total'];

echo json_encode($stats);
?>