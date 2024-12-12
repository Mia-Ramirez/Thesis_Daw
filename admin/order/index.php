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
        } else {
            header("Location:../../account/index.php"); // Temporary while waiting for Landing
        };
        ?>
        <?php include '../components/side_nav.php'; ?>
                
        <div class="topnav">
            <a href="../../account/logout.php">Logout(Logged-in as Admin)</a>
        </div>

        <center>
        <div class="head">
            <h2> ORDERS </h2>
            <h4>Welcome, Admin!</h4>
        </div>
        </center>

        <div class="main">
            <div class="card history">
                <h3>HISTORY</h3>
                <img class="pic" src="../../assets/images/history.png" alt="history">
            </div>
            <div class="card cancel">
                <h3>CANCELLED</h3>
                <img class="pic" src="../../assets/images/cancel.png" alt="recovery">
            </div>
            <div class="card pickup">
                <h3>FOR PICKUP</h3>
                <img class="pic" src="../../assets/images/pickup.png" alt="recovery">
            </div>
            <div class="card new">
                <h3>NEW ORDER</h3>
                <img class="pic" src="../../assets/images/new.png" alt="profile">
            </div>
        </div>

        <script>
            window.onload = function() {
                setActivePage("nav_order");
            };
        </script>
    </body>
</html>