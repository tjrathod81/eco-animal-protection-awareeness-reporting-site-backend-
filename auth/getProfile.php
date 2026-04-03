<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);
$id = $data["id"] ?? null;

if (!$id) {
    echo json_encode(["success" => false, "error" => "Missing ID"]);
    exit;
}

$stmt = $conn->prepare("SELECT name, email, dob, address, phone, profile_pic FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if($user = $result->fetch_assoc()){
    echo json_encode([
        "success" => true,
        "user" => $user
    ]);
} else {
    echo json_encode(["success" => false, "error" => "User not found"]);
}
?>