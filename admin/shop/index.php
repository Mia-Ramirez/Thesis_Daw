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
        <?php
        session_start();
        if (isset($_SESSION["user_role"])) {
          if ($_SESSION["user_role"] == "customer"){
            header("Location:../../customer/home/index.php");
          } else if ($_SESSION["user_role"] == "pharmacist"){
            header("Location:../../pharmacist/dashboard/index.php");
          };
          $current_page_title = "shop";
        } else {
            header("Location:../../account/index.php"); // Temporary while waiting for Landing
        };
        ?>
        <?php include '../components/side_nav.php'; ?>
                
        <?php include '../components/top_nav.php'; ?>  

        <div class="main">
            
        </div>

        <script>
            window.onload = function() {
                setActivePage("nav_shop");
            };
        </script>
    </body>
</html>