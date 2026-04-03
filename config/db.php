<?php

$conn = new mysqli("localhost","root","","eco_db");

if($conn->connect_error){
 die("Database error");
}

?>