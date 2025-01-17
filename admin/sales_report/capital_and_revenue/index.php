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

        <?php
            include($doc_root.'/utils/connect.php');

            $sqlGetDates="SELECT DISTINCT line_dates, DATE_FORMAT(line_dates, '%M %d, %Y') AS formatted_date
                    FROM (
                        SELECT DATE_FORMAT(date_received, '%Y-%m-%d') AS line_dates
                        FROM batch
                        GROUP BY DATE_FORMAT(date_received, '%Y-%m-%d')

                        UNION

                        SELECT DATE_FORMAT(transaction_date, '%Y-%m-%d') AS line_dates
                        FROM transaction
                        GROUP BY DATE_FORMAT(transaction_date, '%Y-%m-%d')
                    ) AS combined_dates";

            $filter_date_str="";
            $query = NULL;
        
            if (isset($_GET['query'])){
                $query = $_GET['query'];
                $filter_date_str=" HAVING formatted_date LIKE '%$query%'";
            };

            $sqlGetDates .= $filter_date_str." ORDER BY line_dates DESC";

            $date_results = mysqli_query($conn,$sqlGetDates);

            $results = array();

            // while($data = mysqli_fetch_array($date_results)){
            //     $sqlGetCapitalValues="";
            // };

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
                    <tr>
                        <td>Biogesic</td>
                        <td>January 11, 2025</td>
                        <td>-P300</td>
                        <td>P400</td>
                        <td>P100</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Loperamide</td>
                        <td>January 11, 2025</td>
                        <td>-P376</td>
                        <td>P400</td>
                        <td>P16</td>
                        <td>P8</td>
                    </tr>
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