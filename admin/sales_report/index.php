<?php
    session_start();
    $base_url = $_SESSION["BASE_URL"];
    // $doc_root = $_SESSION["DOC_ROOT"];
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="<?php echo $base_url;?>assets/scripts/common_fx.js"></script>
        <?php include '../components/title.php'; ?>
    </head>

    <body>
        <?php include '../components/unauth_redirection.php'; ?>
        
        <?php include '../components/side_nav.php'; ?>
        
        <?php
            $current_page_title = "sales report";
            include '../components/top_nav.php';
        ?> 
        
        <div class="main">
          <div class="card account-history" onclick="redirectToPage('history')">
              <h3>HISTORY</h3>
              <img class="pic" src=<?php echo $base_url."assets/images/history.png"; ?> alt="history">
          </div>
          <div class="card capital-and-revenue" onclick="redirectToPage('capital_and_revenue')">
            <h3>CAPITAL & REVENUE</h3>
              <img class="pic" src=<?php echo $base_url."assets/images/capital.png"; ?> alt="capital_and_revenue">
          </div>
          <div class="card slow-moving-meds" onclick="redirectToPage('slow_moving')">
              <h3>SLOW MOVING PRODUCTS</h3>
              <img class="pic" src=<?php echo $base_url."assets/images/slow.png"; ?> alt="slow_moving_meds">
          </div>
          <div class="card slow-moving-meds" onclick="redirectToPage('fast_moving')">
              <h3>FAST MOVING PRODUCTS</h3>
              <img class="pic" src=<?php echo $base_url."assets/images/fast.png"; ?> alt="fast_moving_meds">
          </div>
        </div>

        <script>
          function redirectToPage(page) {
              window.location.href = './'+page+'/index.php';
          };

          window.onload = function() {
              setActivePage("nav_sales_report");
          };
        </script>
    </body>
</html>