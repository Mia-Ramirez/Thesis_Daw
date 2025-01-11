<?php
    session_start();
    $base_url = $_SESSION["BASE_URL"];
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="../../../assets/styles/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="../../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="../../../assets/scripts/common_fx.js"></script>
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
            if (isset($_GET['employee_id'])) {
                $employee_id = $_GET['employee_id'];
                $_SESSION["employee_id"] = $employee_id;
                $sqlGetemployee = "SELECT e.first_name, e.last_name, e.address, e.contact_number, e.date_of_birth, u.email, e.user_id, e.job_title, e.employment_date FROM employee e
                            LEFT JOIN user u ON e.user_id=u.id
                            WHERE e.id=$employee_id";

                $employee_result = mysqli_query($conn,$sqlGetemployee);
                if ($employee_result->num_rows == 0){
                    header("Location:../../../page/404.php");
                };

                $row = mysqli_fetch_array($employee_result);
                $_SESSION['employee_user_id'] = $row["user_id"];
            } else {
                header("Location:../../../page/404.php");
            };
        ?>
        <div class="main">
            <div class="row">
                <b>Are you sure you want to archive this Employee with the Details below?</b>
                <form action="process.php" method="post">
                    <h5>First Name: <?php echo $row['first_name'];?></h5>
                    <h5>Last Name: <?php echo $row['last_name'];?></h5>
                    <h5>Date of Birth: <?php echo $row['date_of_birth'];?></h5>
                    <h5>Contact Number: <?php echo $row['contact_number'];?></h5>
                    <h5>Address: <?php echo $row['address'];?></h5>
                    <h5>Email Address: <?php echo $row['email'];?></h5>
                    <h5>Job Title: <?php echo $row['job_title'];?></h5>
                    <h5>Employment Date: <?php echo $row['employment_date'];?></h5>
                    <hr/>
                    <button name="action" id="yes" value="yes">Yes</button>
                    <button name="action" id="no" value="no">No</button>
                </form>
            </div>
        </div>

        <script>
            window.onload = function() {
                setActivePage("nav_employee");
            };
        </script>
    </body>
</html>