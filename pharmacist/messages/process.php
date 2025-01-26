<!DOCTYPE html>
<?php
    session_start();
    $doc_root = $_SESSION["DOC_ROOT"];
    $user_id = $_SESSION['user_id'];

    include($doc_root.'/utils/connect.php');
    include($doc_root.'/utils/common_fx_and_const.php');

    if (isset($_POST["submit_response"])) {
        $response = mysqli_real_escape_string($conn, $_POST["response"]);
        $customer_id = $_SESSION["customer_id"];
        
        $sqlGetLatestMessage = "SELECT id FROM messages WHERE customer_id=$customer_id ORDER BY id DESC LIMIT 1";
        
        $latest_message_result = mysqli_query($conn, $sqlGetLatestMessage);
        // $num_rows = $latest_message_result->num_rows;
        if ($latest_message_result->num_rows > 0){
            $row = mysqli_fetch_array($latest_message_result);
            $message_id = $row["id"];

            $sqlGetEmployee = "SELECT id FROM employee WHERE user_id=".$user_id;
            $employee_result = mysqli_query($conn,$sqlGetEmployee);
            $row = mysqli_fetch_array($employee_result);
            $employee_id = $row['id'];

            $sqlUpdate = "UPDATE messages SET response='$response', employee_id='$employee_id' WHERE id='$message_id'";
            if(!mysqli_query($conn,$sqlUpdate)){
                die("Something went wrong");
            };
        };
        header("Location:index.php?customer_id=$customer_id");
    };
?>