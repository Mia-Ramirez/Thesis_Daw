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
            $current_page_title = "capital and revenue";
            include '../../components/top_nav.php';
        ?> 
  <div class="space" style="margin-top: 11%;"></div>
        <?php
            include($doc_root.'/utils/connect.php');

            $sqlGetDates="SELECT DISTINCT line_date, DATE_FORMAT(line_date, '%M %d, %Y') AS formatted_date
                    FROM (
                        SELECT DATE_FORMAT(date_received, '%Y-%m-%d') AS line_date
                        FROM batch
                        GROUP BY DATE_FORMAT(date_received, '%Y-%m-%d')

                        UNION

                        SELECT DATE_FORMAT(transaction_date, '%Y-%m-%d') AS line_date
                        FROM transaction
                        GROUP BY DATE_FORMAT(transaction_date, '%Y-%m-%d')
                    ) AS combined_dates";

            $filter_date_str="";
            $query = NULL;
        
            if (isset($_GET['query'])){
                $query = $_GET['query'];
                $filter_date_str=" HAVING formatted_date LIKE '%$query%'";
            };

            $sqlGetDates .= $filter_date_str." ORDER BY line_date DESC LIMIT 0, 10";
            $date_results = mysqli_query($conn,$sqlGetDates);

            $table_data = array();

            while($date_result = mysqli_fetch_array($date_results)){
                $sqlGetRevenueData="SELECT
                                        DATE_FORMAT(transaction_date, '%Y-%m-%d') AS formatted_ref_date,
                                        DATE_FORMAT(transaction_date, '%M %d, %Y') AS readable_ref_date,
                                        pl.qty AS line_qty,
                                        pl.order_id,
                                        pl.line_price,
                                        pl.product_id,
                                        p.name AS product_name,
                                        t.selected_discount,
                                        p.applicable_discounts
                                    FROM product_line pl
                                    INNER JOIN transaction t ON pl.transaction_id=t.id
                                    INNER JOIN product p ON pl.product_id=p.id
                                    WHERE pl.line_type='transaction'
                                    HAVING formatted_ref_date='".$date_result['line_date']."'
                                ";

                $revenue_results = mysqli_query($conn,$sqlGetRevenueData);
                while($revenue_result = mysqli_fetch_array($revenue_results)){
                    
                    $date_key = $revenue_result['formatted_ref_date']."_".$revenue_result['product_id'];
                    $selected_discount = $revenue_result['selected_discount'];
                        
                    $discount_rate = 0;
                    if ($selected_discount && ($selected_discount == $revenue_result['applicable_discounts'] || $revenue_result['applicable_discounts'] == 'Both')){
                        $discount_rate = 0.2;
                    };

                    $line_price = $revenue_result['line_price'] * (1 - $discount_rate);
                    if (array_key_exists($date_key, $table_data)) {
                        $web = $table_data[$date_key]['revenue']['web'];
                        $in_house = $table_data[$date_key]['revenue']['in_house'];
                        
                        if (is_null($revenue_result['order_id'])){
                            $in_house += intval($revenue_result['line_qty']) * $line_price ;
                        } else {
                            $web += intval($revenue_result['line_qty']) * $line_price ;
                        };

                        $table_data[$date_key]['revenue']['web'] = $web;
                        $table_data[$date_key]['revenue']['in_house'] = $in_house;

                    } else {
                        $web = 0;
                        $in_house = 0;
                        
                        if (is_null($revenue_result['order_id'])){
                            $in_house = intval($revenue_result['line_qty']) * $line_price;
                        } else {
                            $web = intval($revenue_result['line_qty']) * $line_price;
                        };

                        $table_data[$date_key] = [
                            'product_name' => $revenue_result['product_name'],
                            'line_date' => $revenue_result['readable_ref_date'],
                            'capital' => 0,
                            'revenue' => [
                                'web' => $web,
                                'in_house' => $in_house
                            ],
                            'remarks' => NULL
                        ];
                    };
                };
                

                $sqlGetCapitalData="SELECT
                                        DATE_FORMAT(date_received, '%Y-%m-%d') AS formatted_ref_date,
                                        DATE_FORMAT(date_received, '%M %d, %Y') AS readable_ref_date,
                                        received_quantity,
                                        disposed_quantity,
                                        b.batch_cost AS line_price,
                                        b.product_id,
                                        p.name AS product_name
                                    FROM batch b
                                    INNER JOIN product p ON b.product_id=p.id
                                    HAVING formatted_ref_date='".$date_result['line_date']."'
                                ";

                $capital_results = mysqli_query($conn,$sqlGetCapitalData);
                while($capital_result = mysqli_fetch_array($capital_results)){
                    
                    $date_key = $capital_result['formatted_ref_date']."_".$capital_result['product_id'];
                    $remarks = NULL;
                    if (is_null($capital_result['disposed_quantity'])){
                        $line_qty = $capital_result['received_quantity'];
                    } else {
                        $line_qty = $capital_result['received_quantity'] - $capital_result['disposed_quantity'];
                        $remarks = 'Values changed due to returning/re-imbursement of products to supplier';
                    };

                    if (array_key_exists($date_key, $table_data)) {
                        $capital = $table_data[$date_key]['capital'];
                        $capital += intval($line_qty) * $capital_result['line_price'];
                        $table_data[$date_key]['capital'] = $capital;

                    } else {
                        $capital = intval($line_qty) * $capital_result['line_price'];
                        $table_data[$date_key] = [
                            'product_name' => $capital_result['product_name'],
                            'line_date' => $capital_result['readable_ref_date'],
                            'capital' => $capital,
                            'revenue' => [
                                'web' => 0,
                                'in_house' => 0
                            ],
                            'remarks' => $remarks
                        ];
                    };
                    
                };
            };

        ?>

        <!-- <div class="search">
            <form method="GET" action="">
                <input type="text" value="<?php //echo $query; ?>" name="query" placeholder="Search anything...">
                <button class="btns" type="submit">Search</button>
            </form>
        </div> -->

        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th class="center-text" rowspan="2">Product</th>
                        <th class="center-text" rowspan="2">Date</th>
                        <th class="center-text" rowspan="2">Total Sales</th>
                        <th class="center-text" rowspan="2">Capital</th>
                        <th class="center-text" colspan="2">Revenue</th>
                    </tr>
                    <tr>
                        <th class="center-text">Web</th>
                        <th class="center-text">Store</th>
                    </tr>
                </thead>

                <tbody>
                <?php
                    foreach ($table_data as $data) {
                    ?>
                    <tr>
                        <td><?php echo $data["product_name"];?></td>
                        <td><?php echo $data["line_date"];?></td>
                        <td>
                            <?php
                                $total_sales = ($data["revenue"]["web"] + $data["revenue"]["in_house"]) - $data["capital"];
                                echo $total_sales;
                                if (!is_null($data["remarks"])){
                                    ?>
                                    <span class="tooltip">*
                                        <span class="tooltip-text"><?php echo $data["remarks"]; ?></span>
                                    </span>
                                    <?php
                                };
                            ?>
                        </td>
                        <td>
                            <?php
                                echo $data["capital"];
                                if (!is_null($data["remarks"])){
                                    ?>
                                    <span class="tooltip">*
                                        <span class="tooltip-text"><?php echo $data["remarks"]; ?></span>
                                    </span>
                                    <?php
                                };
                            ?>
                        </td>
                        <td><?php echo $data["revenue"]["web"];?></td>
                        <td><?php echo $data["revenue"]["in_house"];?></td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>

            </table>
        </div>

        <script>
            window.onload = function() {
                setActivePage("nav_sales_report");
            };
        </script>
    </body>
</html>