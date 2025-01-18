<?php
    session_start();
    $base_url = $_SESSION["BASE_URL"];
    $doc_root = $_SESSION["DOC_ROOT"];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHARMANEST ESSENTIAL</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>assets/styles/bootstrap.css">
        <link rel="stylesheet" href="../styles.css">
        <link rel="stylesheet" href="styles.css">
        <script src="<?php echo $base_url;?>assets/scripts/common_fx.js"></script>
    </head>
    <body class="body">
        <?php include '../components/unauth_redirection.php'; ?>
        
        <?php include '../components/navbar.php'; ?>  
        
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

        <?php
            include($doc_root.'/utils/connect.php');
            
            $user_id = $_SESSION['user_id'];

            $sqlGetCustomerOrders = "SELECT
                                        co.id AS order_id,
                                        customer_id,
                                        date_ordered,
                                        status,
                                        reference_number
                                    FROM customer_order co
                                    INNER JOIN customer c ON co.customer_id=c.id
                                    WHERE c.user_id=$user_id";
            
            $offset = 0;
            if (isset($_GET['page_no'])){
                $page_no = $_GET['page_no'];
                if ($page_no != 1){
                    $offset = (int)$page_no * 10;
                };
            };
            
            $sqlGetCustomerOrders .= " ORDER BY order_id DESC LIMIT ".$offset.", 10";
            
            $orders = mysqli_query($conn,$sqlGetCustomerOrders);
            if ($orders->num_rows == 0){
                $_SESSION["message_string"] = "You didn't order yet";
                $_SESSION["message_class"] = "danger";
                header("Location:../home/index.php");
                exit;
            };
        ?>
        
        <!-- Cancel Order Modal -->
        <?php include './utils/cancelOrderModal.php'; ?>

        <div class="card">
            <h2 style="text-align: center">
                Your Orders
            </h2>
            <table id="orderTable">
                <thead>
                    <tr>
                        <th>Order Reference #</th>
                        <th>Date Ordered</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                while($data = mysqli_fetch_array($orders)){
                    if (isset($_SESSION['customer_id']) == false){
                        $_SESSION['customer_id'] = $data['customer_id'];
                    };

                    $date = new DateTime($data["date_ordered"]);

                    // Format the DateTime object to 'Y-m-d h:i A' (12-hour format with AM/PM)
                    $formattedDate = $date->format('F j, Y h:i A');
                ?>
                    <tr>
                        <td><?php echo $data['reference_number']; ?></td>
                        <td><?php echo $formattedDate; ?></td>
                        <td><?php echo ucwords(str_replace("_", " ", $data['status'])); ?></td>
                        <td>
                            <u class="u_action" onclick="redirectToOrderDetailsPage(<?php echo $data['order_id']; ?>)">View Details</u>
                            <?php if (!in_array($data['status'], ['cancelled','picked_up'])){echo "| <u class='u_action' onclick=\"showCancelOrderModal(".$data['order_id'].", '".$data['reference_number']."')\">Cancel Order</u>";} ?>
                        </td>
                    </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
        </div>

        <script src="../script.js"></script>

        <script src="script.js"></script>

        <script>
            window.onload = function() {
                setActivePage("nav_menu");
            };

            function redirectToOrderDetailsPage(order_id) {
                window.location.href = './details/index.php?order_id='+order_id;
            };

        </script>
    </body>
</html>