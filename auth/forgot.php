<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

include "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$email = $data["email"];

$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);

if($result->num_rows == 1){
    echo json_encode(["success"=>true]);
}else{
    echo json_encode(["success"=>false]);
}
?>