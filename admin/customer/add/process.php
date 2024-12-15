<!DOCTYPE html>
<?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        session_start();
        if (isset($_POST["action"])) {
            include('../../../utils/connect.php');
            if ($_POST['action'] === 'add_customer') {
                $first_name = mysqli_real_escape_string($conn, $_POST["first_name"]);
                $last_name = mysqli_real_escape_string($conn, $_POST["last_name"]);
                $date_of_birth = mysqli_real_escape_string($conn, $_POST["date_of_birth"]);
                $sex = mysqli_real_escape_string($conn, $_POST["sex"]);
                $contact_number = mysqli_real_escape_string($conn, $_POST["contact_number"]);
                $address = mysqli_real_escape_string($conn, $_POST["address"]);

                $email = mysqli_real_escape_string($conn, $_POST["email"]);
                $user_id = NULL;
                
                $checkCustomer="SELECT id FROM customer WHERE first_name='$first_name' AND last_name='$last_name' AND contact_number='$contact_number'";
                $customer_result=$conn->query($checkCustomer);

                if ($customer_result->num_rows != 0){
                    $_SESSION["message_string"] = "This Customer Already Exists !";
                    $_SESSION["message_class"] = "danger";
                    header("Location:index.php");
                    exit;
                };

                if ($email && $email != ""){
                    $checkUser="SELECT * FROM user WHERE email='$email'";
                    $user_result=$conn->query($checkUser);
                    if ($user_result->num_rows > 0){
                        $_SESSION["message_string"] = "Email Address Already Exists !";
                        $_SESSION["message_class"] = "danger";
                        header("Location:index.php");
                        exit;
                    };
                    
                    $username = $first_name."_".$last_name."_".date('YmdHis');
                    $sqlInsertUser = "INSERT INTO user(username , email , role) VALUES ('$username','$email', 'customer')";
                    if(!mysqli_query($conn,$sqlInsertUser)){
                        die("Something went wrong");
                    };

                    $user_id = mysqli_insert_id($conn);
                };
                
                if ($user_id){
                    $sqlInsertCustomer = "INSERT INTO customer(first_name , last_name , contact_number , address, user_id, date_of_birth, sex) VALUES ('$first_name','$last_name','$contact_number', '$address', '$user_id', '$date_of_birth', '$sex')";
                } else {
                    $sqlInsertCustomer = "INSERT INTO customer(first_name , last_name , contact_number , address, date_of_birth, sex) VALUES ('$first_name','$last_name','$contact_number', '$address', '$date_of_birth', '$sex')";
                };
                
                if(!mysqli_query($conn,$sqlInsertCustomer)){
                    die("Something went wrong");
                };

                $_SESSION["message_string"] = "Customer added successfully";
                $_SESSION["message_class"] = "success";
                header("Location:index.php");
                exit;
            };

        };
    };
?>