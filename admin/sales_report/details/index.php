<?php
    session_start();
    $base_url = $_SESSION["BASE_URL"];
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
            $current_page_title = "sales details";
            include '../../components/top_nav.php';
        ?> 

        <?php
            include('../../../utils/connect.php');
            if (isset($_GET['transaction_id'])) {
                $transaction_id = $_GET['transaction_id'];
                
                $sqlGetTransaction = "SELECT
                                        co.reference_number AS order_reference,
                                        co.date_ordered,
                                        t.transaction_date,
                                        t.selected_discount,
                                        e.first_name,
                                        e.last_name,
                                        t.receipt_reference,
                                        t.reference_number,
                                        co.id AS order_id
                                    FROM transaction t
                                    LEFT JOIN customer_order co ON t.order_id=co.id
                                    INNER JOIN employee e ON t.employee_id=e.id
                                    WHERE t.id=$transaction_id";

                $transaction_result = mysqli_query($conn,$sqlGetTransaction);
                if ($transaction_result->num_rows == 0){
                    header("Location:../../../page/404.php");
                };

                $row = mysqli_fetch_array($transaction_result);

                $sqlGetProductLines = "SELECT 
                                        p.name AS product_name,
                                        price,
                                        applicable_discounts,
                                        prescription_is_required,
                                        photo,
                                        qty
                                    FROM product_line pl
                                    INNER JOIN product p ON pl.product_id=p.id
                                    WHERE pl.transaction_id=$transaction_id
                ";
                $product_lines = mysqli_query($conn,$sqlGetProductLines);

                $selected_discount = $row['selected_discount'];
                
            } else {
                header("Location:../list/index.php");
            };

        ?>

        
        
        <div class="container" style="margin-left: 15%">
            <div class="cart-left" style="width: 50%;">
                <div class="card-order">
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
                                    
                                    $line_subtotal = $data['price'] * $data['qty'];

                                    $discount_rate = 0;
                                    if ($selected_discount && ($selected_discount == $data['applicable_discounts'] || $data['applicable_discounts'] == 'Both')){
                                        $discount_rate = 0.2;
                                    };

                                    $line_discount = $data['price'] * (1 - $discount_rate);
                                    
                                    $subtotal += $line_subtotal;
                                    $total_discount += ($line_subtotal - ($line_discount * $data['qty']));

                            ?>
                                <tr>
                                <td>
                                    <img src="<?php echo $data['photo'];?>" style="width:50px; height:50px"><br/>
                                    <?php echo $data['product_name'];?>
                                    <?php if ($data['prescription_is_required'] == '1') {echo "<i class='button-icon fas fa-prescription' title='Prescription is required' style='color: red !important;'></i>";} ?>
                                </td>
                                <td class="price">₱<?php echo $data['price'];?></td>
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
            <div class="cart-right">
                <div class="card-order">
                    <h2>Details</h2>
                    <div id="summary">
                        <p>Transaction Number: <?php echo $row['reference_number']; ?></p>
                        <p>Receipt Number: <?php echo $row['receipt_reference']; ?></p>
                        <p>Transaction Date: <?php
                            $date = new DateTime($row["transaction_date"]);
                            $formattedDate = $date->format('F j, Y h:i A');
                            echo $formattedDate;
                        ?></p>
                        <p>Transacted By: <?php echo $row['first_name']." ".$row['last_name']; ?></p>
                        <?php
                            if (!is_null($row['order_id'])){
                                $date = new DateTime($row["date_ordered"]);

                                // Format the DateTime object to 'Y-m-d h:i A' (12-hour format with AM/PM)
                                $formattedDate = $date->format('F j, Y h:i A');
                                echo "<p>Order Reference Number: ".$row['order_reference']."</p>";
                                echo "<p>Date Ordered: ".$formattedDate."</p>";
                            }
                        ?>
                        

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
                        <p>Total: ₱<span id="total"><?php echo $total; ?></span></p>
                    </div>

                    
                    
                </div>
            </div>

        </div>
        
        <script src="script.js"></script>
        <script>
            window.onload = function() {
                setActivePage("nav_sales_report");
            };
        </script>
    </body>
</html>