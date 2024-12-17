<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="../../../assets/styles/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="../../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="../../../assets/scripts/common_fx.js"></script>
        <title>
        Admin Dashboard
        </title>
    </head>

    <body>
        <?php
            session_start();
            $current_page_title = "update customer details";
            include '../../components/unauth_redirection.php';
        ?>
        
        <?php include '../../components/side_nav.php'; ?>
                
        <?php include '../../components/top_nav.php'; ?>  


        <?php
            include('../../utils/connect.php');
            if (isset($_GET['customer_id'])) {
                $customer_id = $_GET['customer_id'];
                $_SESSION["customer_id"] = $customer_id;
                $sqlGetCustomer = "SELECT * FROM customer WHERE id=$customer_id";
                $customer_result = mysqli_query($conn,$sqlGetCustomer);
                if ($customer_result->num_rows == 0){
                    header("Location:../../../page/404.php");
                }

                $row = mysqli_fetch_array($customer_result);
            } else {
                header("Location:../../../page/404.php");
            };
        ?>
        <div class="main">
            <div class="row">
                <?php
                    if (isset($_SESSION["message_string"])) {
                        ?>
                            <div class="alert alert-<?php echo $_SESSION["message_class"] ?>">
                                <?php 
                                echo $_SESSION["message_string"];
                                ?>
                            </div>
                            <hr style="border: 1px solid #000 !important; width: 80%; margin: 20px auto;">
                        <?php
                        unset($_SESSION["message_string"]);
                        unset($_SESSION["message_class"]);
                    }
                ?>
                <form action="process.php" method="post">
                    <div class="column">
                        <p>
                            <label for="first_name">First Name:</label><br>
                            <input type="text" name="first_name" value="<?php echo $row['first_name'];?>">
                        </p>

                        <p>
                            <label for="last_name">Last Name:</label><br>
                            <input type="text" name="last_name" value="<?php echo $row['last_name'];?>">
                        </p>

                        <div class="row">
                            <p class="column">
                                <label for="date_of_birth">Date of Birth:</label><br>
                                <input type="date" name="date_of_birth" value="<?php echo $row['date_of_birth'];?>">
                            </p>
                            <p class="column">
                                <label for="sex">Sex: </label><br>
                                <select id="sex" name="sex" value="<?php echo $row['sex'];?>">
                                    <option value="selected">Select</option>
                                    <option>Female</option>
                                    <option>Male</option>
                                    <option>Others</option>
                                </select>
                            </p>
                        </div>
                    
                    </div>
                    <div class="column">
                        <p>
                            <label for="contact_number">Contact Number: </label><br>
                            <input type="text" name="contact_number" value="<?php echo $row['contact_number'];?>">
                        </p>

                        <p>
                            <label for="address">Address:</label><br>
                            <input type="text" name="address" value="<?php echo $row['address'];?>">
                        </p>

                        <p>
                            <label for="email">Email Address:</label><br>
                            <input type="email" name="email" value="<?php echo $row['email'];?>" disabled>
                        </p>
                    </div>
                    
                
                    <button name="action" id="update-customer" value="update_customer">Submit</button>
                </form>
            </div>
        </div>

        <script>
            window.onload = function() {
                setActivePage("nav_customer");
            };
        </script>
    </body>
</html>