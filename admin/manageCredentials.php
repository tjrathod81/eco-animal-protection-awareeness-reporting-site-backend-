<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') exit;

include "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);
if (!$data) { echo json_encode(["success" => false, "error" => "No data"]); exit; }

$id = $data['id'] ?? null;
$name = $conn->real_escape_string($data['name']);
$email = $conn->real_escape_string($data['email']);
$password = $conn->real_escape_string($data['password']);

if ($id) {
    // UPDATE LOGIC
    if (!empty($password)) {
        // Removed password_hash. Saving plain text now.
        $sql = "UPDATE users SET name='$name', email='$email', password='$password' WHERE id=$id";
    } else {
        $sql = "UPDATE users SET name='$name', email='$email' WHERE id=$id";
    }
} else {
    $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', 'member')";
}

if ($conn->query($sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}

$conn->close();
?>