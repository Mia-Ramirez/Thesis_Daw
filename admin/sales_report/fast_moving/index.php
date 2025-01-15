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
            $current_page_title = "fast moving products";
            include '../../components/top_nav.php';
        ?> 

        <?php
            include($doc_root.'/utils/connect.php');

            $sqlGetMovingProducts = "SELECT
                                            product_id,
                                            p.name AS product_name,
                                            DATE_FORMAT(transaction_date, '%M %d, %Y') AS formatted_date,
                                            SUM(qty) AS total_quantity
                                        FROM transaction t
                                        INNER JOIN product_line pl ON t.id=pl.transaction_id
                                        INNER JOIN product p ON pl.product_id=p.id
                                        GROUP BY product_id, formatted_date
                                        HAVING total_quantity >= 20 
                                        ";

            $filter_str = "";
            $query = NULL;
            if (isset($_GET['query']) && ($_GET['query'] != '')){
                $query = $_GET['query'];
                $filter_str = " AND CONCAT(product_name, formatted_date, total_quantity) LIKE '%$query%'";
            };
            
            $sqlGetMovingProducts .= $filter_str." ORDER BY formatted_date DESC";
            error_log("HERE: sqlGetMovingProducts ".$sqlGetMovingProducts);
            $result = mysqli_query($conn,$sqlGetMovingProducts);

        ?>

        <div class="search">
            <form method="GET" action="">
                <input type="text" value="<?php echo $query; ?>" name="query" placeholder="Search anything...">
                <button class="btns" type="submit">Search</button>
            </form>
        </div>

        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Date</th>
                        <th>Number of Quantity Sold</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    while($data = mysqli_fetch_array($result)){
                        $date = new DateTime($data["formatted_date"]);

                        $formattedDate = $date->format('F j, Y');
                    ?>
                    <tr>
                        <td><?php echo $data["product_name"];?></td>
                        <td><?php echo $formattedDate; ?></td>
                        <td><?php echo $data["total_quantity"];?></td>
                    </tr>
                    <?php
                    };
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