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
        <link rel="stylesheet" type="text/css" href="../../../../styles.css">
        <link rel="stylesheet" type="text/css" href="../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="<?php echo $base_url;?>assets/scripts/common_fx.js"></script>
        <?php include '../../../../components/title.php'; ?>
    </head>

    <body>
        <?php include '../../../../components/unauth_redirection.php'; ?>
        
        <?php include '../../../../components/side_nav.php'; ?>

        <?php
            $current_page_title = "batch details";
            include '../../../../components/top_nav.php';
        ?>

        <?php
            include($doc_root.'/utils/connect.php');
            if (isset($_GET['batch_id'])) {
                $batch_id = $_GET['batch_id'];
                $sqlGetBatch = "SELECT
                                    p.name AS product_name,
                                    p.id AS product_id,
                                    reference_number,
                                    date_received,
                                    received_quantity,
                                    expiration_date,
                                    s.name AS supplier_name,
                                    u.username,
                                    date_disposed,
                                    disposed_quantity,
                                    batch_cost,
                                    batch_selling_price
                                FROM batch b
                                INNER JOIN product p ON b.product_id=p.id
                                INNER JOIN supplier s ON b.supplier_id=s.id
                                INNER JOIN employee e ON b.employee_id=e.id
                                INNER JOIN user u ON e.user_id=u.id
                                WHERE b.id=$batch_id";

                $batch_result = mysqli_query($conn,$sqlGetBatch);
                if ($batch_result->num_rows == 0){
                    header("Location:../../../../../page/404.php");
                };

                $row = mysqli_fetch_array($batch_result);

                $batches = array();
                $dictionary = [
                    "batchID" => $batch_id,
                    "productID" => $row['product_id'],
                    "referenceNumber" => $row["reference_number"],
                    "maxQuantity" => $row["received_quantity"]
                ];
                array_push($batches, $dictionary);
                echo "
                    <script>
                        let batches = ".json_encode($batches).";
                    </script>
                ";

            } else {
                header("Location:../../index.php");
            };
        ?>

        <!-- Add Dispose Stock Modal -->
        <?php include '../utils/disposeStockModal.php'; ?>
        
        <div class="main">
            <div class="row">

                <div class="column">
                    <p>
                        Batch Reference Number: <b><?php echo $row['reference_number']; ?></b>
                    </p>
                    <p>
                        Product Name: <b><?php echo $row['product_name']; ?></b>
                    </p>
                    <p>
                        Supplier Name: <b><?php echo $row['supplier_name']; ?></b>
                    </p>
                    <p>
                        Wholesale Price: <b>&#8369 <?php echo $row['batch_cost']; ?></b>
                    </p>
                    <p>
                        Selling Price: <b>&#8369 <?php echo $row['batch_selling_price']; ?></b>
                    </p>
                    <p>
                        Expiration Date: <b><?php
                            $date = new DateTime($row["expiration_date"]);

                            // Format the DateTime object to 'Y-m-d h:i A' (12-hour format with AM/PM)
                            $formattedDate = $date->format('F j, Y');
                            echo $formattedDate;
                        ?></b>
                    </p>
                </div>

                <div class="column">
                    <p>
                        Quantity: <b><?php echo $row['received_quantity']; ?></b>
                    </p>
                    
                    <p>
                        Date Received: <b><?php
                            $date = new DateTime($row["date_received"]);

                            // Format the DateTime object to 'Y-m-d h:i A' (12-hour format with AM/PM)
                            $formattedDate = $date->format('F j, Y');
                            echo $formattedDate;
                        ?></b>
                    </p>
                    <p>
                        Disposed Quantity: <b><?php echo $row['disposed_quantity']; ?></b>
                    </p>
                    <p>
                        Date Disposed: <b><?php
                            if (!is_null($row["date_disposed"])){
                                $date = new DateTime($row["date_disposed"]);

                                // Format the DateTime object to 'Y-m-d' (12-hour format with AM/PM)
                                $formattedDate = $date->format('F j, Y');
                                echo $formattedDate;
                            };
                        ?></b>
                    </p>
                    <p>
                        Added By: <b><?php
                            if (strpos($row['username'], "_") !== false){
                                list($first_name, $last_name, $date_created) = explode("_", $row['username']);
                            } else {
                                $first_name = "Super";
                                $last_name = "Admin";
                            };
                            echo $first_name." ".$last_name;
                        ?></b>
                    </p>
                </div>
                
                <center>
                <button class="action_button<?php if (!is_null($row['disposed_quantity'])){echo ' disabled';} ?>" type="button" name="action" value="dispose_stock" id="dispose_stock" <?php if (!is_null($row['disposed_quantity'])){echo 'disabled';} ?> onclick="showDisposeStockModal(0)">Dispose Stock</button>
                </center>
            </div>
        </div>
        
        <script src="../script.js"></script>

        <script>
            // function redirectToPage(page) {
            //     window.location.href = './'+page+'/index.php';
            // };
            
            window.onload = function() {
                setActivePage("nav_inventory");
            };
        </script>
    </body>
</html>