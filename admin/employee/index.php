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
            $current_page_title = "employees";
            include '../components/unauth_redirection.php';
        ?>
        <?php include '../components/side_nav.php'; ?>
                
        <?php include '../components/top_nav.php'; ?>  

        <div class="main">
          <div class="card create-account">
              <h3>CREATE ACCOUNT</h3>
              <img class="pic" src="../../assets/images/addAcct.png" alt="add_employee">
          </div>
          <div class="card accounts">
              <h3>ACCOUNTS</h3>
              <img class="pic" src="../../assets/images/acct.png" alt="list_employees">
          </div>
          <div class="card profile">
              <h3>PROFILES</h3>
              <img class="pic" src="../../assets/images/profile.png" alt="profile">
          </div>
        </div>

        <script>
            window.onload = function() {
                setActivePage("nav_employee");
            };
        </script>
    </body>
</html>