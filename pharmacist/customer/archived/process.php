<!DOCTYPE html>
<?php
    if (!isset($_GET['customer_id']) || !isset($_GET['action']) ) {
        header("Location:../list/index.php");
        exit;
    };

    session_start();
    
    $customer_id = $_GET['customer_id'];
    $action = $_GET['action'];

    include('../../../utils/connect.php');
    $sqlGetCustomer = "SELECT user_id FROM customer WHERE id=$customer_id";

    $customer_result = mysqli_query($conn,$sqlGetCustomer);
    if ($customer_result->num_rows == 0){
        header("Location:../../../page/404.php");
    };
    
    $row = mysqli_fetch_array($customer_result);
    $user_id = $row['user_id'];
    
    if ($action == 'recover'){
        if ($user_id != ''){
            $sqlUpdateUser = "UPDATE user SET is_active='1' WHERE id='$user_id'";
            if(!mysqli_query($conn,$sqlUpdateUser)){
                die("Something went wrong");
            };
        };

        $sqlUpdateCustomer = "UPDATE customer SET is_active='1' WHERE id='$customer_id'";
        if(!mysqli_query($conn,$sqlUpdateCustomer)){
            die("Something went wrong");
        };
        $_SESSION["message_string"] = "Customer record recovered successfully!";
        $_SESSION["message_class"] = "info";

    } else {

    };

    header("Location:index.php");
    exit;
?>