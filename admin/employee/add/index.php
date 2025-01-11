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
            $current_page_title = "add employee";
            include '../../components/top_nav.php';
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
                            <input type="text" name="first_name" required value="<?php if(isset($_SESSION["first_name"])){echo $_SESSION["first_name"];unset($_SESSION["first_name"]);}?>">
                        </p>

                        <p>
                            <label for="last_name">Last Name:</label><br>
                            <input type="text" name="last_name" required value="<?php if(isset($_SESSION["last_name"])){echo $_SESSION["last_name"];unset($_SESSION["last_name"]);}?>">
                        </p>

                        <p class="column">
                            <label for="date_of_birth">Date of Birth:</label><br>
                            <input type="date" name="date_of_birth" required value="<?php if(isset($_SESSION["date_of_birth"])){echo $_SESSION["date_of_birth"];unset($_SESSION["date_of_birth"]);}?>">
                        </p>

                        <p class="column">
                            <label for="contact_number">Contact Number: </label><br>
                            <input type="text" id="contact_number" name="contact_number" required pattern="[+0-9]*" title="Only numbers and + are allowed" maxlength=13 value="<?php if(isset($_SESSION["contact_number"])){echo $_SESSION["contact_number"];unset($_SESSION["contact_number"]);}?>">
                        </p>
                    
                    </div>

                    <div class="column">

                        <div class="row">
                            <p class="column">
                                <label for="job_title">Job Title: </label><br>
                                <select id="job_title" name="job_title" required>
                                    <option value="">Select</option>
                                    <option>Pharmacist</option>
                                    <?php
                                        if ($_SESSION["user_role"] == "super admin"){
                                            echo '<option>Manager</option>';
                                        };
                                    ?>
                                </select>
                            </p>
                            
                            <p class="column">
                                <label for="employment_date">Employment Date:</label><br>
                                <input type="date" id="employment_date" name="employment_date" value="<?php if(isset($_SESSION["employment_date"])){echo $_SESSION["employment_date"];unset($_SESSION["employment_date"]);}?>">
                            </p>
                            
                        </div>

                        <p>
                            <label for="address">Address:</label><br>
                            <input type="text" name="address" required value="<?php if(isset($_SESSION["address"])){echo $_SESSION["address"];unset($_SESSION["address"]);}?>">
                        </p>

                        <p>
                            <label for="email">Email Address:</label><br>
                            <input type="email" name="email" required value="<?php if(isset($_SESSION["email"])){echo $_SESSION["email"];unset($_SESSION["email"]);}?>">
                        </p>
                    </div>
                    
                
                    <button name="action" value="add_employee">Add</button>
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