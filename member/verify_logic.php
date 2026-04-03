<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit; }

include "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);
$id = intval($data['id']);
$decision = $data['decision']; // 'True' or 'Fake'

if ($decision === 'Fake') {
    // 1. DELETE from incidents table
    $sql = "DELETE FROM incidents WHERE id = $id";
    if ($conn->query($sql)) {
        echo json_encode(["success" => true, "message" => "Incident deleted"]);
    }
} else {
    // 2. MOVE to complaints table for Admin
    // First, get the data from incidents
    $incident = $conn->query("SELECT * FROM incidents WHERE id = $id")->fetch_assoc();
    
    $type = $incident['type'];
    $desc = $conn->real_escape_string($incident['description']);
    $loc = $conn->real_escape_string($incident['location']);
    
    // Insert into complaints table with status 'Verified' (meaning Member said it's true)
    $sql_insert = "INSERT INTO complaints (type, description, location, status) 
                   VALUES ('$type', '$desc', '$loc', 'Verified')";
    
    if ($conn->query($sql_insert)) {
        // Update original incident so it doesn't show up in Member's pending list again
        $conn->query("UPDATE incidents SET status = 'Verified' WHERE id = $id");
        echo json_encode(["success" => true, "message" => "Sent to Admin"]);
    }
}

$conn->close();
?>