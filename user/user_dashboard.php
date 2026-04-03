<?php
// backend/user_dashboard.php

// 1. SECURITY HEADERS (Allows Next.js to talk to PHP)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// 2. DATABASE CONNECTION
$host = "localhost";
$user = "root";
$pass = "";
$db   = "eco_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed"]);
    exit();
}

// 3. FETCH USER DATA (In a real app, use the Logged In User ID from Session/Token)
$userId = 1; 

// Fetch reports submitted by this user
$query = "SELECT id, title, status, created_at as date FROM complaints WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$reports = [];
while ($row = $result->fetch_assoc()) {
    $reports[] = $row;
}

// 4. RETURN DATA AS JSON
echo json_encode([
    "status" => "success",
    "reports" => $reports
]);

$stmt->close();
$conn->close();
?>