<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

include "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}


if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "Database connection failed"]);
    exit();
}

// 4. PROCESS FORM DATA
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Escape string inputs to prevent SQL Injection
    $uid_proof = $conn->real_escape_string($_POST['user_id_proof']);
    $u_addr    = $conn->real_escape_string($_POST['user_address']);
    $gps       = $conn->real_escape_string($_POST['gps_coords']);
    $loc       = $conn->real_escape_string($_POST['incident_location']);
    
    $w_name    = $conn->real_escape_string($_POST['w_name'] ?? '');
    $w_phone   = $conn->real_escape_string($_POST['w_phone'] ?? '');
    $w_id      = $conn->real_escape_string($_POST['w_id'] ?? '');
    
    $type      = $conn->real_escape_string($_POST['type']);
    $date      = $_POST['date'];
    $time      = $_POST['time'];
    $desc      = $conn->real_escape_string($_POST['desc']);
    $sugg      = $conn->real_escape_string($_POST['suggestions'] ?? '');

    // 5. FILE UPLOAD LOGIC
    $upload_dir = "uploads/";
    
    // Create folder if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $uploaded_files = [];

    // Process Photos (photos[] array from frontend)
    if (isset($_FILES['photos'])) {
        foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
            if ($key < 6 && $_FILES['photos']['error'][$key] == 0) {
                $filename = time() . "_photo_" . basename($_FILES['photos']['name'][$key]);
                $target = $upload_dir . $filename;
                if (move_uploaded_file($tmp_name, $target)) {
                    $uploaded_files[] = $target;
                }
            }
        }
    }

    // Process Videos (videos[] array from frontend)
    if (isset($_FILES['videos'])) {
        foreach ($_FILES['videos']['tmp_name'] as $key => $tmp_name) {
            if ($key < 2 && $_FILES['videos']['error'][$key] == 0) {
                $filename = time() . "_video_" . basename($_FILES['videos']['name'][$key]);
                $target = $upload_dir . $filename;
                if (move_uploaded_file($tmp_name, $target)) {
                    $uploaded_files[] = $target;
                }
            }
        }
    }

    // Convert file array to a single string to save in the database
    $evidence_paths = implode(",", $uploaded_files);

    // 6. INSERT INTO INCIDENTS TABLE
    $sql = "INSERT INTO incidents (
                user_id_proof, user_address, type, location, gps_coordinates, 
                incident_date, incident_time, description, suggestions, 
                witness_name, witness_phone, witness_id_proof, evidence_paths
            ) VALUES (
                '$uid_proof', '$u_addr', '$type', '$loc', '$gps', 
                '$date', '$time', '$desc', '$sugg', 
                '$w_name', '$w_phone', '$w_id', '$evidence_paths'
            )";

    if ($conn->query($sql)) {
        $last_id = $conn->insert_id;
        
        // 7. NOTIFY ADMIN DASHBOARD (Insert into requests table)
        $conn->query("INSERT INTO requests (type, reference_id, status) VALUES ('Crime: $type', $last_id, 'Pending')");
        
        echo json_encode(["success" => true, "id" => $last_id]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
}

$conn->close();
?>