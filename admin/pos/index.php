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
        <link rel="stylesheet" type="text/css" href="../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="<?php echo $base_url;?>assets/scripts/common_fx.js"></script>
        <?php include '../components/title.php'; ?>
    </head>

    <body>
        <?php include '../components/unauth_redirection.php'; ?>

        <?php include '../components/side_nav.php'; ?>
        
        <?php
            $current_page_title = "point of sale";
            include '../components/top_nav.php';
        ?> 

        <div class="custom-modal" id="yesNoModal">
            <div class="modal-content">
                <p id="modal_message">Do you want to proceed?</p>
                <form action="process.php" method="POST">
                    <input id="modal_value" type="hidden" name="input_name">
                    <button id="button_yes" type="submit" name="action" class="modal-button yes-button" value="button_value">Yes</button>
                    <button type="button" class="modal-button no-button" onclick="onNo('yesNoModal')">No</button>
                </form>
            </div>
        </div>

        <?php
            include($doc_root.'/utils/connect.php');

            $user_id = $_SESSION['user_id'];
            if (isset($_SESSION['receipt_displayed_from'])){
                unset($_SESSION['receipt_displayed_from']);
            };

            $sqlGetUserCartID = "SELECT id AS user_cart_id FROM pos_cart WHERE  user_id=$user_id";    
            $result = mysqli_query($conn,$sqlGetUserCartID);

            if ($result->num_rows != 0){
                $row = mysqli_fetch_array($result);
                $cart_id = $row['user_cart_id'];

            } else {
                $sqlInsertUserCart = "INSERT INTO pos_cart(user_id) VALUES ('$user_id')";
                if(!mysqli_query($conn,$sqlInsertUserCart)){
                    die("Something went wrong");
                };
                $cart_id = mysqli_insert_id($conn);  
            };

            $_SESSION['cart_id'] = $cart_id;

            $filter_str = "";
            $selected_discount = "";

            $sqlGetProductLines = "SELECT 
                                        pl.id AS line_id,
                                        p.name AS product_name,
                                        p.price,
                                        p.applicable_discounts,
                                        p.prescription_is_required,
                                        p.photo,
                                        pl.qty,
                                        p.id AS product_id,
                                        p.current_quantity AS max_quantity,
                                        pl.for_checkout,
                                        pl.line_price,
                                        pl.line_type
                                    FROM product_line pl
                                    INNER JOIN product p ON pl.product_id=p.id
                                ";
            
            $order_id = NULL;
            if (isset($_GET['order_id']) && ($_GET['order_id'] != '')){
                $order_id = $_GET['order_id'];
                $_SESSION['order_id'] = $order_id;

                $sqlGetOrderDetails = "SELECT
                                            reference_number, date_ordered, selected_discount
                                        FROM customer_order
                                        WHERE id=$order_id AND status='for_pickup'";
                
                $result = mysqli_query($conn,$sqlGetOrderDetails);
                if ($result->num_rows == 0){
                    $_SESSION["message_string"] = "Order not found, redirecting to POS";
                    $_SESSION["message_class"] = "info";
                    header("Location: index.php");
                    exit;
                };

                $row = mysqli_fetch_array($result);

                $selected_discount = $row['selected_discount'];
                $_SESSION['selected_discount'] = $selected_discount;
                
                $filter_str = " WHERE pl.order_id=$order_id AND
                                (line_type='order' OR (line_type='pos' AND pl.cart_id=$cart_id))
                            ";

            } else {
                $filter_str = " WHERE line_type='pos' AND pl.cart_id=$cart_id";
            };
            
            $sqlGetProductLines .= $filter_str;
            $product_lines = mysqli_query($conn,$sqlGetProductLines);
            
            $cart_products = array();

            $sqlGetProducts = "SELECT
                                    id AS product_id,
                                    name AS product_name
                                FROM product WHERE current_quantity > 0 ORDER BY id";

            $product_result = mysqli_query($conn,$sqlGetProducts);
            $product_list = array();
            $displayed_products = array();
            
            if ($product_result){
                while ($product_row = mysqli_fetch_assoc($product_result)){
                    $dictionary = [
                        "productID" => $product_row['product_id'],
                        "productName" => $product_row["product_name"]
                    ];
                    array_push($product_list, $dictionary);
                };
            };

            echo "
                <script>
                    let validProducts = ".json_encode($product_list).";
                </script>
            ";
        ?>

        <div class="container" style="margin-left: 15%">
            <div class="cart-left" style="width: 60%;">
                <div class="card-pos">
                    <div>
                        <label for="product-input">Search Product:</label>
                        <input type="text" id="product-input" class="product-input" placeholder="Type and press Enter" autocomplete="off">
                        <ul class="suggestions" id="suggestions-list"></ul>
                    </div>
                    
                    <h2>Product List</h2>
                    <div class="legends">
                        <span> <i class='fas fa-prescription' style='color: red;'></i> - Requires Prescription</span>
                    </div>
                    
                    <table id="productTable">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Discounted Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                while($data = mysqli_fetch_array($product_lines)){
                                    if ($data['line_type'] == 'order'){
                                        $is_selected = 'true';
                                    } elseif ($data['for_checkout'] == '1'){
                                        $is_selected = 'true';
                                    } else {
                                        $is_selected = 'false';
                                    };
                                    $displayed_products[] = $data['product_name'];
                                    $price = $data['line_price'];
                                    if (is_null($price)){
                                        $price = $data['price'];
                                    };
                                    $dictionary = [
                                        "lineID" => $data['line_id'],
                                        "price" => $price,
                                        "discountedPrice" => $price,
                                        "quantity" => $data['qty'],
                                        "selected" => $is_selected,
                                        "applicableDiscounts" => $data['applicable_discounts'],
                                    ];
                                    array_push($cart_products, $dictionary);
                            ?>
                                
                                <tr>
                                <td><input type="checkbox" class="product-checkbox" data-index="<?php echo $data['line_id']; ?>" <?php if ($is_selected == 'true'){echo 'checked';};?>></td>
                                <td>
                                    <img src="<?php echo $data['photo'];?>" style="width:50px; height:50px"><br/>
                                    <?php echo $data['product_name'];?>
                                    <?php if ($data['prescription_is_required'] == '1') {echo "<i class='button-icon fas fa-prescription' title='Prescription is required' style='color: red !important;'></i>";} ?>
                                </td>
                                <td class="price">₱<?php echo $price; ?></td>
                                <td class="discounted-price">₱<?php echo $price; ?></td>
                                <td><input type="number" max="<?php echo $data['max_quantity']; ?>" value="<?php echo $data['qty'];?>" class="quantity" data-index="<?php echo $data['line_id']; ?>" min="1" oninput="adjustInputValue(this)"></td>
                                <td class="total">₱0</td>
                                <td>
                                    <?php
                                        if ($data['line_type'] == 'pos'){
                                    ?>
                                    <a><i class="button-icon fas fa-trash" onclick="showYesNoModal('remove_line-<?php echo $data['line_id']; ?>','Are you sure you want to remove this product in your Cart?')" title="Remove"></i></a>
                                    <?php
                                        }
                                    ?>
                                </td>
                            </tr>
                            <?php
                                };
                                echo "
                                    <script>
                                        let products = ".json_encode($cart_products).";
                                        let displayedProducts = ".json_encode($displayed_products).";
                                    </script>
                                ";
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Summary -->
            <div class="cart-right" style="width: 38%; margin-left: 10px">
                <div class="card-pos">
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
                    <h2>Summary</h2>
                    <?php
                        if ($order_id){
                            ?>
                            <p>Order Reference Number: <?php echo $row['reference_number']; ?></p>
                            <p>Date Ordered: <?php
                                $date = new DateTime($row["date_ordered"]);

                                // Format the DateTime object to 'Y-m-d h:i A' (12-hour format with AM/PM)
                                $formattedDate = $date->format('F j, Y h:i A');
                                echo $formattedDate;
                            ?></p>
                            <?php
                        }
                    ?>
                    
                    <div class="discount">
                        <label for="discount">Discount</label>
                        <select id="discount">
                            <option value="No Discount_0" <?php if(isset($_SESSION['selected_discount']) && $_SESSION['selected_discount'] == ''){echo 'selected';}?>>No Discount</option>
                            <option value="Senior Citizen_0.2" <?php if(isset($_SESSION['selected_discount']) && $_SESSION['selected_discount'] == 'Senior Citizen'){echo 'selected';}?>>Senior Citizen (20%)</option>
                            <option value="Person With Disabilities_0.2" <?php if(isset($_SESSION['selected_discount']) && $_SESSION['selected_discount'] == 'Person With Disabilities'){echo 'selected';}?>>Person With Disabilities (20%)</option>
                        </select>
                        <?php if (isset($_SESSION['selected_discount'])){ unset($_SESSION['selected_discount']); } ?>
                    </div>
                    <div id="summary">
                        <p>Subtotal: ₱<span id="subtotal">0</span></p>
                        <p>Discount: ₱<span id="discountAmount">0</span></p>
                        <p>Total: ₱<span id="total">0</span></p>
                    </div>

                    <form action="process.php" method="POST">
                        <input type="hidden" name="selected_ids" id="selected_ids">
                        <input type="hidden" name="selected_items_qty" id="selected_items_qty">
                        <input type="hidden" name="selected_discount" id="selected_discount">
                        <button class="disabled" type="submit" name="action" value="checkout_cart" id="checkout" disabled>Next</button>
                    </form>
                    
                </div>
            </div>
        </div>
        
        <script src="script.js"></script>                     

        <script>
            window.onload = function() {
                setActivePage("nav_pos");
            };
        </script>
    </body>
</html>