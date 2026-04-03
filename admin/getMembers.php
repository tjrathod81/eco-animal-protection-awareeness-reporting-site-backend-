<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "../config/db.php";

// Check if connection exists
if (!$conn) {
    die(json_encode(["error" => "Database connection variable not found"]));
}

// FIXED QUERY: Matches your columns (id, name, email, phone, role)
$sql = "SELECT id, name, email, phone, address, role FROM users WHERE role = 'member'";
$result = $conn->query($sql);

if (!$result) {
    // This will tell you EXACTLY which column name is wrong
    die(json_encode(["error" => "SQL Query Failed: " . $conn->error]));
}

$members = [];
while($row = $result->fetch_assoc()) {
    $members[] = $row;
}

echo json_encode($members);
?>