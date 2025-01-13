<!DOCTYPE html>
<?php
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location:index.php");
        exit;
    };

    session_start();
    include('../../../utils/connect.php');

    $user_id = $_SESSION['user_id'];
    $order_id= mysqli_real_escape_string($conn, $_POST['order_id']);
    
    if ($_POST['action'] == 'cancel_order'){
        $user_role = ucwords(str_replace("_", " ", $_SESSION['user_role']));

        $remarks= mysqli_real_escape_string($conn, $_POST['remarks']);
        
        $order_remarks = $remarks." (Cancelled by ".$user_role.")";

        $sqlCancelOrder = "UPDATE customer_order SET status='cancelled', remarks='$order_remarks' WHERE id=$order_id";
        if(!mysqli_query($conn,$sqlCancelOrder)){
            die("Something went wrong");
        };

        $history_remarks = "Cancelled by ".$user_role.": ".$remarks;
        $sqlInsertOrderHistory = "INSERT INTO history(object_type, object_id, remarks, user_id) VALUES ('order','$order_id','$history_remarks','$user_id')";
        if(!mysqli_query($conn,$sqlInsertOrderHistory)){
            die("Something went wrong");
        };

        $_SESSION["message_string"] = "Order successfully cancelled!";
        $_SESSION["message_class"] = "info";

    } else if (in_array($_POST['action'], ['preparing','for_pickup'])){
        $action = $_POST['action'];
        $sqlPrepareOrder = "UPDATE customer_order SET status='$action' WHERE id=$order_id";
        if(!mysqli_query($conn,$sqlPrepareOrder)){
            die("Something went wrong");
        };
    
        $history_remarks = "Moved to \"".ucwords(str_replace("_", " ", $action))."\"";
        $sqlInsertOrderHistory = "INSERT INTO history(object_type, object_id, remarks, user_id) VALUES ('order','$order_id','$history_remarks','$user_id')";
        
        if(!mysqli_query($conn,$sqlInsertOrderHistory)){
            die("Something went wrong");
        };

        $_SESSION["message_string"] = "Order is successfully moved to '".ucwords(str_replace("_", " ", $action))."'!";
        $_SESSION["message_class"] = "info";

        
    };

    header("Location:index.php?order_id=".$order_id);
    exit;
?>