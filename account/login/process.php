<?php 
    include('../../utils/connect.php');
    include('../../utils/common_fx_and_const.php'); // getBaseURL

    session_start();

    if (isset($_POST['signIn'])) {
        
        $email_or_username = mysqli_real_escape_string($conn, $_POST['email_or_username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
    
        // Query to check email and password in the database
        $sqlCheck = "SELECT email, role, id, password, username, is_active FROM user WHERE email=\"$email_or_username\" OR username=\"$email_or_username\"";
        $result = mysqli_query($conn, $sqlCheck);
        
        if ($result->num_rows < 1){
            // If email is not yet registered it will display an error message in Login Form
            $_SESSION["login_error"] = "Email is not yet registered!";
            header("Location:index.php");
            exit;
        };

        if ($result->num_rows > 0) {
            $row = mysqli_fetch_array($result);
            $hashedPasswordFromDb = $row["password"]; // Fetch password from your database
            
            if (password_verify($password, $hashedPasswordFromDb)) {
                $role = $row['role'];

                // $_SESSION['user_email'] = $row['email'];
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_role'] = $role;
                $_SESSION['user_name'] = $row['username'];

                // list($first_name, $creationDate) = explode("_", $row['username']);
                $_SESSION['first_name'] = explode("_", $row['username'])[0];
                $_SESSION['BASE_URL'] = getBaseURL();
                
                if ($role == 'customer'){
                    header("Location: ../../customer/index.php");
                } else if ($role == 'admin'){
                    header("Location: ../../admin/index.php");
                } else {
                    header("Location: ../../pharmacist/index.php");
                };
            } else if ($row['is_active'] == 0){
                $_SESSION["login_error"] = "Your Account is disabled/inactive, please contact the Admin";
                $_SESSION["email_username"] = $email_or_username;
                header("Location:index.php");
            } else {
                $_SESSION["login_error"] = "Incorrect Password";
                $_SESSION["email_username"] = $email_or_username;
                header("Location:index.php");
            };
            exit;
        };

        // Fixed email and password condition for admin
        // elseif ($email === 'pharmanest@gmail.com' && $_POST['password'] === 'adminpassword') {
        //     session_start();
        //     $_SESSION['email'] = $email; // Set session for fixed login
        //     header("Location: adminpage.php"); // Redirect to admin or special page
        //     exit;
        // } 
        //   // Fixed email and password condition for pharmacist
        //   elseif ($email === 'pharmanest@gmail.com' && $_POST['password'] === 'pharmacistpassword') {
        //     session_start();
        //     $_SESSION['email'] = $email; // Set session for fixed login
        //     header("Location: pharmacistpage.php"); // Redirect to admin or special page
        //     exit;
        // } 
        // else {
        //     echo "Not Found, Incorrect Email or Password.";
        // }
    }
?>