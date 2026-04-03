<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

include "../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$email = $data["email"];
$password = $data["password"];

$sql = "UPDATE users SET password='$password' WHERE email='$email'";

if($conn->query($sql)){
    echo json_encode(["success"=>true]);
}else{
    echo json_encode(["success"=>false]);
}
?>