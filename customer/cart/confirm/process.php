<!DOCTYPE html>
<?php
    session_start();
    $customer_id = $_SESSION['customer_id'];
    $selected_discount = $_SESSION['selected_discount'];
    unset($_SESSION["selected_discount"]);

    include('../../../utils/connect.php');

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
        print_r($ids);
        // customer_id 	date_ordered 	status 	reference_number 
        $reference_number = date('YmdHis')."-".$customer_id;
        $sqlInsertCustomerOrder = "INSERT INTO customer_order(customer_id , status , reference_number, selected_discount) VALUES ('$customer_id','submitted','$reference_number', '$selected_discount')";
        if(!mysqli_query($conn,$sqlInsertCustomerOrder)){
            die("Something went wrong");
        };
        $order_id = mysqli_insert_id($conn);

        $idsString = implode(',', $ids);
        $sqlTransferCartLinesToOrder = "UPDATE product_line SET cart_id = NULL, order_id = '$order_id', for_checkout=0, line_type='order' WHERE id IN ($idsString)";
        if(!mysqli_query($conn,$sqlTransferCartLinesToOrder)){
            die("Something went wrong");
        };

        $_SESSION["message_string"] = "Order successfully placed!";
        $_SESSION["message_class"] = "info";
        header("Location:../../home/index.php");
        
    } else {
        echo "Error: " . $mysqli->error;
    };

?>