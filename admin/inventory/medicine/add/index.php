<?php
    session_start();
    $base_url = $_SESSION["BASE_URL"];
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>assets/styles/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="../../../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="<?php echo $base_url;?>assets/scripts/common_fx.js"></script>
        <title>
        Admin Dashboard
        </title>
    </head>

    <body>
        <?php include '../../../components/unauth_redirection.php'; ?>
        
        <?php include '../../../components/side_nav.php'; ?>
        
        <?php
            $current_page_title = "add medicine";
            include '../../../components/top_nav.php';
        ?>

        <?php
            include('../../../../utils/connect.php');
            $sqlGetCategories = "SELECT name FROM category ORDER BY id";
        
            $result = mysqli_query($conn,$sqlGetCategories);
            $category_names = array();
            
            if ($result){
                while ($row = mysqli_fetch_assoc($result)){
                    $category_names[] = $row['name'];
                };
            };

            echo "
                <script>
                    let validCategories = ".json_encode($category_names).";
                </script>";

        ?>

        <div class="main">
            <div class="row">
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
                <form action="process.php" method="POST" enctype="multipart/form-data">
                    <div class="column">
                        <div class="row">
                            <p class="column">
                                <label for="medicine_name">Name:</label><br>
                                <input type="text" id="medicine_name" name="medicine_name" required value="<?php if(isset($_SESSION["medicine_name"])){echo $_SESSION["medicine_name"];unset($_SESSION["medicine_name"]);}?>">
                            </p>

                            <p class="column">
                                <label for="price">Price:</label><br>
                                <input type="number" step="0.01" min="1" id="price" name="price" required value="<?php if(isset($_SESSION["price"])){echo $_SESSION["price"];unset($_SESSION["price"]);}?>">
                            </p>
                        </div>
                        
                        <div class="row">
                            <p class="column">
                                <label for="valid_discounts">Discounts: </label><br>
                                <select id="valid_discounts" name="valid_discounts" required>
                                    <option value="">Select</option>
                                    <option>None</option>
                                    <option>Person With Disabilities</option>
                                    <option>Senior Citizen</option>
                                    <option>Both</option>
                                </select>
                            </p>
                            
                            <p class="column">
                                <label for="required_prescription">Required Prescription?: </label><br>
                                <select id="required_prescription" name="required_prescription" required>
                                    <option value="">Select</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </p>
                            
                        </div>
                        
                        <p>
                            <label for="category-input">Categories:</label>
                            <div class="categories-container" id="categories-container">
                                <input type="text" id="category-input" class="category-input" placeholder="Type and press Enter" autocomplete="off">
                                <ul class="suggestions" id="suggestions-list"></ul>
                            </div>
                            <input type="hidden" name="category_names" id="category-names">
                        </p>
                    
                    </div>

                    <div class="column">
                        <div class="row">
                            <p class="column">
                                <label for="rack_location">Rack Location:</label><br>
                                <input type="text" id="rack_location" name="rack_location" required value="<?php if(isset($_SESSION["rack_location"])){echo $_SESSION["rack_location"];unset($_SESSION["rack_location"]);}?>">
                            </p>

                            <p class="column">
                                <label for="maintaining_quantity">Maintaining Quantity:</label><br>
                                <input type="number" id="maintaining_quantity" name="maintaining_quantity" required value="<?php if(isset($_SESSION["maintaining_quantity"])){echo $_SESSION["maintaining_quantity"];unset($_SESSION["maintaining_quantity"]);}?>">
                            </p>
                        </div>
                        
                        <div class="row" style="margin-left: 10px">
                            <label for="image">Photo: </label><br>
                            <div class="image-container">
                                <input name="image" type="file" accept="image/*" onchange="previewImage(event, 'add')" required>
                                <span id="span_image_text">Click to upload an image</span>
                            </div>
                        </div>
                    </div>
                    
                    <button name="action" value="add_medicine">Add</button>
                </form>
            </div>
        </div>
                
        <script src="../scripts.js"></script>

        <script>
            // Array to track already selected categories
            const selectedCategories = [];            
        </script>

        <script>
            window.onload = function() {
                setActivePage("nav_inventory");
            };
        </script>
    </body>
</html>