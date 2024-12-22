<!DOCTYPE html>
<?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        session_start();
        if (!isset($_SESSION["employee_id"])){
            header("Location:../list/index.php");
            exit;
        };

        if (isset($_POST["action"])) {
            if ($_POST['action'] === 'yes') {
                include('../../../utils/connect.php');
                $user_id = $_SESSION['employee_user_id'];
                $employee_id = $_SESSION['employee_id'];

                if ($user_id != ''){
                    $sqlUpdateUser = "UPDATE user SET is_active='0' WHERE id='$user_id'";
                    if(!mysqli_query($conn,$sqlUpdateUser)){
                        die("Something went wrong");
                    };
                };

                $_SESSION["message_string"] = "Employee archived successfully!";
                $_SESSION["message_class"] = "info";
            };

            header("Location:../list/index.php");
            exit;
        };
    };
?>