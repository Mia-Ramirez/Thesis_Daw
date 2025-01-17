<?php
    session_start();
    $base_url = $_SESSION["BASE_URL"];
    $doc_root = $_SESSION["DOC_ROOT"];
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="../../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="<?php echo $base_url;?>assets/scripts/common_fx.js"></script>
        <?php include '../../components/title.php'; ?>
    </head>

    <body>
        <?php include '../../components/unauth_redirection.php'; ?>

        <?php include '../../components/side_nav.php'; ?>

        <?php
            $current_page_title = "list of orders";
            include '../../components/top_nav.php';
        ?>

        <?php
            include($doc_root.'/utils/connect.php');

            $sqlGetOrders = "SELECT
                                co.id AS order_id,
                                date_ordered,
                                status,
                                reference_number,
                                c.first_name,
                                c.last_name
                            FROM customer_order co
                            INNER JOIN customer c ON co.customer_id=c.id
            ";

            $filter_str = "";
            $query = NULL;
            
            if (isset($_GET['query'])){
                $query = $_GET['query'];
                $filter_str = " WHERE CONCAT(c.first_name, c.last_name, co.reference_number, co.status, co.date_ordered) LIKE '%$query%'";
            };

            if (isset($_GET['customer_id'])){
                $customer_id = $_GET['customer_id'];
                if (strpos($filter_str, "WHERE") != false){
                    $filter_str .= " AND co.customer_id=".$customer_id;
                } else {
                    $filter_str .= " WHERE co.customer_id=".$customer_id;
                };
            };

            if (isset($_GET['status'])){
                $status = $_GET['status'];
                if ($status == 'new_order'){
                    $status = " IN ('placed','preparing')";
                } else {
                    $status = " IN ('".$status."')";
                };

                if (strpos($filter_str, "WHERE") != false){
                    $filter_str .= " AND co.status".$status;
                } else {
                    $filter_str .= " WHERE co.status".$status;
                };
            };

            $sqlGetOrders .= $filter_str;
            
            $result = mysqli_query($conn,$sqlGetOrders);
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
                        <th>Order Reference Number</th>
                        <th>Date Ordered</th>
                        <th>Customer Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    while($data = mysqli_fetch_array($result)){
                        $date = new DateTime($data["date_ordered"]);

                        // Format the DateTime object to 'Y-m-d h:i A' (12-hour format with AM/PM)
                        $formattedDate = $date->format('F j, Y h:i A');
                    ?>
                    <tr>
                        
                        <td><?php echo $data["reference_number"];?></td>
                        <td><?php echo $formattedDate;?></td>
                        <td><?php echo $data["first_name"]." ".$data["last_name"];?></td>
                        <td><?php echo ucwords(str_replace("_", " ", $data['status'])); ?></td>
                        <td>
                            <a href="../details/index.php?order_id=<?php echo $data["order_id"]; ?>"><i class="button-icon fas fa-circle-info" title="View Details"></i></a>
                            <a href="../history/index.php?order_id=<?php echo $data["order_id"]; ?>"><i class="button-icon fas fa-clock-rotate-left" title="View History"></i></a>
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
                setActivePage("nav_order");
            };
        </script>
    </body>
</html>