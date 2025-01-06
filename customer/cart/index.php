<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHARMANEST ESSENTIAL</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet" href="../styles.css">
        <script src="../../assets/scripts/common_fx.js"></script>
    </head>
    <body class="body">
        <?php
            session_start();
            include '../components/unauth_redirection.php';

            include('../../utils/connect.php');
            $base_url = $_SESSION["BASE_URL"];

            $user_id = $_SESSION['user_id'];

            $sqlGetProductLines = "SELECT 
                                        pl.id AS line_id,
                                        m.name AS medicine_name,
                                        price,
                                        applicable_discounts,
                                        prescription_is_required,
                                        photo,
                                        qty,
                                        m.id AS medicine_id
                                    FROM product_line pl
                                    INNER JOIN customer_cart cc ON pl.cart_id=cc.id
                                    INNER JOIN medicine m ON pl.medicine_id=m.id
                                    INNER JOIN customer c ON cc.customer_id=c.id
                                    WHERE c.user_id=$user_id
            ";
            $product_lines = mysqli_query($conn,$sqlGetProductLines);
            if ($product_lines->num_rows == 0){
                $_SESSION["message_string"] = "Cart is empty!";
                $_SESSION["message_class"] = "error";
                header("Location:../home/index.php");
            };

            $products = array();
        ?>
        
        <?php include '../components/navbar.php'; ?>

        <?php include '../components/yesNo_modal.php'; ?>  

        <div class="container">
        <!-- Product Table -->
            <div class="cart-left" style="width: 50%;">
                <div class="card">
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
                                    $dictionary = [
                                        "lineID" => $data['line_id'],
                                        "price" => $data['price'],
                                        "discountedPrice" => $data['price'],
                                        "quantity" => $data['qty'],
                                        "selected" => 'false',
                                        "applicableDiscounts" => $data['applicable_discounts'],
                                        "prescriptionIsRequired" => $data['prescription_is_required'],
                                    ];
                                    array_push($products, $dictionary);
                            ?>
                                
                                <tr>
                                <td><input type="checkbox" class="product-checkbox" data-index="<?php echo $data['line_id']; ?>"></td>
                                <td>
                                    <img src="<?php echo $data['photo'];?>" style="width:50px; height:50px"><br/>
                                    <?php echo $data['medicine_name'];?>
                                    <?php if ($data['prescription_is_required'] == '1') {echo "<i class='button-icon fas fa-prescription' title='Attach Prescription' style='color: red !important;'></i>";} ?>
                                </td>
                                <td class="price">₱<?php echo $data['price'];?></td>
                                <td class="discounted-price">₱<?php echo $data['price'];?></td>
                                <td><input type="number" value="<?php echo $data['qty'];?>" class="quantity" data-index="<?php echo $data['line_id']; ?>" min="1"></td>
                                <td class="total">₱0</td>
                                <td>
                                    <a><i class="button-icon fas fa-trash" onclick="showYesNoModal('remove_line-<?php echo $data['line_id']; ?>')" title="Remove"></i></a>
                                </td>
                            </tr>
                            <?php
                                };
                                echo "
                                    <script>
                                        let products = ".json_encode($products).";
                                    </script>
                                ";
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Summary -->
            <div class="cart-right">
                <div class="card">
                    <h2>Summary</h2>
                    <div class="discount">
                        <label for="discount">Discount</label>
                        <select id="discount">
                            <option value="No Discount_0">No Discount</option>
                            <option value="Senior Citizen_0.2">Senior Citizen (20%)</option>
                            <option value="Person With Disabilities_0.2">Person With Disabilities (20%)</option>
                        </select>
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
                        <button class="disabled" type="submit" name="action" value="checkout_cart" id="checkout" disabled>Checkout</button>
                    </form>
                    
                </div>
            </div>
        </div>

        <script src="script.js"></script>

        <script src="../script.js"></script>

        <script>
            window.onload = function() {
                setActivePage("nav_cart");
            };
        </script>
    </body>
</html>