<!DOCTYPE html>
<?php
    include('../../utils/connect.php');
    
    require '../../apps/PHPMailer/src/Exception.php';
    require '../../apps/PHPMailer/src/PHPMailer.php';
    require '../../apps/PHPMailer/src/SMTP.php';

    include('../../utils/email.php');
    include('../../utils/common_fx_and_const.php'); // enable_logging, getBaseURL, enable_send_email

    if ($_POST['action'] === 'recover_password') {
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $sqlCheck = "SELECT username, email FROM user WHERE email=\"$email\"";
        $result = mysqli_query($conn, $sqlCheck);
        session_start();
        if ($result->num_rows < 1){
            // If email is not yet registered it will display an error message in Login Form
            $_SESSION["message_string"] = "Email is not yet registered!";
            $_SESSION["message_class"] = "danger";

        } else {
            $row = mysqli_fetch_array($result);
            $first_name = explode("_", $row['username'])[0];
            $encoded_key = base64_encode("recover_password|".$row["email"]."|".date('YmdHis'));
            $base_url = getBaseURL();
            $link = $base_url . "account/set_password?key=$encoded_key";
            
            if ($enable_logging == "1"){
                error_log("This is a log message for debugging purposes: ".$link);
            };

            if ($enable_send_email == "1"){
                send_email(
                    $email,
                    "Forgot Password (Step 2)",
                    "Hello $first_name!<br/> You are only one step to reset your password. Kindly click the link below and follow the instructions.<br/>$link" 
                );
            };
            
            $_SESSION["message_string"] = "Email Sent";
            $_SESSION["message_class"] = "info";
        }
        header("Location:index.php");
        exit;
    }
?>