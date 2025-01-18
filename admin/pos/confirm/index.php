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
        <link rel="stylesheet" type="text/css" href="../../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="<?php echo $base_url;?>assets/scripts/common_fx.js"></script>
        <?php include '../../components/title.php'; ?>
    </head>

    <body>
        <?php include '../../components/unauth_redirection.php'; ?>

        <?php include '../../components/side_nav.php'; ?>
        
        <?php
            $current_page_title = "point of sale";
            include '../../components/top_nav.php';
        ?> 

        <?php
            if (!isset($_SESSION['cart_id'])){
                header("Location: ../index.php");
                exit;
            };
            $cart_id = $_SESSION['cart_id'];

            if (!isset($_SESSION['selected_discount'])){
                header("Location: ../index.php");
                exit;
            };
            $selected_discount = $_SESSION['selected_discount'];
            if ($selected_discount == "No Discount"){
                $selected_discount = NULL;
            };

            include($doc_root.'/utils/connect.php');

            $sqlGetProductLines = "SELECT 
                                        p.name AS product_name,
                                        p.price,
                                        p.applicable_discounts,
                                        p.prescription_is_required,
                                        p.photo,
                                        pl.line_price,
                                        pl.qty
                                    FROM product_line pl
                                    INNER JOIN product p ON pl.product_id=p.id
            ";

            $filter_str = "";
            $order_id = NULL;
            if (isset($_GET['order_id']) && ($_GET['order_id'] != '')){
                $order_id = $_GET['order_id'];
                $sqlGetOrderDetails = "SELECT reference_number, date_ordered
                                        FROM customer_order
                                        WHERE id=$order_id AND status='for_pickup'";
                
                $result = mysqli_query($conn,$sqlGetOrderDetails);
                if ($result->num_rows == 0){
                    $_SESSION["message_string"] = "Order not found, redirecting to POS";
                    $_SESSION["message_class"] = "info";
                    header("Location: ../index.php");
                    exit;
                };

                $row = mysqli_fetch_array($result);
            };
            
            if ($order_id){
                $filter_str = " WHERE pl.order_id=$order_id AND for_checkout='1'";
            } else {
                $filter_str = " WHERE line_type='pos' AND for_checkout='1' AND pl.pos_cart_id=$cart_id";
            };
        

            $sqlGetProductLines .= $filter_str;
            $product_lines = mysqli_query($conn,$sqlGetProductLines);
            if ($product_lines->num_rows == 0){
                $_SESSION["message_string"] = "Cart is empty!";
                $_SESSION["message_class"] = "danger";
                header("Location:../index.php");
            };
            $_SESSION['order_id'] = $order_id;
        ?>
        
        <div class="container" style="margin-left: 15%">
        <!-- Product Table -->
            <div class="cart-left" style="width: 50%;">
                <div class="card-pos">
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
                                    $price = $data['line_price'];
                                    if (is_null($price)){
                                        $price = $data['price'];
                                    };
                                    $line_subtotal = $price * $data['qty'];

                                    $discount_rate = 0;
                                    if ($selected_discount && ($selected_discount == $data['applicable_discounts'] || $data['applicable_discounts'] == 'Both')){
                                        $discount_rate = 0.2;
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
                                <td class="price">₱<?php echo $price;?></td>
                                <td class="discounted-price">₱<?php echo $line_discount;?></td>
                                <td><?php echo $data['qty'];?></td>
                                <td class="total">₱<?php echo $line_subtotal;?></td>
                            </tr>
                            <?php
                                };
                                $total = $subtotal - $total_discount;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Summary -->
            <div class="cart-right">
                <div class="card-pos">
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
                    <div id="summary">
                        <p>Discount Type:
                            <?php
                                if ($selected_discount){
                                    echo $selected_discount;
                                } else {
                                    echo "None";
                                };
                            ?>
                        </p>
                        <p>Subtotal: ₱<span id="subtotal"><?php echo $subtotal; ?></span></p>
                        <p>Discount: ₱<span id="discountAmount"><?php echo $total_discount; ?></span></p>
                        <p>Total: ₱<span id="total">
                            <?php
                                echo $total;
                                $_SESSION['total'] = $total;
                            ?></span>
                        </p>
                    </div>

                    <form action="process.php" method="POST">
                        <div>
                            <label for="amount">Amount:</label>
                            <input type="number" step="0.01" id="amount" name="amount" min="<?php echo $total; ?>" required>
                            <p>Change: ₱<span id="change"></span></p>
                        </div>
                        <button type="submit" name="action" value="confirm_transaction" id="confirm_transaction" disabled style="background-color: gray">Confirm</button>
                    </form>
                    
                </div>
            </div>
        </div>

        <script src="script.js"></script>       

        <script>
            window.onload = function() {
                setActivePage("nav_pos");
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