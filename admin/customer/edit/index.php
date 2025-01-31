<?php
    session_start();
    $base_url = $_SESSION["BASE_URL"];
    $doc_root = $_SESSION["DOC_ROOT"];
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>assets/styles/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="../../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="<?php echo $base_url;?>assets/scripts/common_fx.js"></script>
        <?php include '../../components/title.php'; ?>
    </head>

    <body>
        <?php include '../../components/unauth_redirection.php'; ?>

        <?php include '../../components/side_nav.php'; ?>
                
        <?php
            $current_page_title = "update customer details";
            include '../../components/top_nav.php';
        ?>
        <div class="content" style="margin-top: 11%;">
        <?php
            include($doc_root.'/utils/connect.php');
            if (isset($_GET['customer_id'])) {
                $customer_id = $_GET['customer_id'];
                $_SESSION["customer_id"] = $customer_id;
                $sqlGetCustomer = "SELECT c.first_name, c.last_name, c.address, c.contact_number, c.date_of_birth, c.sex, u.email FROM customer c
                            LEFT JOIN user u ON c.user_id=u.id
                            WHERE c.id=$customer_id";

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
                            
                        <?php
                        unset($_SESSION["message_string"]);
                        unset($_SESSION["message_class"]);
                    }
                ?>
                <form action="process.php" method="post">
                    <div class="column">
                        <p>
                            <label for="first_name">First Name:</label><br>
                            <input type="text" name="first_name" value="<?php echo $row['first_name'];?>" required>
                        </p>

                        <p>
                            <label for="last_name">Last Name:</label><br>
                            <input type="text" name="last_name" value="<?php echo $row['last_name'];?>" required>
                        </p>

                        <div class="row">
                            <p class="column">
                                <label for="date_of_birth">Date of Birth:</label><br>
                                <input type="date" name="date_of_birth" value="<?php echo $row['date_of_birth'];?>" required>
                            </p>
                            <p class="column">
                                <label for="sex">Sex: </label><br>
                                <select id="sex" name="sex" required>
                                    <option value="">Select</option>
                                    <option <?php if ($row['sex'] == "Female"){echo "selected";};?>>Female</option>
                                    <option <?php if ($row['sex'] == "Male"){echo "selected";};?>>Male</option>
                                    <option <?php if ($row['sex'] == "Others"){echo "selected";};?>>Others</option>
                                </select>
                            </p>
                        </div>
                    
                    </div>
                    <div class="column">
                        <p>
                            <label for="contact_number">Contact Number: </label><br>
                            <input type="text" id="contact_number" name="contact_number" required pattern="[+0-9]*" title="Only numbers and + are allowed" maxlength=13  value="<?php echo $row['contact_number'];?>">
                        </p>

                        <p>
                            <label for="address">Address:</label><br>
                            <input type="text" name="address" value="<?php echo $row['address'];?>" required>
                        </p>

                        <p>
                            <label for="email">Email Address:</label><br>
                            <input type="email" name="email" value="<?php echo $row['email'];?>">
                        </p>
                    </div>
                    
                
                    <button style="background-color: red; color: white;" name="action" value="update_customer">Update</button>
                </form>
            </div>
        </div>
        </div>
        <script>
            window.onload = function() {
                setActivePage("nav_customer");
            };
        </script>
    </body>
</html>