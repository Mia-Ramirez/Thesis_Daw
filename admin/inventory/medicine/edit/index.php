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
            $current_page_title = "update medicine details";
            include '../../../components/unauth_redirection.php';
        ?>
        
        <?php include '../../../components/side_nav.php'; ?>
                
        <?php include '../../../components/top_nav.php'; ?>  

        <?php
            include('../../../../utils/connect.php');
            if (isset($_GET['medicine_id'])) {
                $medicine_id = $_GET['medicine_id'];
                $_SESSION["medicine_id"] = $medicine_id;
                $sqlGetMedicine = "SELECT * FROM medicine
                            WHERE id=$medicine_id";

                $medicine_result = mysqli_query($conn,$sqlGetMedicine);
                if ($medicine_result->num_rows == 0){
                    header("Location:../../../../page/404.php");
                }

                $row = mysqli_fetch_array($medicine_result);
                
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
                <form action="process.php" method="post">
                    <div class="column">
                        <div class="row">
                            <p class="column">
                                <label for="medicine_name">Name:</label><br>
                                <input type="text" id="medicine_name" name="medicine_name" value="<?php echo $row["name"];?>">
                            </p>

                            <p class="column">
                                <label for="price">Price:</label><br>
                                <input type="number" id="price" name="price" value="<?php echo $row["price"];?>">
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
                    
                    </div>

                    <div class="column">
                        <label for="photo">Photo: </label><br>
                        <div class="image-container">
                            <input name="photo" type="file" accept="image/*" onchange="previewImage(event)">
                            <span>Click or drag to upload an image</span>
                        </div>
                    </div>
                    
                
                    <button name="action" value="update_medicine">Update</button>
                </form>
            </div>
        </div>

        <script>
            window.onload = function() {
                setActivePage("nav_inventory");
            };
        </script>
    </body>
</html>