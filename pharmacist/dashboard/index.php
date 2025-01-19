<?php
    session_start();
    $base_url = $_SESSION["BASE_URL"];
    $doc_root = $_SESSION["DOC_ROOT"];
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="<?php echo $base_url;?>assets/scripts/common_fx.js"></script>
        <?php include '../components/title.php'; ?>
    </head>

    <body>
        <?php include '../components/unauth_redirection.php'; ?>
        
        <?php include '../components/side_nav.php'; ?>
        
        <?php
            $current_page_title = "dashboard";
            include '../components/top_nav.php';
        ?>

        <?php
            include($doc_root.'/utils/connect.php');
            
            $sqlGetTotalSales = "(SELECT
                                    SUM(total) AS total_sales
                                FROM transaction
                                WHERE DATE_FORMAT(transaction_date, '%Y-%m-%d')=CURDATE()
            )";

            $sqlGetProcessedOrders = "(SELECT
                                        COUNT(*)
                                    FROM customer_order co
                                    WHERE status NOT IN ('placed', 'cancelled')
                                    AND id IN (SELECT object_id FROM history
                                                WHERE object_type='order'
                                                AND DATE_FORMAT(date_recorded, '%Y-%m-%d')=CURDATE()
                                    )
            )";

            $sqlGetProductShortages = "(SELECT
                                    COUNT(*)
                                FROM product
                                WHERE current_quantity < maintaining_quantity
            )";

            $sqlCounts = "
                SELECT 'total_sales' AS key_name, COALESCE(".$sqlGetTotalSales.", 0) AS record_count
                UNION
                SELECT 'processed_orders' AS key_name, COALESCE(".$sqlGetProcessedOrders.", 0) AS record_count
                UNION
                SELECT 'product_shortages' AS key_name, COALESCE(".$sqlGetProductShortages.", 0) AS record_count
            ";

            $counter_values = array();
            $result = mysqli_query($conn,$sqlCounts);
            while($data = mysqli_fetch_array($result)){
                $counter_values[$data['key_name']] = $data['record_count'];
            };

            $sqlRecentActivity = "SELECT 
                                    history.remarks,
                                    DATE_FORMAT(history.date_recorded, '%Y-%m-%d %H:%m') AS formatted_date,
                                    CASE
                                        WHEN history.object_type = 'transaction' THEN 'POS'
                                        WHEN history.object_type = 'product' THEN product.name
                                        WHEN history.object_type = 'order' THEN CONCAT('Order ', customer_order.reference_number)
                                        ELSE NULL
                                    END AS related_ref
                                FROM 
                                    history
                                LEFT JOIN 
                                    transaction ON history.object_type = 'transaction' AND history.object_id = transaction.id
                                LEFT JOIN 
                                    product ON history.object_type = 'product' AND history.object_id = product.id
                                LEFT JOIN 
                                    customer_order ON history.object_type = 'order' AND history.object_id = customer_order.id
                                ORDER BY date_recorded DESC
                                LIMIT 0, 5
            ";
            $result = mysqli_query($conn,$sqlRecentActivity);

        ?>

        <div class="container">
        <div class="card-dashboard">
            <h3>Quick Overview</h3>
            <ul>
                <li>Total Sales Today: &#8369 <?php echo $counter_values['total_sales']; ?></li>
                <li>Orders Processed Today: <?php echo $counter_values['processed_orders']; ?></li>
                <li>Inventory Alerts: <?php echo $counter_values['product_shortages']; ?> product(s) low in stock</li>
            </ul>
            <h3>Recent Activity</h3>
            <ul>
                <?php
                    while($data = mysqli_fetch_array($result)){
                ?>
                    <li><?php
                            $formatted_date = date('Y-m-d h:i A', strtotime($data['formatted_date']));
                            echo $formatted_date.": ".$data['related_ref'].' - '.$data['remarks']; ?></li>
                <?php
                    }
                ?>
            </ul>
        </div>
        </div>

        <script>
            window.onload = function() {
                setActivePage("nav_dashboard");
            };
        </script>
    </body>
</html>