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
            $current_page_title = "list of customers";
            include '../../components/top_nav.php';
        ?>

        <?php
            include('../../../utils/connect.php');
            if (isset($_GET['query'])){
                $query = $_GET['query'];
                $sqlGetCustomers = "SELECT c.first_name, c.last_name, c.address, c.contact_number, u.email, c.id AS customer_id FROM customer c
                            LEFT JOIN user u ON c.user_id=u.id
                            WHERE (c.is_active=1 OR u.is_active=1) AND CONCAT(c.first_name, c.last_name, c.address, c.contact_number, COALESCE(u.email, '')) LIKE '%$query%'
                            ORDER BY c.id DESC";
            } else {
                $query = NULL;
                $sqlGetCustomers = "SELECT c.first_name, c.last_name, c.address, c.contact_number, u.email, c.id AS customer_id FROM customer c
                            LEFT JOIN user u ON c.user_id=u.id
                            WHERE c.is_active=1 OR u.is_active=1
                            ORDER BY c.id DESC";
            }
            
            $result = mysqli_query($conn,$sqlGetCustomers);
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
                            <a href="../edit/index.php?customer_id=<?php echo $data["customer_id"]; ?>"><i class="button-icon fas fa-pen-to-square" title="Edit"></i></a>
                            <a href="../archive/index.php?customer_id=<?php echo $data["customer_id"]; ?>"><i class="button-icon fas fa-box-archive" title="Archive"></i></a>
                            <a href="../../order/list/index.php?customer_id=<?php echo $data["customer_id"]; ?>"><i class="button-icon fas fa-cubes" title="View Orders"></i></a>
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
                setActivePage("nav_customer");
            };
        </script>
    </body>
</html>