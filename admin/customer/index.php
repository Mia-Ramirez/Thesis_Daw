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
          $current_page_title = "customers";
        } else {
            header("Location:../../account/index.php"); // Temporary while waiting for Landing
        };
        ?>
        <?php include '../components/side_nav.php'; ?>
                
        <?php include '../components/top_nav.php'; ?>  

        <div class="main">
            <div class="card history">
                <h3>HISTORY</h3>
                <img class="pic" src="../../assets/images/history.png" alt="history">
            </div>
            <div class="card recovery">
                <h3>ACCOUNT RECOVERY</h3>
                <img class="pic" src="../../assets/images/recover.png" alt="recovery">
            </div>
            <a href="#">
            <div class="card profile">
                <h3>PROFILE</h3>
                <img class="pic" src="../../assets/images/profile.png" alt="profile">
            </div>
            </a>
        </div>

        <script>
            window.onload = function() {
                setActivePage("nav_customer");
            };
        </script>
    </body>
</html>