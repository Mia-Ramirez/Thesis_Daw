<?php
    session_start();
    $base_url = $_SESSION["BASE_URL"];
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="../../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="../../../assets/scripts/common_fx.js"></script>
        <?php include '../../components/title.php'; ?>
    </head>

    <body>
        <?php include '../../components/unauth_redirection.php'; ?>

        <?php include '../../components/side_nav.php'; ?>

        <?php
            $current_page_title = "slow moving medicines";
            include '../../components/top_nav.php';
        ?> 

        <?php
            include('../../../utils/connect.php');

            $query = NULL;
            if (isset($_GET['query'])){
                $query = $_GET['query'];
            };

        ?>

        <!-- <div class="search">
            <form method="GET" action="">
                <input type="text" value="<?php echo $query; ?>" name="query" placeholder="Search anything...">
                <button class="btns" type="submit">Search</button>
            </form>
        </div> -->

        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>Medicine</th>
                        <th>Date</th>
                        <th>Number of Quantity Sold</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>Loperamide</td>
                        <td>January 11, 2025</td>
                        <td>3</td>
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