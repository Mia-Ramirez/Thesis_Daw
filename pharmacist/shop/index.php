<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="../../assets/scripts/common_fx.js"></script>
        <title>
        Pharmacist Dashboard
        </title>
    </head>

    <body style="background: linear-gradient(to bottom right, lightsalmon, white); background-repeat: no-repeat; background-attachment: fixed;">
      <?php
            session_start();
            $current_page_title = "shop";
            include '../components/unauth_redirection.php';
        ?>
        <?php include '../components/side_nav.php'; ?>
                
        <?php include '../components/top_nav.php'; ?>  

        <?php
            session_start();
            include '../components/unauth_redirection.php';
            $base_url = $_SESSION["BASE_URL"];

            $category_id = NULL;
            $query = NULL;

            if (isset($_GET['category_id'])){
                $category_id = $_GET['category_id'];
            };

            if (isset($_GET['query'])){
                $query = $_GET['query'];
            };

        ?>

        <div class="container">

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
                
                $sqlGetMedicines = "SELECT m.id AS medicine_id, name AS medicine_name, price, photo
                                        FROM medicine_categories mc
                                        JOIN medicine m ON mc.medicine_id = m.id
                                    ";

                if ($category_id){
                    $sqlGetMedicines .= " WHERE m.id IN (SELECT medicine_id FROM medicine_categories WHERE FIND_IN_SET($category_id, category_ids) > 0)";
                };

                if ($query){
                    if (strpos($sqlGetMedicines, "WHERE") != false){
                        $sqlGetMedicines .= " AND (m.name LIKE '%$query%')";
                    } else {
                        $sqlGetMedicines .= " WHERE (m.name LIKE '%$query%')";
                    };

                    $sqlGetCategoryIDs = "SELECT id FROM category WHERE name LIKE '%$query%'";
                    $category_id_results = mysqli_query($conn,$sqlGetCategoryIDs);
                    while($data = mysqli_fetch_array($category_id_results)){
                        $sqlGetMedicines .= " OR (FIND_IN_SET(".$data['id'].", mc.category_ids) > 0)";
                    };
                };
                
                $sqlGetMedicines .= " ORDER BY m.id DESC";
                
                $medicine_results = mysqli_query($conn,$sqlGetMedicines);
                while($data = mysqli_fetch_array($medicine_results)){
            ?>
            <div class="product">
                <center>
                    <img class="img" src="<?php echo $data['photo']; ?>" alt="<?php echo $data['medicine_name']; ?>">
                </center>
                <p>Price &#8369 <?php echo $data['price']; ?></p>
                <p><?php echo $data['medicine_name']; ?></p>
            </div>
            <?php
                };
            ?>
            </div>
        </div>

        <script>
            window.onload = function() {
                setActivePage("nav_shop");
            };
        </script>
    </body>
</html>