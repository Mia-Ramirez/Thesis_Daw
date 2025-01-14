<!DOCTYPE html>
<?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        session_start();
        if (isset($_POST["action"])) {
            include('../../../utils/connect.php');
            if ($_POST['action'] === 'add_employee') {
                $first_name = mysqli_real_escape_string($conn, $_POST["first_name"]);
                $last_name = mysqli_real_escape_string($conn, $_POST["last_name"]);
                $date_of_birth = mysqli_real_escape_string($conn, $_POST["date_of_birth"]);
                $contact_number = mysqli_real_escape_string($conn, $_POST["contact_number"]);
                $address = mysqli_real_escape_string($conn, $_POST["address"]);
                $job_title = mysqli_real_escape_string($conn, $_POST["job_title"]);
                $employment_date = mysqli_real_escape_string($conn, $_POST["employment_date"]);

                $email = mysqli_real_escape_string($conn, $_POST["email"]);
                
                $_SESSION["date_of_birth"] = $date_of_birth;
                $_SESSION["contact_number"] = $contact_number;
                $_SESSION["address"] = $address;
                $_SESSION["email"] = $email;
                $_SESSION["first_name"] = $first_name;
                $_SESSION["last_name"] = $last_name;
                $_SESSION["job_title"] = $job_title;
                $_SESSION["employment_date"] = $employment_date;

                $checkEmployee="SELECT id FROM employee WHERE ((first_name='$first_name' AND last_name='$last_name') OR contact_number='$contact_number')";
                $employee_result=$conn->query($checkEmployee);

                if ($employee_result->num_rows != 0){
                    $_SESSION["message_string"] = "This Employee/Contact Number already exists/used !";
                    $_SESSION["message_class"] = "danger";
                    header("Location:index.php");
                    exit;
                };

                if ($email && $email != ""){
                    $checkUser="SELECT id FROM user WHERE email='$email'";
                    $user_result=$conn->query($checkUser);
                    if ($user_result->num_rows > 0){
                        $_SESSION["message_string"] = "Email Address already used !";
                        $_SESSION["message_class"] = "danger";
                        header("Location:index.php");
                        exit;
                    };
                    
                    $username = $first_name."_".$last_name."_".date('YmdHis');

                    if ($job_title == 'Manager'){ $role = "admin";}
                    else { $role = "pharmacist";};

                    $sqlInsertUser = "INSERT INTO user(username, email, role) VALUES ('$username','$email','$role')";
                    if(!mysqli_query($conn,$sqlInsertUser)){
                        die("Something went wrong");
                    };

                    $user_id = mysqli_insert_id($conn);
                };
                
                $sqlInsertEmployee = "INSERT INTO employee(first_name, last_name, contact_number, address, user_id, date_of_birth, job_title, employment_date) VALUES ('$first_name','$last_name','$contact_number','$address','$user_id','$date_of_birth','$job_title','$employment_date')";
                
                if(!mysqli_query($conn,$sqlInsertEmployee)){
                    die("Something went wrong");
                };

                $_SESSION["message_string"] = "Employee added successfully!";
                $_SESSION["message_class"] = "success";
                header("Location:index.php");
                exit;
            };

        };
    };
?>