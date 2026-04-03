<?php
// 1. Enhanced CORS Headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// 2. Handle Pre-flight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// 3. Include Database (Double check this path!)
include "../config/db.php"; 

// 4. Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['message'])) {
    // Sanitize inputs
    $msg = $conn->real_escape_string($data['message']);
    $type = isset($data['type']) ? $conn->real_escape_string($data['type']) : 'General';
    
    // 5. Check if table name is exactly 'admin_reports'
    $sql = "INSERT INTO admin_reports (report_text, report_type, status) VALUES ('$msg', '$type', 'New')";
    
    if ($conn->query($sql)) {
        echo json_encode([
            "status" => "success", 
            "message" => "Admin notified successfully"
        ]);
    } else {
        // This will tell you if the table name or columns are wrong
        echo json_encode([
            "status" => "error", 
            "error" => $conn->error
        ]);
    }
} else {
    echo json_encode([
        "status" => "error", 
        "message" => "No message data received by PHP"
    ]);
}

$conn->close();
?>