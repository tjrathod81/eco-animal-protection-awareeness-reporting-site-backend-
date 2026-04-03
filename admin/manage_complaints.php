<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit; }

include "../config/db.php";

// Get the JSON data from Next.js
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id']) && isset($data['action'])) {
    $id = intval($data['id']);
    $action = $data['action']; // 'Approve' or 'Delete'

    if ($action === 'Delete') {
        // Remove from complaints table
        $sql = "DELETE FROM complaints WHERE id = $id";
    } else {
        // Update status to 'Approved'
        $sql = "UPDATE complaints SET status = 'Approved' WHERE id = $id";
    }

    if ($conn->query($sql)) {
        echo json_encode(["success" => true]);
    } else {
        // This will help you see the EXACT SQL error in the console
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Missing ID or Action"]);
}

$conn->close();
?>