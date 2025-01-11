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
        <?php include '../components/title.php'; ?>
    </head>

    <body>
        <?php include '../components/unauth_redirection.php'; ?>

        <?php include '../components/side_nav.php'; ?>

        <?php
            $current_page_title = "customers";
            include '../components/top_nav.php';
        ?>

        <div class="main">
            <div class="card account-recovery" onclick="redirectToPage('archived')">
                <h3>ARCHIVED</h3>
                <img class="pic" src="../../assets/images/recover.png" alt="recovery">
            </div>
            <!-- <div class="card">
            </div> -->
            <div class="card add-customer" onclick="redirectToPage('add')">
                <h3>ADD CUSTOMER</h3>
                <img class="pic" src="../../assets/images/addAcct.png" alt="add_customer">
            </div>
            <div class="card profile" onclick="redirectToPage('list')">
                <h3>PROFILES</h3>
                <img class="pic" src="../../assets/images/profile.png" alt="profile">
            </div>
        </div>

        <script>
            function redirectToPage(page) {
                window.location.href = './'+page+'/index.php';
            };

            window.onload = function() {
                setActivePage("nav_customer");
            };
        </script>
    </body>
</html>