<?php
    session_start();
    $base_url = $_SESSION["BASE_URL"];
    $doc_root = $_SESSION["DOC_ROOT"];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHARMANEST ESSENTIAL</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet" href="../../styles.css">
        <script src="<?php echo $base_url;?>assets/scripts/common_fx.js"></script>
    </head>
    <body class="body">

        <?php include '../../components/unauth_redirection.php'; ?>

        <?php include '../../components/navbar.php'; ?>  

        <?php
            include($doc_root.'/utils/connect.php');
            
            $customer_id = $_SESSION['customer_id'];
            $from_prescription_page = '0';
            if (isset($_SESSION['from_prescription_page']) && ($_SESSION['from_prescription_page'] == '1')){
                $from_prescription_page = '1';
                unset($_SESSION['from_prescription_page']);
            };

            $sqlGetProductLines = "SELECT
                                    p.name AS product_name,
                                    price,
                                    applicable_discounts,
                                    prescription_is_required,
                                    photo,
                                    qty,
                                    selected_discount,
                                    pl.line_price,
                                    prescription_id,
                                    pl.id AS line_id
                                FROM product_line pl
                                INNER JOIN customer_cart cc ON pl.cart_id=cc.id
                                LEFT JOIN product_prescription mp ON cc.customer_id=mp.customer_id
                                INNER JOIN product p ON pl.product_id=p.id
                                WHERE cc.customer_id=$customer_id AND pl.for_checkout=1 AND line_type='cart'
            ";
            
            $product_lines = mysqli_query($conn,$sqlGetProductLines);
            if ($product_lines->num_rows == 0){
                $_SESSION["message_string"] = "Cart is empty!";
                $_SESSION["message_class"] = "danger";
                header("Location:../../home/index.php");
            };

            $selected_discount = NULL;
            $products = array();

        ?>
        
        <div class="container" style="margin-top: 10%;">
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
                                <th>Product</th>
                                <th>Price</th>
                                <th>Discounted Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $subtotal = 0;
                                $total_discount = 0;

                                while($data = mysqli_fetch_array($product_lines)){
                                    $dictionary = [
                                        "lineID" => $data['line_id'],
                                        "price" => $data['price'],
                                        "discountedPrice" => $data['price'],
                                        "quantity" => $data['qty'],
                                        "selected" => '1',
                                        "applicableDiscounts" => $data['applicable_discounts'],
                                        "prescriptionIsRequired" => $data['prescription_is_required'],
                                    ];
                                    array_push($products, $dictionary);
                                    error_log("HERE: ".$data['prescription_is_required']." | ".$from_prescription_page);
                                    if ($data['prescription_is_required'] == '1' && ($from_prescription_page == '0')){
                                        header("Location:../prescription/index.php");
                                    };

                                    if ($data['selected_discount']){
                                        $selected_discount = $data['selected_discount'];
                                    };

                                    $line_subtotal = $data['price'] * $data['qty'];

                                    $discount_rate = 0;
                                    if ($selected_discount && ($selected_discount == $data['applicable_discounts'] || $data['applicable_discounts'] == 'Both')){
                                        $discount_rate = 0.2;
                                    };
                                    $price = $data['line_price'];
                                    if (is_null($price)){
                                        $price = $data['price'];
                                    };
                                    $line_discount = $price * (1 - $discount_rate);
                                    
                                    $subtotal += $line_subtotal;
                                    $total_discount += ($line_subtotal - ($line_discount * $data['qty']));

                            ?>
                                <tr>
                                <td>
                                    <img src="<?php echo $data['photo'];?>" style="width:50px; height:50px"><br/>
                                    <?php echo $data['product_name'];?>
                                    <?php if ($data['prescription_is_required'] == '1') {echo "<i class='button-icon fas fa-prescription' title='Prescription is required' style='color: red !important;'></i>";} ?>
                                </td>
                                <td class="price">₱<?php echo $price; ?></td>
                                <td class="discounted-price">₱<?php echo $line_discount;?></td>
                                <td><?php echo $data['qty'];?></td>
                                <td class="total">₱<?php echo $line_subtotal;?></td>
                            </tr>
                            <?php
                                };
                                $total = $subtotal - $total_discount;
                                
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
                    <div id="summary">
                        <div class="discount">
                            <label for="discount">Discount</label>
                            <select id="discount">
                                <option value="No Discount_0" <?php if($selected_discount && $selected_discount == ''){echo 'selected';}?>>No Discount</option>
                                <option value="Senior Citizen_0.2" <?php if($selected_discount && $selected_discount == 'Senior Citizen'){echo 'selected';}?>>Senior Citizen (20%)</option>
                                <option value="Person With Disabilities_0.2" <?php if($selected_discount && $selected_discount == 'Person With Disabilities'){echo 'selected';}?>>Person With Disabilities (20%)</option>
                            </select>
                        </div>
                        <p>Subtotal: ₱<span id="subtotal"><?php echo $subtotal; ?></span></p>
                        <p>Discount: ₱<span id="discountAmount"><?php echo $total_discount; ?></span></p>
                        <p>Total: ₱<span id="total"><?php echo $total; ?></span></p>
                    </div>

                    <form action="process.php" method="POST">
                        <input type="hidden" name="selected_discount" id="selected_discount">
                        <button class="disabled" type="submit" name="action" value="confirm_order" id="confirm_order" disabled>Confirm</button>
                    </form>
                    
                </div>
            </div>
        </div>

        <script src="script.js"></script>
        <script src="../../script.js"></script>
        
        <script>
            window.onload = function() {
                setActivePage("nav_cart");
            };

            const button = document.getElementById('confirm_order');
           
            // Function to check if user has reached the bottom of the page
            function checkScrollPosition() {
                const scrollPosition = window.innerHeight + window.scrollY;
                const pageHeight = document.documentElement.scrollHeight;
                
                // If user has reached the bottom, enable the button
                if (scrollPosition >= pageHeight) {
                    button.disabled = false;
                }
            }

            // Attach the scroll event listener to the window
            window.addEventListener('scroll', checkScrollPosition);
            checkScrollPosition();

        </script>
    </body>
</html>