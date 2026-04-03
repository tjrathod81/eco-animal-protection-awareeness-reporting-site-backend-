<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') exit;

include "../config/db.php";
$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;
$title = $conn->real_escape_string($data['title']);
$description = $conn->real_escape_string($data['description']);
$location = $conn->real_escape_string($data['location']);
$date = $data['date'];
$status = $data['status'];

if ($id) {
    $sql = "UPDATE events SET title='$title', description='$description', location='$location', date='$date', status='$status' WHERE id=$id";
} else {
    $sql = "INSERT INTO events (title, description, location, date, status) VALUES ('$title', '$description', '$location', '$date', '$status')";
}

echo json_encode(["success" => $conn->query($sql)]);
?>