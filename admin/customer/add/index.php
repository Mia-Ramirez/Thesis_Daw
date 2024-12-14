<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
            $current_page_title = "add customer";
            include '../../components/unauth_redirection.php';
        ?>
        
        <?php include '../../components/side_nav.php'; ?>
                
        <?php include '../../components/top_nav.php'; ?>  

        <div class="main">
            <div class="row">
                <form>
                    <div class="column">
                        <p>
                            <label for="first_name">First Name:</label><br>
                            <input type="text" name="first_name">
                        </p>

                        <p>
                            <label for="last_name">Last Name:</label><br>
                            <input type="text" name="last_name">
                        </p>

                        <div class="row">
                            <p class="column">
                                <label for="date_of_birth">Date of Birth:</label><br>
                                <input type="date" name="date_of_birth">
                            </p>
                            <p class="column">
                                <label for="sex">Sex: </label><br>
                                <select id="sex" name="sex">
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
                            <input type="text" name="contact_number">
                        </p>

                        <p>
                            <label for="address">Address:</label><br>
                            <input type="text" name="address">
                        </p>

                        <p>
                            <label for="email">Email Address:</label><br>
                            <input type="email" name="email">
                        </p>
                    </div>
                    
                
                <input type="submit" name="add" value="Submit">
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