<!DOCTYPE html>
<?php
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location:index.php");
        exit;
    };

    session_start();
    include('../../utils/connect.php');

    if ($_POST['action'] == 'cancel_order'){
        $user_id = $_SESSION['user_id'];

        $order_id= mysqli_real_escape_string($conn, $_POST['order_id']);
        $remarks= mysqli_real_escape_string($conn, $_POST['remarks']);
        
        $remarks = $remarks." (Cancelled by Customer)";
        
        $sqlCancelOrder = "UPDATE customer_order SET status='cancelled', remarks='$remarks' WHERE id=$order_id";
        if(!mysqli_query($conn,$sqlCancelOrder)){
            die("Something went wrong");
        };

        $sqlInsertOrderHistory = "INSERT INTO history(object_type , object_id , remarks, user_id) VALUES ('order','$order_id','cancelled', '$user_id')";
        if(!mysqli_query($conn,$sqlInsertOrderHistory)){
            die("Something went wrong");
        };

        $_SESSION["message_string"] = "Order successfully cancelled!";
        $_SESSION["message_class"] = "info";
        header("Location:index.php");
        exit;

    }
?>