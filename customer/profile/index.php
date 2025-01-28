<?php
    session_start();
    $base_url = $_SESSION["BASE_URL"];
    $doc_root = $_SESSION["DOC_ROOT"];
    if (isset($_SESSION['from_prescription_page'])){
        unset($_SESSION['from_prescription_page']);
    };
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Value MED Generics Pharmacy</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>assets/styles/bootstrap.css">
        <link rel="stylesheet" href="../styles.css">
        <link rel="stylesheet" href="styles.css">
        <script src="<?php echo $base_url;?>assets/scripts/common_fx.js"></script>
    </head>
    <body class="body">
        <div class="content" style="margin-top: 12%;">
        <?php include '../components/unauth_redirection.php'; ?>
        
        <?php include '../components/navbar.php'; ?>  

        <?php
            $user_id = $_SESSION['user_id'];
            
            include($doc_root.'/utils/connect.php');

            $sqlGetCustomer = "SELECT c.first_name, c.last_name, c.address, c.contact_number, c.date_of_birth, c.sex, u.email, u.password_length, c.id AS customer_id, u.password FROM customer c
                            LEFT JOIN user u ON c.user_id=u.id
                            WHERE c.user_id=$user_id";

            $customer_result = mysqli_query($conn,$sqlGetCustomer);
            if ($customer_result->num_rows == 0){
                header("Location:../../../page/404.php");
            };

            $row = mysqli_fetch_array($customer_result);

            if (isset($_SESSION['customer_id']) == false){
                $_SESSION['customer_id'] = $row['customer_id'];
            };

            if ($row["password_length"] !== null){
                $pass_len = $row["password_length"];
            } else {
                $pass_len = 8;
            }
            $pass_placeholder = str_repeat('•', $pass_len);
            $_SESSION["pass_placeholder"] = $pass_placeholder;
            $_SESSION["user_hashed_password"] = $row["password"];
        ?>
        
        <div class="form-container">
            <?php
                if (isset($_SESSION["message_string"])) {
                    ?>
                        <div class="alert alert-<?php echo $_SESSION["message_class"] ?>">
                            <?php 
                            echo $_SESSION["message_string"];
                            ?>
                        </div>
                        
                    <?php
                    unset($_SESSION["message_string"]);
                    unset($_SESSION["message_class"]);
                }
            ?>
            <form method="POST" action="process.php">
                <center><h2>Your Information</h2></center>
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="first_name" value="<?php echo $row['first_name'];?>" required>
                    <label for="first_name">First Name:</label><br>
                    
                </div>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="last_name" value="<?php echo $row['last_name'];?>" required>
                    <label for="last_name">Last Name:</label><br>
                </div>

                <div class="input-group">
                    <i class="fas fa-address-book"></i>
                    <input type="text" name="address" value="<?php echo $row['address'];?>" required>
                    <label for="address">Address:</label><br>
                </div>

                <div class="input-group">
                    <i class="fas fa-mobile"></i>
                    <input type="text" id="contact_number" name="contact_number" required pattern="[+0-9]*" title="Only numbers and + are allowed" maxlength=13  value="<?php echo $row['contact_number'];?>">
                    <label for="contact_number">Contact Number: </label><br>
                </div>

                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" value="<?php echo $row['email'];?>">
                    <label for="email">Email Address:</label><br>
                </div>

                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" id="password" placeholder="Password" required value="<?php echo $pass_placeholder; ?>">
                    <label for="password">Password</label>
                </div>

                <button name="action" value="update_customer">Update</button>
            </form>
        </div>
        </div>
        <script src="../script.js"></script>
        
        <script>
            window.onload = function() {
                setActivePage("nav_menu");
            };
        </script>
    
</body>
</html>