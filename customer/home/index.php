<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHARMANEST ESSENTIAL</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body style="background: linear-gradient(to bottom right, lightsalmon, white);
        background-repeat: no-repeat;
        background-attachment: fixed;">
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
        <nav class="navbar">
            <div class="navdiv">
            <div class="logo">
                <img style="width: 100px; float: left; border-radius: 100%; border: solid 1px rgb(0, 0, 0); margin: 1.5rem; margin-left: 3rem;" 
                src=<?php echo $base_url."assets/images/logo.png";?>  alt="PHARMANEST ESSENTIAL LOGO">
            </div>
            <div class="title">
            <h1> PHARMANEST ESSENTIAL </h1>
            </div>
                <div class="li">
                    <div class="menu">
                    <a href="<?php echo $base_url."customer/home/index.php";?>">
                        <i class="fas fa-home"></i> <!-- home icon -->
                    </a>
                    </div>

                    <div class="menu">
                    <a href="#">
                        <i class="fas fa-cart-shopping"></i> <!-- add to cart icon -->
                    </a>
                    </div>

                    <div class="menu">
                    <a href="#">
                        <i class="fas fa-envelope"></i> <!-- chat icon -->
                    </a>
                    </div>

                    <div id="ikot" class="menu"  > <!-- gusto kong paikutin -->
                        <a href="#">
                            <i  class="fas fa-gear"></i> <!-- settings icon -->
                        </a>

                        <div class="sub-menu">
                            <a href="#">My Account</a>
                            <a href="#">Logout</a>
                        </div>
                    </div>
                </div>
                
            </div>
        </nav>

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
                        $sqlGetMedicines .= " AND (m.name LIKE '%$query%') OR (FIND_IN_SET((SELECT id FROM category WHERE name LIKE '%$query%'), mc.category_ids) > 0)";
                    } else {
                        $sqlGetMedicines .= " WHERE (m.name LIKE '%$query%') OR (FIND_IN_SET((SELECT id FROM category WHERE name LIKE '%$query%'), mc.category_ids) > 0)";
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
                <center>
                    <a href="#"><button class="btn">Buy Now</button></a>
                    <a href="#"><button class="btn">Add to Cart</button></a>
                </center>
            </div>
            <?php
                };
            ?>
        </div>

    </body>
</html>