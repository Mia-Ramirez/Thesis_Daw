<!DOCTYPE html>
<?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        session_start();
        if (!isset($_SESSION["employee_id"])){
            header("Location:../list/index.php");
            exit;
        };

        if (isset($_POST["action"])) {
            include('../../../utils/connect.php');
            if ($_POST['action'] === 'update_employee') {
                $first_name = mysqli_real_escape_string($conn, $_POST["first_name"]);
                $last_name = mysqli_real_escape_string($conn, $_POST["last_name"]);
                $date_of_birth = mysqli_real_escape_string($conn, $_POST["date_of_birth"]);
                $contact_number = mysqli_real_escape_string($conn, $_POST["contact_number"]);
                $address = mysqli_real_escape_string($conn, $_POST["address"]);
                $job_title = mysqli_real_escape_string($conn, $_POST["job_title"]);
                $employment_date = mysqli_real_escape_string($conn, $_POST["employment_date"]);

                $email = mysqli_real_escape_string($conn, $_POST["email"]);
                
                $employee_id = $_SESSION['employee_id'];
                
                $checkEmployee="SELECT user_id FROM employee WHERE ((first_name='$first_name' AND last_name='$last_name') OR contact_number='$contact_number') AND id!=$employee_id";
                $employee_result=$conn->query($checkEmployee);
                
                if ($employee_result->num_rows != 0){
                    $_SESSION["message_string"] = "This Employee/Contact Number already exists/used !";
                    $_SESSION["message_class"] = "danger";
                    header("Location:index.php?employee_id=".$employee_id);
                    exit;
                };

                $sqlGetCurrentRecord = "SELECT user_id FROM employee WHERE id=$employee_id";
                
                $result = mysqli_query($conn,$sqlGetCurrentRecord);
                $row = mysqli_fetch_array($result);
                $user_id = $row['user_id'];
                
                if ($email){
                    $checkUser="SELECT * FROM user WHERE email='$email' AND id!=$user_id";
                    $user_result=$conn->query($checkUser);
                    if ($user_result->num_rows > 0){
                        $_SESSION["message_string"] = "Email Address already used !";
                        $_SESSION["message_class"] = "danger";
                        header("Location:index.php?employee_id=".$employee_id);
                        exit;
                    };
                };

                if ($job_title == 'Manager'){ $role = "admin";}
                else { $role = "pharmacist";};
                
                $sqlUpdateEmployee = "UPDATE employee SET first_name='$first_name', last_name='$last_name', contact_number='$contact_number', address='$address', job_title='$job_title', date_of_birth='$date_of_birth', employment_date='$employment_date' WHERE id='$employee_id'";
                
                if(!mysqli_query($conn,$sqlUpdateEmployee)){
                    die("Something went wrong");
                };
                $username = $first_name."_".$last_name."_".date('YmdHis');
                $sqlUpdateUser = "UPDATE user SET role='$role', email='$email', username='$username' WHERE id='$user_id'";
                
                if(!mysqli_query($conn,$sqlUpdateUser)){
                    die("Something went wrong");
                };

                $_SESSION["message_string"] = "Employee details updated successfully!";
                $_SESSION["message_class"] = "info";
                header("Location:index.php?employee_id=".$employee_id);
                exit;
            };
        };
    };
?>