<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit; }

include "../config/db.php";

// Read the JSON body
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['message'])) {
    $msg = $conn->real_escape_string($data['message']);
    $type = $conn->real_escape_string($data['type']);
    
    // We insert NULL for file_path since we removed that feature
    $sql = "INSERT INTO admin_reports (report_text, report_type, file_path) VALUES ('$msg', '$type', NULL)";
    
    if ($conn->query($sql)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "error" => $conn->error]);
    }
} else {
    echo json_encode(["status" => "error", "error" => "Empty message"]);
}

$conn->close();
?>