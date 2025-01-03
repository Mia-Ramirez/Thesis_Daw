<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHARMANEST ESSENTIAL</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet" href="../../styles.css">
        <script src="../../../assets/scripts/common_fx.js"></script>
    </head>
    <body class="body">
        <?php
            session_start();
            include '../../components/unauth_redirection.php';
            $base_url = $_SESSION["BASE_URL"];


        ?>
        
        <?php include '../../components/navbar.php'; ?>  

        
        <script src="../../script.js"></script>
        
        <script>
            window.onload = function() {
                setActivePage("nav_cart");
            };
        </script>
    </body>
</html>