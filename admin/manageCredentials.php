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
$password = $data['password'];

if ($id) {
    // UPDATE: Matches your 'name' and 'email' columns
    $sql = "UPDATE users SET name='$name', email='$email' WHERE id=$id";
    if (!$conn->query($sql)) {
        die(json_encode(["success" => false, "error" => $conn->error]));
    }
    
    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $conn->query("UPDATE users SET password='$hashed' WHERE id=$id");
    }
} else {
    // CREATE: Matches your 'name', 'email', 'password', 'role' columns
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$hashed', 'member')";
    
    if (!$conn->query($sql)) {
        die(json_encode(["success" => false, "error" => $conn->error]));
    }
}

echo json_encode(["success" => true]);
?>