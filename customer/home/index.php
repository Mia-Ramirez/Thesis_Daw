<?php
    session_start();
    $base_url = $_SESSION["BASE_URL"];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHARMANEST ESSENTIAL</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>assets/styles/bootstrap.css">
        <link rel="stylesheet" href="../styles.css">
        <link rel="stylesheet" href="styles.css">
        <script src="<?php echo $base_url;?>assets/scripts/common_fx.js"></script>
    </head>
    <body class="body">
        <?php include '../components/unauth_redirection.php'; ?>

        <?php include '../components/navbar.php'; ?>  

        <?php
            $category_id = NULL;
            $query = NULL;

            if (isset($_GET['category_id'])){
                $category_id = $_GET['category_id'];
            };

            if (isset($_GET['query'])){
                $query = $_GET['query'];
            };

        ?>
        
        <?php 
            if (isset($_SESSION["message_string"])) {
            ?>
                
                <div class="alert alert-<?php echo $_SESSION["message_class"] ?>">
                    <?php 
                    echo $_SESSION["message_string"];
                    ?>
                </div>
            <?php
            unset($_SESSION["message_string"]);
            unset($_SESSION["message_class"]);
            }
        ?>

        <div class="search">
        <form method="GET" action="">
        <input type="text" value="<?php echo $query; ?>" name="query" placeholder="Search anything...">
            <button class="btns" type="submit">Search</button>
        </form>
        </div>

        <div class="categories"> <!-- show different types of meds for faster and easier navigation -->
            <?php
                include('../../utils/connect.php');
                
                $sqlGetCategories = "SELECT id AS category_id, name AS category_name FROM category
                                ORDER BY id DESC";
                
                $category_result = mysqli_query($conn,$sqlGetCategories);
                while($data = mysqli_fetch_array($category_result)){
            ?>
            <div class="meds"><a <?php if ($category_id == $data['category_id']){echo 'class=active-category '; }; ?>href="./index.php?category_id=<?php echo $data['category_id']; ?>"><?php echo $data["category_name"];?></a></div>
            <?php
                };
            ?>
        </div>

        <div class="details">
            <?php
                include('../../utils/connect.php');
                
                $sqlGetProducts = "SELECT
                                        p.id AS product_id,
                                        name AS product_name,
                                        price,
                                        current_quantity,
                                        photo
                                    FROM product_categories mc
                                    JOIN product p ON mc.product_id = p.id
                                    ";

                if ($category_id){
                    $sqlGetProducts .= " WHERE p.id IN (SELECT product_id FROM product_categories WHERE FIND_IN_SET($category_id, category_ids) > 0)";
                };

                if ($query){
                    if (strpos($sqlGetProducts, "WHERE") != false){
                        $sqlGetProducts .= " AND (p.name LIKE '%$query%')";
                    } else {
                        $sqlGetProducts .= " WHERE (p.name LIKE '%$query%')";
                    };

                    $sqlGetCategoryIDs = "SELECT id FROM category WHERE name LIKE '%$query%'";
                    $category_id_results = mysqli_query($conn,$sqlGetCategoryIDs);
                    while($data = mysqli_fetch_array($category_id_results)){
                        $sqlGetProducts .= " OR (FIND_IN_SET(".$data['id'].", mc.category_ids) > 0)";
                    };
                };
                
                $sqlGetProducts .= " ORDER BY p.id DESC";
                
                $product_results = mysqli_query($conn,$sqlGetProducts);
                while($data = mysqli_fetch_array($product_results)){
            ?>
            <div class="product">
                <center>
                    <img class="img" src="<?php echo $data['photo']; ?>" alt="<?php echo $data['product_name']; ?>">
                </center>
                <p>Price &#8369 <?php echo $data['price']; ?></p>
                <p><?php echo $data['product_name']; ?></p>
                <?php
                    if ($data['current_quantity'] == '0'){
                        echo "<p style='color: red;'>Out of Stock</p>";
                    } else {
                        ?>
                        <center>
                        <a href="process.php?action=buy_now&product_id=<?php echo $data["product_id"]; ?>"><button class="btn">Buy Now</button></a>
                        <a href="process.php?action=add_to_cart&product_id=<?php echo $data["product_id"]; ?>"><button class="btn">Add to Cart</button></a>
                        </center>
                    <?php
                    }
                ?>
            
            </div>
            <?php
                };
            ?>
        </div>
            
        <script src="../script.js"></script>

        <script>
            window.onload = function() {
                setActivePage("nav_home");
            };
        </script>
    </body>
</html>