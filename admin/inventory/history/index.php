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
            $current_page_title = "stock history";
            include '../../components/top_nav.php';
        ?> 
    <div class="content" style="margin-top: 11%;">
<?php
            include($doc_root.'/utils/connect.php');

            $sqlGetInventoryHistory = "SELECT
                                h.remarks AS history_remarks,
                                h.date_recorded,
                                p.name AS reference_name,
                                u.username
                            FROM history h
                            INNER JOIN product p ON h.object_id=p.id
                            INNER JOIN user u ON h.user_id=u.id
                            WHERE h.object_type='product'
            ";

            $filter_str = "";
            if (isset($_GET['product_id'])){
                $product_id = $_GET['product_id'];
                $filter_str .= " AND p.id=".$product_id;
            };

            if (isset($_GET['stock_type'])){
                $stock_type = $_GET['stock_type'];
                if ($stock_type == 'in'){
                    $filter_str .= " AND h.remarks LIKE '%Add%'";
                } else if ($stock_type == 'out'){
                    $filter_str .= " AND (h.remarks LIKE '%Sold%') OR (h.remarks LIKE '%Disposed%')";
                };
            };
            
            $offset = 0;
            if (isset($_GET['page_no'])){
                $page_no = $_GET['page_no'];
                if ($page_no != 1){
                    $offset = (int)$page_no * 10;
                };
            };

            $sqlGetInventoryHistory .= $filter_str ." ORDER BY h.id DESC LIMIT ".$offset.", 10";
            
            $result = mysqli_query($conn,$sqlGetInventoryHistory);
        ?>

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
                        <th>Product</th>
                        <th>Date Recorded</th>
                        <th>Recorded By</th>
                        <th>Remarks</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    while($data = mysqli_fetch_array($result)){
                        $date = new DateTime($data["date_recorded"]);

                        // Format the DateTime object to 'Y-m-d h:i A' (12-hour format with AM/PM)
                        $formattedDate = $date->format('F j, Y h:i A');
                        
                        if (strpos($data['username'], "_") !== false){
                            list($first_name, $last_name, $date_created) = explode("_", $data['username']);
                        } else {
                            $first_name = "Super";
                            $last_name = "Admin";
                        };
                        
                    ?>
                    <tr>
                        
                        <td><?php echo $data["reference_name"];?></td>
                        <td><?php echo $formattedDate;?></td>
                        <td><?php echo $first_name." ".$last_name;?></td>
                        <td><?php echo $data['history_remarks']; ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>

            </table>
        </div>
        </div>
        <script>
            window.onload = function() {
                setActivePage("nav_inventory");
            };
        </script>
    </body>
</html>