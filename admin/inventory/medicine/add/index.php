<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="../../../../assets/styles/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="../../../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="../../../../assets/scripts/common_fx.js"></script>
        <title>
        Admin Dashboard
        </title>
    </head>

    <body>
        <?php
            session_start();
            $current_page_title = "add medicine";
            include '../../../components/unauth_redirection.php';
        ?>
        
        <?php include '../../../components/side_nav.php'; ?>
                
        <?php include '../../../components/top_nav.php'; ?>  

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
                <form action="process.php" method="post">
                    <div class="column">
                        <div class="row">
                            <p class="column">
                                <label for="medicine_name">Name:</label><br>
                                <input type="text" id="medicine_name" name="medicine_name" value="<?php if(isset($_SESSION["medicine_name"])){echo $_SESSION["medicine_name"];unset($_SESSION["medicine_name"]);}?>">
                            </p>

                            <p class="column">
                                <label for="price">Price:</label><br>
                                <input type="number" id="price" name="price" value="<?php if(isset($_SESSION["price"])){echo $_SESSION["price"];unset($_SESSION["price"]);}?>">
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
                    
                    </div>

                    <div class="column">
                        <label for="photo">Photo: </label><br>
                        <div class="image-container">
                            <input name="photo" type="file" accept="image/*" onchange="previewImage(event)">
                            <span>Click or drag to upload an image</span>
                        </div>
                    </div>
                    
                    <button name="action" value="add_medicine">Add</button>
                </form>
            </div>
        </div>
        
        <script src="script.js"></script>
        <script>
            window.onload = function() {
                setActivePage("nav_inventory");
            };
        </script>
    </body>
</html>