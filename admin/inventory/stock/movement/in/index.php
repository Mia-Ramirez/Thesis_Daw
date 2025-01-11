<?php
    session_start();
    $base_url = $_SESSION["BASE_URL"];
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="../../../../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="../../../../../assets/scripts/common_fx.js"></script>
        <?php include '../../../../components/title.php'; ?>
    </head>

    <body>
        <?php include '../../../../components/unauth_redirection.php'; ?>
        
        <?php include '../../../../components/side_nav.php'; ?>

        <?php
            $current_page_title = "stock in";
            include '../../../../components/top_nav.php';
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
                <form action="" method="POST">
                    <div class="column">
                        <div class="row">
                            <p class="column">
                                Medicine:
                            </p>

                            <p class="column">
                                <label for="reference_number">Reference Number:</label><br>
                                <input type="text" id="reference_number" name="reference_number" required>
                            </p>
                        </div>
                        
                        <div class="row">
                            <p class="column">
                                <label for="date_received">Date Received:</label><br>
                                <input type="date" name="date_received" required>
                            </p>
                            
                            <p class="column">
                                <label for="sex">Sex: </label><br>
                                <select id="sex" name="sex" required>
                                    <option value="">Select</option>
                                    <option>Female</option>
                                    <option>Male</option>
                                    <option>Others</option>
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

        <script>
            // function redirectToPage(page) {
            //     window.location.href = './'+page+'/index.php';
            // };
            
            window.onload = function() {
                setActivePage("nav_inventory");
            };
        </script>
    </body>
</html>