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
            $current_page_title = "inventory";
            include '../components/top_nav.php';
        ?>

        <div class="main" style="margin-left: 12%; margin-right: 0%">
            <div class="card stock_out" onclick="redirectToPage('stock_out')">
                <h3>STOCK OUT</h3>
                <img class="pic" src=<?php echo $base_url."assets/images/stockOut.png"; ?> alt="history">
            </div>
            <div class="card stock_in" onclick="redirectToPage('stock_in')">
                <h3>STOCK IN</h3>
                <img class="pic" src=<?php echo $base_url."assets/images/stockIn.png"; ?> alt="history">
            </div>
            <div class="card category" onclick="redirectToPage('category')">
                <h3>CATEGORIES</h3>
                <img class="pic" src=<?php echo $base_url."assets/images/categories.png"; ?> alt="category">
            </div>
            <div class="card product" onclick="redirectToPage('product')">
                <h3>PRODUCTS</h3>
                <img class="pic" src=<?php echo $base_url."assets/images/meds.png"; ?> alt="product">
            </div>
            <div class="card history" onclick="redirectToPage('history')">
                <h3>HISTORY</h3>
                <img class="pic" src=<?php echo $base_url."assets/images/history.png"; ?> alt="history">
            </div>
            <div class="card stock" onclick="redirectToPage('low_stock')">
                <h3>LOW STOCKS</h3>
                <img class="pic" src=<?php echo $base_url."assets/images/stock.png"; ?> alt="stock">
            </div>  
        </div>

        <script>
            function redirectToPage(page) {
                if (page == 'low_stock'){
                    window.location.href = './stock/index.php?is_low=true';
                } else if (page == 'stock_in'){
                    window.location.href = './stock/index.php?stock_type=in';
                } else if (page == 'stock_out'){
                    window.location.href = './stock/index.php?stock_type=out';
                } else {    
                    window.location.href = './'+page+'/index.php';
                };
            };
            
            window.onload = function() {
                setActivePage("nav_inventory");
            };
        </script>
    </body>
</html>