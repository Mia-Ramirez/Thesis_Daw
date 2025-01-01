<!DOCTYPE html>
<?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        session_start();
        if (!isset($_SESSION["user_id"])){
            header("Location:../index.php");
            exit;
        };

        if (isset($_POST["action"])) {
            include('../../utils/connect.php');
            if ($_POST['action'] === 'update_customer') {
                $first_name = mysqli_real_escape_string($conn, $_POST["first_name"]);
                $last_name = mysqli_real_escape_string($conn, $_POST["last_name"]);
                $contact_number = mysqli_real_escape_string($conn, $_POST["contact_number"]);
                $address = mysqli_real_escape_string($conn, $_POST["address"]);
            
                $email = mysqli_real_escape_string($conn, $_POST["email"]);
                $password = mysqli_real_escape_string($conn, $_POST["password"]);
                
                $user_id = $_SESSION['user_id'];
                
                $checkCustomer="SELECT id FROM customer WHERE ((first_name='$first_name' AND last_name='$last_name') OR contact_number='$contact_number') AND user_id!=$user_id";
                $customer_result=$conn->query($checkCustomer);
                
                if ($customer_result->num_rows != 0){
                    $_SESSION["message_string"] = "This Customer/Contact Number already exists/used !";
                    $_SESSION["message_class"] = "danger";
                    header("Location:index.php");
                    exit;
                };

                $sqlGetCurrentRecord = "SELECT id FROM customer WHERE user_id=$user_id";
                
                $result = mysqli_query($conn,$sqlGetCurrentRecord);
                $row = mysqli_fetch_array($result);
                $customer_id = $row['id'];

                if ($email){
                    $checkUser="SELECT * FROM user WHERE email='$email' AND id!=$user_id";
                    $user_result=$conn->query($checkUser);
                    if ($user_result->num_rows > 0){
                        $_SESSION["message_string"] = "Email Address already used !";
                        $_SESSION["message_class"] = "danger";
                        header("Location:index.php");
                        exit;
                    };
                };

                $password_len = strlen($password);
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $sqlUpdateUser = "UPDATE user SET email='$email', password_length = $password_len, password = '$hashed_password' WHERE id=$user_id";
                if(!mysqli_query($conn,$sqlUpdateUser)){
                    die("Something went wrong");
                };


                $sqlUpdateCustomer = "UPDATE customer SET first_name='$first_name', last_name='$last_name', contact_number='$contact_number', address='$address' WHERE id='$customer_id'";
                
                if(!mysqli_query($conn,$sqlUpdateCustomer)){
                    die("Something went wrong");
                };

                $_SESSION["message_string"] = "Profile info updated successfully!";
                $_SESSION["message_class"] = "info";
                header("Location:index.php");
                exit;

            };
        };
    };
?>