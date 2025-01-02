<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST['signUp'])){
            require '../../apps/PHPMailer/src/Exception.php';
            require '../../apps/PHPMailer/src/PHPMailer.php';
            require '../../apps/PHPMailer/src/SMTP.php';

            include('../../utils/connect.php');
            include('../../utils/email.php');
            include('../../utils/common_fx_and_const.php'); // getBaseURL, enable_logging, enable_send_email

            session_start();
            $firstName= mysqli_real_escape_string($conn, $_POST['fName']);
            $lastName= mysqli_real_escape_string($conn, $_POST['lName']);
            $address= mysqli_real_escape_string($conn, $_POST['address']);
            $contact= mysqli_real_escape_string($conn, $_POST['contact']);
            //$contact= mysqli_real_escape_string($conn,$_POST['contact']);
            $email= mysqli_real_escape_string($conn, $_POST['email']);
            $password= mysqli_real_escape_string($conn, $_POST['password']);

            $checkUser="SELECT * FROM user WHERE email='$email'";
            $user_result=$conn->query($checkUser);

            $checkTemporaryRecord="SELECT * FROM temporary_record WHERE JSON_UNQUOTE(JSON_EXTRACT(data, '$.email'))='$email'";
            $temp_record_result=$conn->query($checkTemporaryRecord);

            if ($user_result->num_rows > 0){
                $_SESSION["register_error"] = "Email Address already exists! Please proceed to Login or Forgot Password";
                header("Location:index.php");
                exit;
            } else if ($temp_record_result->num_rows > 0) {
                $_SESSION["register_error"] = "User already submitted registration, please verify on your emai!";
                header("Location:index.php");
                exit;
            } else{
                $currentDate = date('Y-m-d H:i');
                    
                // Encrypt password for security
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $deletionDate = date('Y-m-d H:i:s', strtotime($currentDate . ' +1 day')); // add 1 day to current time
                $username = $firstName."_".$lastName."_".date('YmdHis');
                $reference_key = base64_encode("register|".$email."|".$deletionDate); // generate a random key
                $jsonData = json_encode([
                    "first_name" => $firstName,
                    "last_name" => $lastName,
                    "address" => $address,
                    "contact" => $contact,
                    "email" => $email,
                    "username" => $username,
                    "password" => $hashed_password,
                    "role" => "customer"
                ]);
        
                if ($jsonData === false) {
                    die("JSON encoding error: " . json_last_error_msg());
                };
                
                $base_url = getBaseURL();
                $link = $base_url . "/account/activate?key=$reference_key";
            
                if ($enable_logging == "1"){
                    error_log("This is a log message for debugging purposes: ".$link);
                };
                
                // Insert data to Database
                $sqlInsert = "INSERT INTO temporary_record(reference_key, data, deletion_date) VALUES ('$reference_key', '$jsonData', '$deletionDate')";
                if(!mysqli_query($conn,$sqlInsert)){
                    die("Something went wrong");
                };

                // $message = getCurrentURL();
                if ($enable_send_email == "1"){
                    send_email(
                        $email,
                        "Account Registration (Step 2)",
                        "Hello $firstName!<br/> You are only one step to register your account. Kindly click the link below to activate your account.<br/>$link" 
                    );
                };

                $_SESSION["register_success"] = "User request registration submitted, please verify your account by clicking the link sent to your email";
                header("Location:../login/index.php");
                exit;
            };
        };
    };
?>