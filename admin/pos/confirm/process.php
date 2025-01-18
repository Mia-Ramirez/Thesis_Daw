<!DOCTYPE html>
<?php
    session_start();
    $doc_root = $_SESSION["DOC_ROOT"];
    if (!isset($_SESSION['cart_id'])){
        header("Location: index.php");
        exit;
    };
    $cart_id = $_SESSION['cart_id'];
    
    $order_id = NULL;
    if (isset($_SESSION['order_id']) && ($_SESSION['order_id'] != '')){
        $order_id = $_SESSION['order_id'];
        unset($_SESSION['order_id']);
    };
    
    if (!isset($_SESSION['selected_discount'])){
        header("Location: ../index.php");
        exit;
    };
    $selected_discount = $_SESSION['selected_discount'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($_POST['action'] == 'confirm_transaction'){
            $user_id = $_SESSION['user_id'];

            include($doc_root.'/utils/connect.php');

            $amount= mysqli_real_escape_string($conn, $_POST['amount']);

            $sqlGetEmployeeID = "SELECT id FROM employee WHERE user_id=$user_id";

            $employee_result = mysqli_query($conn,$sqlGetEmployeeID);
            if ($employee_result->num_rows == 0){
                header("Location:../../../../page/404.php");
            };

            $row = mysqli_fetch_array($employee_result);
            $employee_id = $row['id'];

            $sqlGetProductLines = "SELECT
                                        pl.id AS product_line_id,
                                        pl.qty,
                                        pl.product_id,
                                        -- p.cost AS line_cost,
                                        p.price AS line_price
                                    FROM product_line pl
                                    INNER JOIN product p ON pl.product_id=p.id";

            $filter_str = "";
            if ($order_id){
                $sqlCheckOrder = "SELECT id
                                        FROM customer_order
                                        WHERE id=$order_id AND status='for_pickup'";
                
                $result = mysqli_query($conn,$sqlCheckOrder);
                if ($result->num_rows == 0){
                    $_SESSION["message_string"] = "Order not found, redirecting to POS";
                    $_SESSION["message_class"] = "info";
                    header("Location: ../index.php");
                    exit;
                };
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

            $sqlGetNextSRNo = "SELECT LPAD(COUNT(*) + 1, 6, '0') AS next_number FROM `transaction`";
            $result = mysqli_query($conn,$sqlGetNextSRNo);
            $row = mysqli_fetch_array($result);
            
            $or_number = "SR#".$row['next_number'];
            $tr_number = "TR#".date('Ymd-His');

            if ($selected_discount == 'No Discount'){
                $selected_discount = NULL;
            };

            if ($order_id){
                $sqlInsertTransaction = "INSERT INTO
                                            transaction(employee_id, receipt_reference, selected_discount, reference_number, amount_paid, order_id)
                                            VALUES ('$employee_id','$or_number','$selected_discount','$tr_number','$amount', '$order_id')";
            } else {
                $sqlInsertTransaction = "INSERT INTO
                                            transaction(employee_id, receipt_reference, selected_discount, reference_number, amount_paid)
                                            VALUES ('$employee_id','$or_number','$selected_discount','$tr_number','$amount')";
            };

            if(!mysqli_query($conn,$sqlInsertTransaction)){
                die("Something went wrong");
            };
            $transaction_id = mysqli_insert_id($conn);

            while($data = mysqli_fetch_array($product_lines)){
                $line_id = $data['product_line_id'];
                // $line_cost = $data['line_cost'];
                $line_price = $data['line_price'];
                $product_id = $data['product_id'];

                if ($order_id){
                    $sqlTransferLineToTransaction = "UPDATE product_line SET cart_id=NULL, pos_cart_id=NULL, for_checkout=0, transaction_id=$transaction_id, line_type='transaction', line_price='$line_price', order_id='$order_id' WHERE id=$line_id";
                } else {
                    $sqlTransferLineToTransaction = "UPDATE product_line SET cart_id=NULL, pos_cart_id=NULL, for_checkout=0, transaction_id=$transaction_id, line_type='transaction', line_price='$line_price' WHERE id=$line_id";
                };
                if(!mysqli_query($conn,$sqlTransferLineToTransaction)){
                    die("Something went wrong");
                };

                $history_remarks = "Sold: ".$data['qty']." quantity ".$or_number;
                $sqlInsertProductHistory = "INSERT INTO history(object_type, object_id, remarks, user_id) VALUES ('product','$product_id','$history_remarks','$user_id')";
                if(!mysqli_query($conn,$sqlInsertProductHistory)){
                    die("Something went wrong");
                };
                
                $sqlGetProductStock = "SELECT current_quantity FROM product WHERE id=$product_id";
                $product_result = mysqli_query($conn,$sqlGetProductStock);
                $row = mysqli_fetch_array($product_result);

                $current_quantity = $row['current_quantity'] - $data['qty'];
                $sqlUpdateProductStock = "UPDATE product SET current_quantity='$current_quantity' WHERE id = $product_id";
                if(!mysqli_query($conn,$sqlUpdateProductStock)){
                    die("Something went wrong");
                };
            };

            $history_remarks = "Transacted: ".$product_lines->num_rows." item(s) ".$tr_number;
            $sqlInsertTransactionHistory = "INSERT INTO history(object_type, object_id, remarks, user_id) VALUES ('transaction','$transaction_id','$history_remarks','$user_id')";
            if(!mysqli_query($conn,$sqlInsertTransactionHistory)){
                die("Something went wrong");
            };

            
            if ($order_id){
                $sqlUpdateOrderStatus = "UPDATE customer_order SET status='picked_up' WHERE id=$order_id";
                if(!mysqli_query($conn,$sqlUpdateOrderStatus)){
                    die("Something went wrong");
                };

                $history_remarks = "Moved to \"Picked-up\"";
                $sqlInsertOrderHistory = "INSERT INTO history(object_type, object_id, remarks, user_id) VALUES ('order','$order_id','$history_remarks','$user_id')";
                if(!mysqli_query($conn,$sqlInsertOrderHistory)){
                    die("Something went wrong");
                };
            };
            
            unset($_SESSION['selected_discount']);

            $_SESSION['receipt_displayed_from'] = 'pos';
            header("Location:../receipt/index.php?transaction_id=".$transaction_id);
            exit;
        };
    };
?>