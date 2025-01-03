<!DOCTYPE html>
<?php
    include('../../utils/connect.php');
    include('../../utils/common_fx_and_const.php'); // loggedChanges
    if ($_POST['action'] === 'set_password') {
        $password = mysqli_real_escape_string($conn, $_POST["password"]);
        $confirm_password = mysqli_real_escape_string($conn, $_POST["confirm_password"]);
        $password_len = strlen($password);
        session_start();
        
        $email = $_SESSION["email"];
        // $user_id = $_SESSION["user_id"];
        //$username = $_SESSION["user_name"];

        if ($password != $confirm_password){
            $key = $_SESSION["key"];
            $_SESSION["message_string"] = "Passwords doesn't match!";
            $_SESSION["message_class"] = "danger";
            header("Location:index.php?key=$key");

        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $sqlUpdate = "UPDATE user SET password = '$hashed_password', password_length = $password_len WHERE email='$email'";
            if(mysqli_query($conn,$sqlUpdate)){
                //loggedChanges($conn, $user_id, "Account", "Set New Password (Forgot Password)", $username);
                $_SESSION["register_success"] = "Password updated successfully, please sign in using your new password";
                header("Location:../login/index.php");
            }else{
                die("Something went wrong");
            }
        }
    }
?>