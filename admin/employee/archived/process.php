<!DOCTYPE html>
<?php
    if (!isset($_GET['employee_id']) || !isset($_GET['action']) ) {
        header("Location:../list/index.php");
        exit;
    };

    session_start();
    $doc_root = $_SESSION["DOC_ROOT"];
    $employee_id = $_GET['employee_id'];
    $action = $_GET['action'];

    include($doc_root.'/utils/connect.php');
    $sqlGetemployee = "SELECT user_id FROM employee WHERE id=$employee_id";

    $employee_result = mysqli_query($conn,$sqlGetemployee);
    if ($employee_result->num_rows == 0){
        header("Location:../../../page/404.php");
    };
    
    $row = mysqli_fetch_array($employee_result);
    $user_id = $row['user_id'];
    
    if ($action == 'recover'){
        if ($user_id != ''){
            $sqlUpdateUser = "UPDATE user SET is_active='1' WHERE id='$user_id'";
            if(!mysqli_query($conn,$sqlUpdateUser)){
                die("Something went wrong");
            };
        };

        $_SESSION["message_string"] = "Employee record recovered successfully!";
        $_SESSION["message_class"] = "info";

    } else {

    };

    header("Location:index.php");
    exit;
?>