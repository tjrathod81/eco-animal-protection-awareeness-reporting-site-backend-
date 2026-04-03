<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $type = $conn->real_escape_string($data['type']); // 'Event' or 'Volunteer'
    $ref_id = $data['reference_id'] ? (int)$data['reference_id'] : "NULL";
    
    if ($type === 'Event') {
        // We use the 'type' column to store the specific request
        $details = $data['details'];
        $title = $conn->real_escape_string($details['title']);
        $req_type = "Event: " . $title;
        // In your requests table: id, type, reference_id, status
        $sql = "INSERT INTO requests (type, reference_id, status) VALUES ('$req_type', NULL, 'Pending')";
    } else {
        // reference_id points to the ID in the events table
        $sql = "INSERT INTO requests (type, reference_id, status) VALUES ('Volunteer', $ref_id, 'Pending')";
    }

    if ($conn->query($sql)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
}
$conn->close();
?>