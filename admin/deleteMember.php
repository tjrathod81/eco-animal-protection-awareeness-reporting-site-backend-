<?php
header("Access-Control-Allow-Origin: *");
include "../config/db.php";

$id = $_GET['id'];
$sql = "DELETE FROM users WHERE id = $id AND role = 'member'";
if ($conn->query($sql)) {
    echo json_encode(["success" => true]);
}
?>