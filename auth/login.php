<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$email = $data["email"] ?? '';
$password = $data["password"] ?? '';
$role = $data["role"] ?? '';

//
$stmt = $conn->prepare("SELECT id, role FROM users WHERE email=? AND password=? AND role=?");
$stmt->bind_param("sss", $email, $password, $role);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 1){
    $user = $result->fetch_assoc();
    echo json_encode([
        "success" => true,
        "role" => $user['role'],
        "id" => $user['id']
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid credentials"]);
}

$stmt->close();
$conn->close();
?>