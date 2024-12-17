<!DOCTYPE html>
<?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        session_start();
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
                
                
            };

        };
    };
?>