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
            $current_page_title = "order details";
            include '../../components/top_nav.php';
        ?> 
        <div class="content" style="margin-top: 10%;">
        <?php
            include($doc_root.'/utils/connect.php');
            if (isset($_GET['order_id'])) {
                $order_id = $_GET['order_id'];
                
                $sqlGetCustomerOrder = "SELECT
                                        co.date_ordered,
                                        co.status,
                                        co.reference_number,
                                        co.selected_discount,
                                        co.remarks,
                                        u.email,
                                        c.first_name,
                                        c.last_name,
                                        c.contact_number
                                    FROM customer_order co
                                    INNER JOIN customer c ON co.customer_id=c.id
                                    INNER JOIN user u ON c.user_id=u.id
                                    WHERE co.id=$order_id";

                $order_result = mysqli_query($conn,$sqlGetCustomerOrder);
                if ($order_result->num_rows == 0){
                    header("Location:../../../page/404.php");
                };

                $row = mysqli_fetch_array($order_result);
                $_SESSION['customer_email'] = $row['email'];
                $_SESSION["customer_mobile_number"] = $row['contact_number'];
                $_SESSION['order_reference_number'] = $row['reference_number'];
                $sqlGetProductLines = "SELECT 
                                        p.name AS product_name,
                                        price,
                                        applicable_discounts,
                                        prescription_is_required,
                                        photo,
                                        pl.line_price,
                                        pl.line_discount,
                                        qty,
                                        cp.prescription_photo,
                                        cp.reference_name AS prescription_name
                                    FROM product_line pl
                                    INNER JOIN product p ON pl.product_id=p.id
                                    INNER JOIN customer_cart cc ON pl.cart_id=cc.id
                                    LEFT JOIN product_prescription mp ON cc.customer_id=mp.customer_id
                                    LEFT JOIN customer_prescription cp ON mp.prescription_id=cp.id
                                    WHERE pl.order_id=$order_id AND pl.line_type IN ('order', 'transaction')
                ";
                
                $product_lines = mysqli_query($conn,$sqlGetProductLines);

                $selected_discount = $row['selected_discount'];
                
            } else {
                header("Location:../list/index.php");
            };

        ?>

        <div class="custom-modal" id="cancelOrderModal" style="z-index: 1000;">
            <div class="modal-content">
                <h1 id="modal_header">Order</h1>
                <p id="modal_message">Are you sure you want to cancel this Order?</p>
                <form action="process.php" method="POST">
                    <input id="order_id" type="hidden" name="order_id">
                    <div class="input-group">
                        <label for="remarks">Reason for Cancellation</label>
                        <input type="text" name="remarks" required>
                    </div>
                    <button id="button_yes" type="submit" name="action" class="modal-button yes-button" value="cancel_order">Submit</button>
                    <button type="button" class="modal-button no-button" onclick="onNo('cancelOrderModal')">Close</button>
                </form>
            </div>
        </div>
        
        <div class="container" style="margin-left: 15%">
            <div class="cart-left" style="width: 62%;">
                <div class="card-order">
                    <h2>Product List</h2>
                    <div class="legends">
                        <span> <i class='fas fa-prescription' style='color: red;'></i> - Requires Prescription</span>
                    </div>
                    <table id="productTable">
                        <thead>
                            <tr>
                                <?php if ($row['status'] == 'preparing'){ ?> 
                                <th>Ready</th>
                                <?php } ?>
                                <th>Product</th>
                                <?php if ($row['status'] == 'preparing'){ ?> 
                                <th>Prescription</th>
                                <?php } ?>
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
                                    
                                    if (!is_null($data['line_discount'])){
                                        $discount_rate = $data['line_discount']/100;
                                    };

                                    $line_discount = $price * (1 - $discount_rate);
                                    
                                    $subtotal += $line_subtotal;
                                    $total_discount += ($line_subtotal - ($line_discount * $data['qty']));

                            ?>
                                <tr>
                                <?php
                                    if ($row['status'] == 'preparing'){ 
                                        echo '<td><input type="checkbox" class="product-checkbox"></td>';
                                    };
                                ?>
                                <td>
                                    <img src="<?php echo $data['photo'];?>" style="width:50px; height:50px"><br/>
                                    <?php echo $data['product_name'];?>
                                    <?php if ($data['prescription_is_required'] == '1') {echo "<i class='button-icon fas fa-prescription' title='Prescription is required' style='color: red !important;'></i>";} ?>
                                </td>
                                <?php
                                    if ($row['status'] == 'preparing'){
                                        if ($data['prescription_is_required'] == '1'){
                                            ?>
                                            <td>
                                            <img class="prescription_photo" src="<?php echo $data['prescription_photo'];?>" style="width:50px; height:50px" onclick="openFullscreen(this)"><br/>
                                            <?php echo $data['prescription_name'];?>
                                            </td>
                                            <?php
                                        } else {
                                            echo "<td>N/A</td>";
                                        };
                                    };
                                ?> 
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

            <!-- Details -->
            <div class="cart-right" style="width: 36%;">
                <div class="card-order">
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
                    <h2>Details</h2>
                    <div id="summary">
                        <p><b>Order Reference Number</b>: <?php echo $row['reference_number']; ?></p>
                        <p><b>Customer Name</b>: <?php echo $row['first_name']." ".$row['last_name']; ?></p>
                        <p><b>Status</b>: <?php echo ucwords(str_replace("_", " ", $row['status'])); ?></p>
                        <?php
                        if ($row['status'] == 'cancelled'){
                        ?>
                        <p><b>Remarks</b>: <?php echo $row['remarks']; ?></p>
                        <?php
                        }
                        ?>
                        <p><b>Date Ordered</b>: <?php
                            $date = new DateTime($row["date_ordered"]);

                            // Format the DateTime object to 'Y-m-d h:i A' (12-hour format with AM/PM)
                            $formattedDate = $date->format('F j, Y h:i A');
                            echo $formattedDate;
                        ?></p>

                        <p><b>Discount Type</b>:
                            <?php
                                if ($selected_discount){
                                    echo $selected_discount;
                                } else {
                                    echo "None";
                                };
                            ?>
                        </p>
                        <p><b>Subtotal</b>: ₱<span id="subtotal"><?php echo $subtotal; ?></span></p>
                        <p><b>Discount</b>: ₱<span id="discountAmount"><?php echo $total_discount; ?></span></p>
                        <p><b>Total</b>: ₱<span id="total"><?php echo $total; ?></span></p>
                    </div>

                    <form action="process.php" method="POST">
                        <input id="order_id" type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                        <?php if ($row['status'] == 'placed'){ ?>
                        <button class="action_button next_status" type="submit" name="action" value="preparing">Preparing</button>
                        <?php }?>
                        <?php if ($row['status'] == 'preparing'){ ?>
                        <button id="ready_for_pickup" class="action_button next_status disabled" type="submit" name="action" disabled value="for_pickup">Ready for Pick-Up</button>
                        <?php } ?>
                        <?php if ($row['status'] == 'for_pickup'){ ?>
                        <button class="action_button next_status" type="button" name="action" onclick="redirectToPOSPage()">Next</button>
                        <?php } ?>
                        <button class="action_button<?php if (in_array($row['status'], ['cancelled','picked_up'])){echo ' disabled';} ?>" type="button" name="action" value="cancel_order" id="cancel_order" <?php if (in_array($row['status'], ['cancelled','picked_up'])){echo 'disabled';} ?> onclick="showCancelOrderModal(<?php echo '\''.$order_id.'\',\''.$row['reference_number'].'\''; ?>)">Cancel Order</button>
                    </form>
                    
                </div>
            </div>

        </div>
        </div>
        
        <script src="script.js"></script>

        <script>
            window.onload = function() {
                setActivePage("nav_order");
            };

            function redirectToPOSPage() {
                window.location.href = '../../pos/index.php?order_id=' + <?php echo $order_id; ?>;
            };

        </script>
    </body>
</html>