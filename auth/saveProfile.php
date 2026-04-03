<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include "../config/db.php";

// Handle data sent from React
$data = json_decode(file_get_contents("php://input"), true);

if(!$data){
    echo json_encode(["success" => false, "error" => "No data received"]);
    exit;
}

$id = $data["id"] ?? "";
$name = $data["name"] ?? "";
$dob = !empty($data["dob"]) ? $data["dob"] : null; // Handle empty date
$address = $data["address"] ?? "";
$phone = $data["phone"] ?? "";
$profile_pic = $data["profile_pic"] ?? "";

if(empty($id)){
    echo json_encode(["success" => false, "error" => "No ID provided"]);
    exit;
}

// Prepared statement for security
$stmt = $conn->prepare("UPDATE users SET name=?, dob=?, address=?, phone=?, profile_pic=? WHERE id=?");
$stmt->bind_param("sssssi", $name, $dob, $address, $phone, $profile_pic, $id);

if($stmt->execute()){
    // affected_rows is -1 on error, 0 on no change, 1+ on success
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}

$stmt->close();
$conn->close();
?>