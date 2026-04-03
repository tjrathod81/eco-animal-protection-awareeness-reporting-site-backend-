<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Content-Type: application/json");

include "../config/db.php";
$id = $_GET['id'] ?? null;

if ($id) {
    $sql = "DELETE FROM events WHERE id = $id";
    echo json_encode(["success" => $conn->query($sql)]);
}
?>