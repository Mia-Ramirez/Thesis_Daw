<?php

$host="localhost";
$customer="root";
$pass="";
$db="system";
$conn=new mysqli($host,$customer,$pass,$db);
if($conn->connect_error){
    echo "Failed to connect DB".$conn->connect_error;
}
?>