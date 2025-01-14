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
        <link rel="stylesheet" type="text/css" href="../../../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="<?php echo $base_url;?>assets/scripts/common_fx.js"></script>
        <?php include '../../../components/title.php'; ?>
    </head>

    <body>
        <?php include '../../../components/unauth_redirection.php'; ?>
        
        <?php include '../../../components/side_nav.php'; ?>
        
        <?php
            $current_page_title = "stock batch";
            include '../../../components/top_nav.php';
        ?> 
        
        <?php
            include($doc_root.'/utils/connect.php');
            
            $sqlGetProductBatches = "SELECT
                                        b.id AS batch_id,
                                        p.id AS product_id,
                                        reference_number,
                                        expiration_date,
                                        received_quantity,
                                        p.name AS product_name,
                                        date_disposed
                                    FROM batch b
                                    INNER JOIN product p ON b.product_id=p.id";

            $filter_str = "";
            if (isset($_GET['product_id'])){
                $product_id = $_GET['product_id'];
                $filter_str = " WHERE p.id=".$product_id;
            };

            $offset = '0';
            if (isset($_GET['page_no'])){
                $page_no = $_GET['page_no'];
                if ($page_no == 1){
                    $offset = 0;
                } else {
                    $offset = (int)$_GET['page_no'] * 10;
                };
            };

            $sqlGetProductBatches .= $filter_str ." ORDER BY expiration_date ASC LIMIT ".$offset.", 10";
            $result = mysqli_query($conn,$sqlGetProductBatches);

            $batches = array();
        ?>

        <?php include './utils/disposeStockModal.php'; ?>

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
                        <th>Batch Reference Number</th>
                        <th>Product</th>
                        <th>Expiration Date</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $index = 0;
                    while($data = mysqli_fetch_array($result)){
                        $date = new DateTime($data["expiration_date"]);

                        // Format the DateTime object to 'Y-m-d h:i A' (12-hour format with AM/PM)
                        $formattedDate = $date->format('F j, Y');

                        $dictionary = [
                            "productID" => $data['product_id'],
                            "batchID" => $data["batch_id"],
                            "referenceNumber" => $data["reference_number"],
                            "maxQuantity" => $data["received_quantity"]
                        ];
                        array_push($batches, $dictionary);
                    ?>
                    <tr>
                        
                        <td><?php echo $data["reference_number"];?></td>
                        <td><?php echo $data["product_name"];?></td>
                        <td><?php echo $formattedDate;?></td>
                        <td><?php echo $data["received_quantity"];?></td>
                        <td>
                            <u class="u_action" onclick="redirectToBatchDetailsPage(<?php echo $data['batch_id']; ?>)"><i class="button-icon fas fa-circle-info" title="View Details"></i></u>
                            <?php if (is_null($data['date_disposed'])){echo "<u class='u_action' onclick=\"showDisposeStockModal(".$index.")\"><i class=\"button-icon fas fa-minus\" title=\"Dispose Stock\"></i></u>";} ?>
                        </td>
                    </tr>
                    <?php
                        $index++;
                    }
                    ?>
                </tbody>

            </table>
        </div>
        <?php
            echo "
                <script>
                    let batches = ".json_encode($batches).";
                </script>
            ";
        ?>
        <script src="script.js"></script>
        <script>
            function redirectToBatchDetailsPage(batch_id) {
                window.location.href = './details/index.php?batch_id='+batch_id;
            };
            
            window.onload = function() {
                setActivePage("nav_inventory");
            };
        </script>
    </body>
</html>