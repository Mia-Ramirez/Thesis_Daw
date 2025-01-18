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
            $current_date = date("%Y-%m-%d");
            
            $counter_values = array();

            $sqlGetTotalSales = "(SELECT
                                    SUM(total) AS total_sales
                                FROM transaction
                                WHERE DATE_FORMAT(transaction_date, '%Y-%m-%d')=CURDATE()
            )";

            $sqlGetTotalPurchase = "(SELECT
                                    SUM((received_quantity - COALESCE(disposed_quantity, 0)) * batch_cost) AS total_product_cost
                                FROM batch
                                WHERE DATE_FORMAT(date_received, '%Y-%m-%d')=CURDATE()
            )";

            $sqlGetNewOrders = "(SELECT
                                    COUNT(*)
                                FROM customer_order
                                WHERE status IN ('placed', 'processing'))
            ";

            $sqlGetProductShortages = "(SELECT
                                    COUNT(*)
                                FROM product
                                WHERE current_quantity < maintaining_quantity
            )";

            $sqlExpiringProducts = "(SELECT
                                    COUNT(*)
                                FROM batch
                                WHERE expiration_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
            )";

            $sqlNotMovingProducts = "(SELECT
                                        COUNT(*) AS result_count
                                        FROM (SELECT 
                                            DATE_FORMAT(t.transaction_date, '%Y-%m-%d') AS formatted_date,
                                            SUM(qty) AS total_quantity
                                                FROM transaction t
                                            INNER JOIN product_line pl ON t.id=pl.transaction_id
                                            GROUP BY product_id, formatted_date
                                            HAVING total_quantity <= 10 AND formatted_date=CURDATE()
                                        ) AS subquery
            )";

            $sqlCounts = "
                SELECT 'total_sales' AS key_name, ".$sqlGetTotalSales." AS record_count
                UNION
                SELECT 'total_purchase' AS key_name, ".$sqlGetTotalPurchase." AS record_count
                UNION
                SELECT 'new_orders' AS key_name, ".$sqlGetNewOrders." AS record_count
                UNION
                SELECT 'product_shortages' AS key_name, ".$sqlGetProductShortages." AS record_count
                UNION
                SELECT 'expiring_products' AS key_name, ".$sqlExpiringProducts." AS record_count
                UNION
                SELECT 'not_moving_products' AS key_name, ".$sqlNotMovingProducts." AS record_count
            ";
            
            $result = mysqli_query($conn,$sqlCounts);
            while($data = mysqli_fetch_array($result)){
                $counter_values[$data['key_name']] = $data['record_count'];
            }
        ?>

        <div class="pot">
	        <div class="main">
				<h2 style="text-align: center;">Today's Report</h2>
				<br>
				<div class="grid">
					<div class="boxs"><h4>Total Sales</h4>
					    <h3><?php echo $counter_values['total_sales']; ?></h3>
					</div>
					<div class="boxs"><h4>Total Purchase</h4>
					    <h3><?php echo $counter_values['total_purchase']; ?></h3>
					</div>
				</div>
			</div>
			
            <div class="card" onclick="redirectToPage('order/list','status=new_order')">
                <h2><?php echo $counter_values['new_orders']; ?></h2>
                <p>New Orders</p>
            </div>

            <div class="card" onclick="redirectToPage('inventory/stock','is_low=true')">
                <h2><?php echo $counter_values['product_shortages']; ?></h2>
                <p>Product Shortage</p>
            </div>

            <div class="card" onclick="redirectToPage('inventory/stock/batch','soon_to_expire=true')">
                <h2><?php echo $counter_values['expiring_products']; ?></h2>
                <p>Soon to Expire</p>
            </div>

            <div class="card" onclick="redirectToPage('sales_report/slow_moving','get_today=true')">
                <h2><?php echo $counter_values['not_moving_products']; ?></h2>
                <p>Not Moving Products</p>
            </div>
        </div>
        
        <script>
            window.onload = function() {
                setActivePage("nav_dashboard");
            };

            function redirectToPage(page, param) {
                if (param == ''){
                    window.location.href = '../'+page+'/index.php';
                } else {
                    window.location.href = '../'+page+'/index.php?'+param;
                };
                
            };
        </script>
    </body>
</html>