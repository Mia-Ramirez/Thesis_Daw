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
            $current_page_title = "update employee details";
            include '../../components/top_nav.php';
        ?> 

        <?php
            include('../../../utils/connect.php');
            if (isset($_GET['employee_id'])) {
                $employee_id = $_GET['employee_id'];
                $_SESSION["employee_id"] = $employee_id;
                $sqlGetEmployee = "SELECT e.first_name, e.last_name, e.address, e.contact_number, e.date_of_birth, e.job_title, e.employment_date, u.email, e.user_id FROM employee e
                            LEFT JOIN user u ON e.user_id=u.id
                            WHERE e.id=$employee_id";

                $employee_result = mysqli_query($conn,$sqlGetEmployee);
                if ($employee_result->num_rows == 0){
                    header("Location:../../../page/404.php");
                }

                $row = mysqli_fetch_array($employee_result);
                
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

                        <p class="column">
                            <label for="date_of_birth">Date of Birth:</label><br>
                            <input type="date" name="date_of_birth" value="<?php echo $row['date_of_birth'];?>" required>
                        </p>

                        <p class="column">
                            <label for="contact_number">Contact Number: </label><br>
                            <input type="text" id="contact_number" name="contact_number" required pattern="[+0-9]*" title="Only numbers and + are allowed" maxlength=13  value="<?php echo $row['contact_number'];?>">
                        </p>
                    
                    </div>

                    <div class="column">
                        <div class="row">
                            <p class="column">
                                <label for="job_title">Job Title: </label><br>
                                <select id="job_title" name="job_title" required <?php if ($row['user_id'] == $_SESSION['user_id']){echo "disabled";}; ?>>
                                    <option value="">Select</option>
                                    <option <?php if ($row['job_title'] == "Pharmacist"){echo "selected";};?>>Pharmacist</option>
                                    <option
                                        <?php if ($row['job_title'] == "Manager"){echo "selected";};?>
                                        <?php if ($_SESSION['user_role'] == "admin"){echo "disabled";};?>
                                    >
                                        Manager
                                    </option>
                                </select>
                                <input type="hidden" name="job_title" value="<?php echo $row['job_title'];?>">
                            </p>
                            
                            <p class="column">
                                <label for="employment_date">Employment Date:</label><br>
                                <input type="date" id="employment_date" value="<?php echo $row['employment_date'];?>" name="employment_date">
                            </p>
                            
                        </div>

                        <p>
                            <label for="address">Address:</label><br>
                            <input type="text" name="address" value="<?php echo $row['address'];?>" required>
                        </p>

                        <p>
                            <label for="email">Email Address:</label><br>
                            <input type="email" name="email" value="<?php echo $row['email'];?>" required>
                        </p>
                    </div>
                    
                
                    <button name="action" value="update_employee">Update</button>
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