<?php
    session_start();
    $base_url = $_SESSION["BASE_URL"];
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="../../assets/scripts/common_fx.js"></script>
        <title>
        Admin Dashboard
        </title>
    </head>

    <body>
        <?php include '../components/unauth_redirection.php'; ?>
        <?php include '../components/side_nav.php'; ?>
        
        <?php
            $current_page_title = "point of sale";
            include '../components/top_nav.php';
        ?> 

        <div class="main">
            
        </div>

        <script>
            window.onload = function() {
                setActivePage("nav_pos");
            };
        </script>
    </body>
</html>