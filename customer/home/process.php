<!DOCTYPE html>
<?php
    if (!isset($_GET['medicine_id']) || !isset($_GET['action']) ) {
        header("Location:index.php");
        exit;
    };

    session_start();
    
    $medicine_id = $_GET['medicine_id'];
    $action = $_GET['action'];

    $customer_id = $_SESSION['user_id'];

    $sqlCheckMedicine = "SELECT name FROM medicine WHERE id=$medicine_id"; 
    $result = mysqli_query($conn, $sqlCheckMedicine);
    
    if ($result->num_rows < 1){
        // If email is not yet registered it will display an error message in Login Form
        $_SESSION["message_string"] = "Medicine not found!";
        header("Location:index.php");
        exit;
    };

    
?>