<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include "../config/db.php";

if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "DB Connection Failed"]);
    exit();
}

// 4. GET DATA
$data = json_decode(file_get_contents("php://input"), true);

if ($data && isset($data['id'])) {
    $id = (int)$data['id'];
    $status = $conn->real_escape_string($data['status']);

    // Update Request Status
    $update = $conn->query("UPDATE requests SET status = '$status' WHERE id = $id");

    if ($status === 'Approved') {
        $res = $conn->query("SELECT type FROM requests WHERE id = $id");
        if ($res && $row = $res->fetch_assoc()) {
            if (strpos($row['type'], 'Event:') !== false) {
                $title = str_replace('Event: ', '', $row['type']);
                // Insert into events table
                $conn->query("INSERT INTO events (title, description, location, date, status) 
                            VALUES ('$title', 'User Proposed Event', 'TBD', CURDATE(), 'Coming soon')");
            }
        }
    }
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => "No data received"]);
}

$conn->close();
?>