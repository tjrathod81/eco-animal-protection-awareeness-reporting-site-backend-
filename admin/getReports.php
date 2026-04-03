<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit; }

include "../config/db.php"; 

// 1. Check if connection exists
if (!$conn) {
    echo json_encode(["status" => "error", "error" => "Database connection failed"]);
    exit;
}

// 2. Fetch data - Make sure these column names match your phpMyAdmin exactly
$sql = "SELECT id, report_text, report_type, created_at FROM admin_reports ORDER BY created_at DESC";
$result = $conn->query($sql);

$reports = [];

if ($result) {
    while($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }
    // 3. Return the array
    echo json_encode($reports);
} else {
    // 4. This will show if your table name 'admin_reports' is spelled wrong
    echo json_encode(["status" => "error", "error" => $conn->error]);
}

$conn->close();
?>