<?php
    error_reporting(E_ALL); // Report all types of errors
    ini_set('display_errors', 1); // Display errors on the screen
    
    $dbName = "pharmanest_db";
    // $dbName = "pharmanest_db_test"; // uncomment this to use the testing DB
    $dbHost = "localhost";
    $dbUser = "root";
    $dbPass = "";
    $conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
    if($conn->connect_error){
        die("Failed to connect DB".$conn->connect_error);
    }
?>