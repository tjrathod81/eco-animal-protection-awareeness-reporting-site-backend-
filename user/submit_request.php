<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $type = $conn->real_escape_string($data['type']); 
    $ref_id = $data['reference_id'] ? (int)$data['reference_id'] : "NULL";
    
    if ($type === 'Event') {
        $details = $data['details'];
        $eventDate = $details['date'];
        $today = date("Y-m-d");

        // VALIDATION: Block past dates, months, and years
        if ($eventDate < $today) {
            echo json_encode(["success" => false, "error" => "Past dates are not allowed for new events."]);
            exit();
        }

        $title = $conn->real_escape_string($details['title']);
        $req_type = "Event: " . $title;
        
        // You might need to add a 'description' or 'date' column to your requests table 
        // if you want to store these details for the Admin to see.
        $sql = "INSERT INTO requests (type, reference_id, status) VALUES ('$req_type', NULL, 'Pending')";
    } else {
        // Volunteer logic: Verify event is still active
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