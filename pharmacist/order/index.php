<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="../../assets/scripts/common_fx.js"></script>
        <title>
        Pharmacist Dashboard
        </title>
    </head>

    <body>
        <?php
            session_start();
            $current_page_title = "orders";
            include '../components/unauth_redirection.php';
        ?>
        <?php include '../components/side_nav.php'; ?>
                
        <?php include '../components/top_nav.php'; ?>  

        <div class="main">
            <div class="card history">
                <h3>HISTORY</h3>
                <img class="pic" src="../../assets/images/history.png" alt="history">
            </div>
            <div class="card cancel">
                <h3>CANCELLED</h3>
                <img class="pic" src="../../assets/images/cancel.png" alt="cancelled">
            </div>
            <div class="card pickup">
                <h3>FOR PICKUP</h3>
                <img class="pic" src="../../assets/images/pickup.png" alt="pickup">
            </div>
            <div class="card new">
                <h3>NEW ORDER</h3>
                <img class="pic" src="../../assets/images/new.png" alt="new_order">
            </div>
        </div>

        <script>
            window.onload = function() {
                setActivePage("nav_order");
            };
        </script>
    </body>
</html>