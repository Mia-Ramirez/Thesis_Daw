<?php
    session_start();
    $base_url = $_SESSION["BASE_URL"];
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
            $current_page_title = "archive customer";
            include '../../components/top_nav.php';
        ?>

        <?php
            include('../../../utils/connect.php');
            if (isset($_GET['customer_id'])) {
                $customer_id = $_GET['customer_id'];
                $_SESSION["customer_id"] = $customer_id;
                $sqlGetCustomer = "SELECT c.first_name, c.last_name, c.address, c.contact_number, c.date_of_birth, c.sex, u.email, c.user_id FROM customer c
                            LEFT JOIN user u ON c.user_id=u.id
                            WHERE c.id=$customer_id";

                $customer_result = mysqli_query($conn,$sqlGetCustomer);
                if ($customer_result->num_rows == 0){
                    header("Location:../../../page/404.php");
                };

                $row = mysqli_fetch_array($customer_result);
                $_SESSION['customer_user_id'] = $row["user_id"];
            } else {
                header("Location:../../../page/404.php");
            };
        ?>
        <div class="main">
            <div class="row">
                <b>Are you sure you want to archive this Customer with the Details below?</b>
                <form action="process.php" method="post">
                    <h5>First Name: <?php echo $row['first_name'];?></h5>
                    <h5>Last Name: <?php echo $row['last_name'];?></h5>
                    <h5>Date of Birth: <?php echo $row['date_of_birth'];?></h5>
                    <h5>Sex: <?php echo $row['sex'];?></h5>
                    <h5>Contact Number: <?php echo $row['contact_number'];?></h5>
                    <h5>Address: <?php echo $row['address'];?></h5>
                    <h5>Email Address: <?php echo $row['email'];?></h5>
                    <hr/>
                    <button name="action" id="yes" value="yes">Yes</button>
                    <button name="action" id="no" value="no">No</button>
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