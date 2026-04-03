<?php
// 1. Mandatory Headers for Next.js
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// 2. Handle Pre-flight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// 3. Database Connection
include "../config/db.php";

if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "Database connection failed"]);
    exit();
}

// 4. Fetch only PENDING incidents for the Member to verify
// We select the columns based on your MySQL table structure
$sql = "SELECT 
            id, 
            user_id_proof, 
            type, 
            location, 
            incident_date, 
            incident_time, 
            description, 
            evidence_paths 
        FROM incidents 
        WHERE status = 'Pending' 
        ORDER BY created_at DESC";

$result = $conn->query($sql);

$incidents = [];

if ($result) {
    while($row = $result->fetch_assoc()) {
        $incidents[] = $row;
    }
    // Return the array to Next.js
    echo json_encode($incidents);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}

$conn->close();
?>