<!DOCTYPE html>
<?php
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location:index.php");
        exit;
    };

    session_start();
    $doc_root = $_SESSION["DOC_ROOT"];

    require($doc_root.'/apps/PHPMailer/src/Exception.php');
    require($doc_root.'/apps/PHPMailer/src/PHPMailer.php');
    require($doc_root.'/apps/PHPMailer/src/SMTP.php');
    
    include($doc_root.'/utils/connect.php');
    include($doc_root.'/utils/email.php');
    include($doc_root.'/utils/sms.php');
    include($doc_root.'/utils/common_fx_and_const.php');

    $user_id = $_SESSION['user_id'];
    $order_id= mysqli_real_escape_string($conn, $_POST['order_id']);
    $order_reference_number = $_SESSION['order_reference_number'];

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

        if ($action == 'for_pickup'){
            $message = "Your Order with Reference Number ".$order_reference_number." is ready to Pick-up\n\nPharmanest";
            if ($enable_logging == "1"){
                error_log("This is a log message for debugging purposes: ");
                error_log($message);
            };

            if (($enable_send_sms == "1") && (!is_null($_SESSION["customer_mobile_number"]))){
                sendSMS($_SESSION["customer_mobile_number"], $message);
            };
        };
    };

    $customer_email = $_SESSION['customer_email'];
    if (($enable_send_email == "1") && (!is_null($customer_email))){
        send_email(
            $customer_email,
            "Order Updates",
            "Hello!<br/>Your Order ".$order_reference_number." has been ".$history_remarks.".<br/>" 
        );
    };
    unset($_SESSION['customer_email']);
    unset($_SESSION['order_reference_number']);
    
    header("Location:index.php?order_id=".$order_id);
    exit;
?>