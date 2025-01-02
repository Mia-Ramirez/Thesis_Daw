<!DOCTYPE html>
<?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        session_start();
        if (!isset($_SESSION["customer_id"])){
            header("Location:../list/index.php");
            exit;
        };

        if (isset($_POST["action"])) {
            include('../../../utils/connect.php');
            if ($_POST['action'] === 'update_customer') {
                $first_name = mysqli_real_escape_string($conn, $_POST["first_name"]);
                $last_name = mysqli_real_escape_string($conn, $_POST["last_name"]);
                $date_of_birth = mysqli_real_escape_string($conn, $_POST["date_of_birth"]);
                $sex = mysqli_real_escape_string($conn, $_POST["sex"]);
                $contact_number = mysqli_real_escape_string($conn, $_POST["contact_number"]);
                $address = mysqli_real_escape_string($conn, $_POST["address"]);
            
                $email = mysqli_real_escape_string($conn, $_POST["email"]);
                
                $customer_id = $_SESSION['customer_id'];
                
                $checkCustomer="SELECT user_id FROM customer WHERE ((first_name='$first_name' AND last_name='$last_name') OR contact_number='$contact_number') AND id!=$customer_id";
                $customer_result=$conn->query($checkCustomer);
                
                if ($customer_result->num_rows != 0){
                    $_SESSION["message_string"] = "This Customer/Contact Number already exists/used !";
                    $_SESSION["message_class"] = "danger";
                    header("Location:index.php?customer_id=".$customer_id);
                    exit;
                };

                $sqlGetCurrentRecord = "SELECT c.user_id FROM customer c
                            LEFT JOIN user u ON c.user_id=u.id
                            WHERE c.id=$customer_id";
                
                $result = mysqli_query($conn,$sqlGetCurrentRecord);
                $row = mysqli_fetch_array($result);
                $user_id = $row['user_id'];
                
                if ($email && !is_null($user_id)){
                    $checkUser="SELECT * FROM user WHERE email='$email' AND id!=$user_id";
                    $user_result=$conn->query($checkUser);
                    if ($user_result->num_rows > 0){
                        $_SESSION["message_string"] = "Email Address already used !";
                        $_SESSION["message_class"] = "danger";
                        header("Location:index.php?customer_id=".$customer_id);
                        exit;
                    };
                };
                
                if (!is_null($row["user_id"])){
                    $sqlUpdateUser = "UPDATE user SET email='$email' WHERE id='$user_id'";
                    if(!mysqli_query($conn,$sqlUpdateUser)){
                        die("Something went wrong");
                    };

                } elseif (is_null($row["user_id"]) && $email != "") {
                    $username = $first_name."_".$last_name."_".date('YmdHis');
                    $sqlInsertUser = "INSERT INTO user(username , email , role) VALUES ('$username','$email', 'customer')";
                    if(!mysqli_query($conn,$sqlInsertUser)){
                        die("Something went wrong");
                    };
                    $user_id = mysqli_insert_id($conn);

                };

                if ($user_id){
                    $sqlUpdateCustomer = "UPDATE customer SET first_name='$first_name', last_name='$last_name', contact_number='$contact_number', address='$address', sex='$sex', date_of_birth='$date_of_birth', user_id=$user_id WHERE id='$customer_id'";
                } else {
                    $sqlUpdateCustomer = "UPDATE customer SET first_name='$first_name', last_name='$last_name', contact_number='$contact_number', address='$address', sex='$sex', date_of_birth='$date_of_birth' WHERE id='$customer_id'";
                };
                
                if(!mysqli_query($conn,$sqlUpdateCustomer)){
                    die("Something went wrong");
                };

                $_SESSION["message_string"] = "Customer details updated successfully!";
                $_SESSION["message_class"] = "info";
                header("Location:index.php?customer_id=".$customer_id);
                exit;

            };
        };
    };
?>