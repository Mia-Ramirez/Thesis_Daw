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
          $current_page_title = "inventory";
        } else {
            header("Location:../../account/index.php"); // Temporary while waiting for Landing
        };
        ?>
        <?php include '../components/side_nav.php'; ?>
                
        <?php include '../components/top_nav.php'; ?>  

        <div class="main">
            <div class="card stock">
                <h3>LOW STOCKS</h3>
                <img class="pic" src="../../assets/images/stock.png" alt="history">
            </div>
            <div class="card category">
                <h3>MEDS CATEGORIES</h3>
                <img class="pic" src="../../assets/images/categories.png" alt="recovery">
            </div>
            <div class="card medicine">
                <h3>MEDICINE</h3>
                <img class="pic" src="../../assets/images/meds.png" alt="profile">
            </div>
        </div>

        <script>
            window.onload = function() {
                setActivePage("nav_inventory");
            };
        </script>
    </body>
</html>