<?php
    session_start();
    $base_url = $_SESSION["BASE_URL"];
    $doc_root = $_SESSION["DOC_ROOT"];
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>assets/styles/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="../../../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="<?php echo $base_url;?>assets/scripts/common_fx.js"></script>
        <?php include '../../../components/title.php'; ?>
    </head>

    <body>
        <?php include '../../../components/unauth_redirection.php'; ?>
        
        <?php include '../../../components/side_nav.php'; ?>

        <?php
            $current_page_title = "stock in";
            include '../../../components/top_nav.php';
        ?>

        <?php
            include($doc_root.'/utils/connect.php');
            if (isset($_GET['product_id'])) {
                $product_id = $_GET['product_id'];
                $sqlGetProduct = "SELECT name, cost, price FROM product WHERE id=$product_id";
                $product_result = mysqli_query($conn,$sqlGetProduct);
                if ($product_result->num_rows == 0){
                    header("Location:../../../../../page/404.php");
                };
                $row = mysqli_fetch_array($product_result);
                $_SESSION['product_id'] = $product_id;
                if (!is_null($row['cost']) && (isset($_SESSION['cost']) == false)){
                    $_SESSION['cost'] = $row['cost'];
                };
                if (!is_null($row['price']) && (isset($_SESSION['selling_price']) == false)){
                    $_SESSION['selling_price'] = $row['price'];
                };

            } else {
                header("Location:../../index.php");
            };


            $sqlGetSupliers = "SELECT id AS supplier_id, name AS supplier_name FROM supplier ORDER BY id DESC";
        
            $suppliers = mysqli_query($conn,$sqlGetSupliers);
        ?>

        <!-- Add Supplier Modal -->
        <form method="POST" action="process.php">
        <div id="supplierModal" class="modal">
            <div class="modal-content">
                <h3 id="modalTitle">Add Supplier</h3>
                <input type="hidden" name="modal_supplier_id" id="supplierId">
                <label for="supplierName">Supplier Name:</label>
                <input type="text" name="modal_supplier_name" id="supplierName">
                <button class="btns" style="padding: 5px; margin: 5px;" type="submit" name="action" value="save_supplier">Save</button>
                <button id="modal_close_button" class="btns" style="padding: 5px; margin: 5px;" type="button" onclick="closeModal()">Cancel</button>
            </div>
        </div>
        
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
                <div class="column">
                    <p>
                        <label for="product">Product Name:</label><br>
                        <input type="text" value="<?php echo $row['name']; ?>" disabled>
                    </p>
                
                    
                    <div class="form-group">
                        <label for="supplier-option">Choose an option (for Supplier):</label>
                        <select id="supplier-option" name="supplier_id" onchange="checkSelection()" style="width: 80% !important;" value="<?php if(isset($_SESSION["supplier_name"])){echo $_SESSION["supplier_name"];unset($_SESSION["supplier_name"]);}?>" required>
                            <option value="">Select</option>
                            <?php
                                $index = 0;
                                while($data = mysqli_fetch_array($suppliers)){
                                    echo "<option data-index='$index' value='".$data['supplier_id']."'>".$data['supplier_name']."</option>";
                                    $index++;
                                };
                            ?>
                            <option value="new_supplier">Add New Supplier</option>
                        </select>
                        <i id="edit_supplier_button" style="display: none" class="button-icon fas fa-pen-to-square" title="Edit"></i>
                    </div>
                   

                    <div class="row">
                        <p class="column">
                            <label for="cost">Wholesale Price:</label><br>
                            <input style="width: 60%" id="cost_input" type="number" step="0.01" min="1" name="cost" required value="<?php if(isset($_SESSION["cost"])){echo $_SESSION["cost"];unset($_SESSION["cost"]);}?>">
                        </p>
                        <p class="column">
                            <label for="selling_price">Selling Price:</label><br>
                            <input style="width: 60%" id="selling_price_input" type="number" step="0.01" min="1" name="selling_price" required value="<?php if(isset($_SESSION["selling_price"])){echo $_SESSION["selling_price"];unset($_SESSION["selling_price"]);}?>">
                        </p>
                    </div>

                </div>

                <div class="column">
                    <p>
                        <label for="reference_number">Reference Number:</label><br>
                        <input id="reference_number_input" type="text" name="reference_number" required  value="<?php if(isset($_SESSION["reference_number"])){echo $_SESSION["reference_number"];unset($_SESSION["reference_number"]);}?>">
                    </p>

                    <p>
                        <label for="quantity">Quantity:</label><br>
                        <input id="quantity_input" type="number" name="quantity" required value="<?php if(isset($_SESSION["quantity"])){echo $_SESSION["quantity"];unset($_SESSION["quantity"]);}?>">
                    </p>

                    <p>
                        <label for="expiration_date">Date of Expiration:</label><br>
                        <input id="expiration_date_input"  type="date" name="expiration_date" required  value="<?php if(isset($_SESSION["expiration_date"])){echo $_SESSION["expiration_date"];unset($_SESSION["expiration_date"]);}?>">
                    </p>
                </div>

                <button id="add_stock" name="action" value="add_stock">Add Stock</button>
            
            </div>
        </div>
        </form>
        
        <script src="script.js"></script>

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