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
        <link rel="stylesheet" type="text/css" href="../../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="<?php echo $base_url;?>assets/scripts/common_fx.js"></script>
        <?php include '../../components/title.php'; ?>
    </head>

    <body>
        <?php include '../../components/unauth_redirection.php'; ?>
        
        <?php include '../../components/side_nav.php'; ?>
        
        <?php
            $current_page_title = "stocks";
            include '../../components/top_nav.php';
        ?> 
        
        <div class="content" style="margin-top: 11%;">

        <?php
            include($doc_root.'/utils/connect.php');
            
            $sqlGetStockProducts = "SELECT
                                        p.id,
                                        p.name,
                                        p.current_quantity,
                                        p.maintaining_quantity,
                                        COUNT(b.id) AS batch_count
                                    FROM product p
                                    LEFT JOIN batch b ON p.id=b.product_id
                                    ";

            $stock_type = 'both';
            $filter_str = "";
            if (isset($_GET['is_low'])){
                $stock_type = 'in';
                $filter_str=" WHERE current_quantity BETWEEN 1 AND maintaining_quantity";
            };

            if (isset($_GET['is_zero'])){
                $stock_type = 'in';
                $filter_str=" WHERE current_quantity=0";
            };

            if (isset($_GET['stock_type'])){
                $stock_type = $_GET['stock_type'];
            };
            
            $sqlGetStockProducts .= $filter_str." GROUP BY p.id ORDER BY current_quantity ASC, maintaining_quantity ASC";

            $result = mysqli_query($conn,$sqlGetStockProducts);
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
                        <th>Current Number of Stock</th>
                        <?php
                            if (in_array($stock_type, ['in', 'both'])){
                                echo '<th>Maintaining Quantity</th>';
                            };
                            if (in_array($stock_type, ['out', 'both'])){
                                echo '<th>Number of Batches</th>';
                            };
                        ?>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    while($data = mysqli_fetch_array($result)){
                    ?>
                    <tr>
                        
                        <td><?php echo $data["name"];?></td>
                        <td><?php echo $data["current_quantity"];?></td>
                        <?php
                            if (in_array($stock_type, ['in', 'both'])){
                                echo '<td>'.$data["maintaining_quantity"].'</td>';
                            };
                            if (in_array($stock_type, ['out', 'both'])){
                                echo '<td>'.$data["batch_count"].'</td>';
                            };
                        ?>
                        <td>
                            <?php
                                if (in_array($stock_type, ['in', 'both'])){
                                    echo '<a href="./add/index.php?product_id='.$data['id'].'"><i class="button-icon fas fa-plus" title="Stock In"></i></a>';
                                };
                                if (in_array($stock_type, ['out', 'both'])){
                                    echo '<a href="./batch/index.php?product_id='.$data['id'].'"><i class="button-icon fas fa-minus" title="Stock Out"></i></a>';
                                };
                            ?>
                            
                            <a href="../history/index.php?stock_type=<?php echo $stock_type; ?>&product_id=<?php echo $data["id"]; ?>"><i class="button-icon fas fa-clock-rotate-left" title="View History"></i></a>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>

            </table>
        </div>
        </div>
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