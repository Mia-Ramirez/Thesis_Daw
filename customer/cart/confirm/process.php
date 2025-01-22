<!DOCTYPE html>
<?php
    session_start();
    $doc_root = $_SESSION["DOC_ROOT"];
    $customer_id = $_SESSION['customer_id'];
    $user_id = $_SESSION['user_id'];

    $selected_discount = $_SESSION['selected_discount'];
    unset($_SESSION["selected_discount"]);

    include($doc_root.'/utils/connect.php');

    require($doc_root.'/apps/PHPMailer/src/Exception.php');
    require($doc_root.'/apps/PHPMailer/src/PHPMailer.php');
    require($doc_root.'/apps/PHPMailer/src/SMTP.php');
    
    include($doc_root.'/utils/email.php');
    include($doc_root.'/utils/common_fx_and_const.php');

    $sqlGetProductLines = "SELECT pl.id AS product_line_id
                                    FROM product_line pl
                                    INNER JOIN customer_cart cc ON pl.cart_id=cc.id
                                    WHERE cc.customer_id=$customer_id AND pl.for_checkout=1 AND line_type='cart'
                        ";
    $result = mysqli_query($conn, $sqlGetProductLines);

    // Fetch all results at once as an associative array
    if ($result) {
        $rows = $result->fetch_all(MYSQLI_ASSOC); // Fetch all rows as an associative array
    
        // Extract just the 'id' values into a separate array
        $ids = array_column($rows, 'product_line_id');
    
        // Optionally, print the IDs to verify
        $reference_number = date('YmdHis')."-".$customer_id;
        $sqlInsertCustomerOrder = "INSERT INTO customer_order(customer_id, status, reference_number, selected_discount) VALUES ('$customer_id','placed','$reference_number','$selected_discount')";
        if(!mysqli_query($conn,$sqlInsertCustomerOrder)){
            die("Something went wrong");
        };
        $order_id = mysqli_insert_id($conn);

        $idsString = implode(',', $ids);
        $sqlTransferCartLinesToOrder = "UPDATE product_line SET order_id = '$order_id', for_checkout=0, line_type='order' WHERE id IN ($idsString)";
        if(!mysqli_query($conn,$sqlTransferCartLinesToOrder)){
            die("Something went wrong");
        };
        
        $sqlInsertOrderHistory = "INSERT INTO history(object_type, object_id, remarks, user_id) VALUES ('order','$order_id','Order Placed','$user_id')";
        if(!mysqli_query($conn,$sqlInsertOrderHistory)){
            die("Something went wrong");
        };

        $sqlGetOrder = "SELECT
                            u.email,
                            co.reference_number 
                        FROM customer_order co
                        INNER JOIN customer c ON co.customer_id=c.id
                        INNER JOIN user u ON c.user_id=u.id
                        WHERE co.id=$order_id AND co.status='placed'
        ";
        $result = mysqli_query($conn,$sqlGetOrder);
        $row = mysqli_fetch_array($result);
        $customer_email = $row['email'];
        $order_reference_number = $row['reference_number'];

        if (($enable_send_email == "1") && (!is_null($customer_email))){
            send_email(
                $customer_email,
                "Order Updates",
                "Hello!<br/>Your Order ".$order_reference_number." has been placed.<br/>" 
            );
        };

        $_SESSION["message_string"] = "Order successfully placed!";
        $_SESSION["message_class"] = "info";
        header("Location:../../home/index.php");
        
    } else {
        echo "Error: " . $mysqli->error;
    };

?>