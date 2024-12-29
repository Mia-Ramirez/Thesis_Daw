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
            $current_page_title = "archived employees";
            include '../../components/unauth_redirection.php';
        ?>
        <?php include '../../components/side_nav.php'; ?>
                
        <?php include '../../components/top_nav.php'; ?>  

        <?php
            include('../../../utils/connect.php');
            if (isset($_GET['query'])){
                $query = $_GET['query'];
                $sqlGetEmployees = "SELECT e.first_name, e.last_name, e.address, e.contact_number, u.email, e.id AS employee_id FROM employee e
                            LEFT JOIN user u ON e.user_id=u.id
                            WHERE u.is_active=0 AND CONCAT(e.first_name, e.last_name, e.address, e.contact_number, COALESCE(u.email, '')) LIKE '%$query%'
                            ORDER BY e.id DESC";
            } else {
                $query = NULL;
                $sqlGetEmployees = "SELECT e.first_name, e.last_name, e.address, e.contact_number, u.email, e.id AS employee_id FROM employee e
                            LEFT JOIN user u ON e.user_id=u.id
                            WHERE u.is_active=0
                            ORDER BY e.id DESC";
            }
            
            $result = mysqli_query($conn, $sqlGetEmployees);
        ?>

        <div class="search">
            <form method="GET" action="">
            <input type="text" value="<?php echo $query; ?>" name="query" placeholder="Search anything...">
                <button class="btns" type="submit">Search</button>
            </form>
        </div>

        <div class="table">
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
            <table>
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Address</th>
                        <th>Contact Number</th>
                        <th>Email Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    while($data = mysqli_fetch_array($result)){
                    ?>
                    <tr>
                        
                        <td><?php echo $data["first_name"];?></td>
                        <td><?php echo $data["last_name"];?></td>
                        <td><?php echo $data["address"];?></td>
                        <td><?php echo $data["contact_number"];?></td>
                        <td><?php echo $data["email"];?></td>
                        <td>
                            <a href="./process.php?action=recover&employee_id=<?php echo $data["employee_id"]; ?>">Recover</a>
                            <!-- | <a href="./process.php?action=delete&employee_id=<?php?>">Delete</a> -->
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>

            </table>
        </div>

        <script>
            window.onload = function() {
                setActivePage("nav_employee");
            };
        </script>
    </body>
</html>