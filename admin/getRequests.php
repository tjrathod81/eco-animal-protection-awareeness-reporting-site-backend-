
<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include "../config/db.php";

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Fetch all requests ordered by latest first
$sql = "SELECT id, type, reference_id, status, created_at FROM requests ORDER BY created_at DESC";
$result = $conn->query($sql);

$requests = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
}

// Output as JSON
echo json_encode($requests);

$conn->close();
?>