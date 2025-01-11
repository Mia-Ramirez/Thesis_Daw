<?php
    session_start();
    $base_url = $_SESSION["BASE_URL"];
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="../../../../assets/styles/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="../../../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="../../../../assets/scripts/common_fx.js"></script>
        <?php include '../../../components/title.php'; ?>
    </head>

    <body>
        <?php include '../../../components/unauth_redirection.php'; ?>
        
        <?php include '../../../components/side_nav.php'; ?>

        <?php
            $current_page_title = "update medicine details";
            include '../../../components/top_nav.php';
        ?> 

        <?php
            include('../../../../utils/connect.php');
            if (isset($_GET['medicine_id'])) {
                $medicine_id = $_GET['medicine_id'];
                $_SESSION["medicine_id"] = $medicine_id;
                $sqlGetMedicine = "SELECT
                                    name, price, applicable_discounts, prescription_is_required, photo, rack_location, maintaining_quantity,
                                    (SELECT GROUP_CONCAT(c.name ORDER BY c.name SEPARATOR ', ') AS category_names FROM medicine_categories mc
                                        JOIN category c ON FIND_IN_SET(c.id, mc.category_ids) > 0
                                        WHERE mc.medicine_id=$medicine_id
                                    ) as category_names FROM medicine
                                    WHERE id=$medicine_id";

                $medicine_result = mysqli_query($conn,$sqlGetMedicine);
                if ($medicine_result->num_rows == 0){
                    header("Location:../../../../page/404.php");
                }

                $row = mysqli_fetch_array($medicine_result);
                $_SESSION['photo_url'] = $row['photo'];

                // Split the string by commas
                $medicineCategoriesJSON = json_encode(array());
                if ($row['category_names'] != ''){
                    $medicineCategoriesJSON = json_encode(explode(",", $row["category_names"]));
                };

                $sqlGetCategories = "SELECT name FROM category ORDER BY id";
        
                $category_result = mysqli_query($conn,$sqlGetCategories);
                $category_names = array();
                
                if ($category_result){
                    while ($category_row = mysqli_fetch_assoc($category_result)){
                        $category_names[] = $category_row['name'];
                    };
                };

                echo "
                    <script>
                        let validCategories = ".json_encode($category_names).";
                    </script>";

            } else {
                header("Location:../../../../page/404.php");
            };
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
                <form action="process.php" method="post" enctype="multipart/form-data">
                    <div class="column">
                        <div class="row">
                            <p class="column">
                                <label for="medicine_name">Name:</label><br>
                                <input type="text" id="medicine_name" name="medicine_name" value="<?php echo $row["name"];?>">
                            </p>

                            <p class="column">
                                <label for="price">Price:</label><br>
                                <input type="number" step="0.01" min="1" id="price" name="price" value="<?php echo $row["price"];?>">
                            </p>
                        </div>
                        
                        <div class="row">
                            <p class="column">
                                <label for="valid_discounts">Discounts: </label><br>
                                <select id="valid_discounts" name="valid_discounts" required>
                                    <option value="">Select</option>
                                    <option <?php if ($row['applicable_discounts'] == "None"){echo "selected";};?>>None</option>
                                    <option <?php if ($row['applicable_discounts'] == "Person With Disabilities"){echo "selected";};?>>Person With Disabilities</option>
                                    <option <?php if ($row['applicable_discounts'] == "Senior Citizen"){echo "selected";};?>>Senior Citizen</option>
                                    <option <?php if ($row['applicable_discounts'] == "Both"){echo "selected";};?>>Both</option>
                                </select>
                            </p>
                            
                            <p class="column">
                                <label for="required_prescription">Required Prescription?: </label><br>
                                <select id="required_prescription" name="required_prescription" required>
                                    <option value="">Select</option>
                                    <option <?php if ($row['prescription_is_required'] == "1"){echo "selected";};?>>Yes</option>
                                    <option <?php if ($row['prescription_is_required'] == "0"){echo "selected";};?>>No</option>
                                </select>
                            </p>
                            
                        </div>

                        <p>
                            <label for="categories">Categories:</label><br>
                            <div class="categories-container" id="categories-container">
                                <input type="text" id="category-input" class="category-input" placeholder="Type and press Enter" autocomplete="off">
                                <ul class="suggestions" id="suggestions-list"></ul>
                            </div>
                            <input type="hidden" name="category_names" id="category-names" value="<?php echo $row["category_names"];?>">
                        </p>
                    
                    </div>

                    <div class="column">
                        <div class="row">
                            <p class="column">
                                <label for="rack_location">Rack Location:</label><br>
                                <input type="text" id="rack_location" name="rack_location" required value="<?php echo $row["rack_location"];?>">
                            </p>

                            <p class="column">
                                <label for="maintaining_quantity">Maintaining Quantity:</label><br>
                                <input type="number" id="maintaining_quantity" name="maintaining_quantity" required value="<?php echo $row["maintaining_quantity"];?>">
                            </p>
                        </div>
                        
                        <div class="row" style="margin-left: 10px">
                            <label for="image">Photo: </label><br>
                            <div class="image-container">
                                <input name="image" type="file" accept="image/*" onchange="previewImage(event, 'update')">
                                <img id="img_photo" src="<?php echo $row['photo']; ?>">
                            </div>
                        </div>
                    </div>

                    <button name="action" value="update_medicine">Update</button>
                </form>
            </div>
        </div>
        
        <script src="../scripts.js"></script>

        <script>
            // Array to track already selected categories
            const selectedCategories = [];
            var medicineCategories = <?php echo $medicineCategoriesJSON; ?>;
            medicineCategories.forEach(categoryValue => {
                addCategory(categoryValue.trim());
                selectedCategories.push(categoryValue.trim())
            });
            
        </script>

        <script>
            window.onload = function() {
                setActivePage("nav_inventory");
            };
        </script>
    </body>
</html>