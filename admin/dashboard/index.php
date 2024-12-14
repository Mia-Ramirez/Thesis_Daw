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
          $current_page_title = "dashboard";
        } else {
            header("Location:../../account/index.php"); // Temporary while waiting for Landing
        };
        ?>
        <?php include '../components/side_nav.php'; ?>
                
        <?php include '../components/top_nav.php'; ?>  

        <div class="pot">
            <div class="pots">
                <div id="two" class="card">
                    <h2>0</h2>
                    <p>Not Moving Meds</p>
                </div>
                <div id="four" class="card">
                    <h2>0</h2>
                    <p>Meds Shortage</p>
                </div>
            </div>
            <div id="three" class="card">
                <h2>0</h2>
                <p>New Orders</p>
            </div>
            <div id="one" class="card">
                <h2>0</h2>
                <p>Soon to Expire</p>
            </div>
        </div>

        <div class="main">
            <div class="report">
                <h2 style="text-align: center;">Today's Report</h2>
                <div class="grid">
                    <div class="box"><h4>Total Sales</h4>
                        <h3>0</h3>
                    </div>
                    <div class="box"><h4>Total Purchase</h4>
                        <h3>0</h3>
                    </div>
                </div>
            </div>
        </div>

        <script>
            window.onload = function() {
                setActivePage("nav_dashboard");
            };
        </script>
    </body>
</html>