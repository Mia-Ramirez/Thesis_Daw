<!DOCTYPE html>
<?php
    session_start();
    if (!isset($_SESSION['cart_id'])){
        header("Location: index.php");
        exit;
    };
    $cart_id = $_SESSION['cart_id'];
    
    $order_id = NULL;
    if (isset($_SESSION['order_id'])){
        $order_id = $_SESSION['order_id'];
        unset($_SESSION['order_id']);
    };
    
    include('../../utils/connect.php');
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (!isset($_GET['product_id']) || !isset($_GET['action']) ) {
            header("Location:index.php");
            exit;
        };

        $action = $_GET['action'];
        $product_id = $_GET['product_id'];
        if ($action != 'add_to_list'){
            header("Location: index.php?order_id=".$order_id);
            exit;
        };

        if ($order_id){
            $sqlInsertProductLine = "INSERT INTO product_line(cart_id, product_id, qty, for_checkout, line_type, order_id) VALUES ('$cart_id', '$product_id', '1', '1', 'pos', '$order_id')";
        } else {
            $sqlInsertProductLine = "INSERT INTO product_line(cart_id, product_id, qty, for_checkout, line_type) VALUES ('$cart_id', '$product_id', '1', '1', 'pos')";
        };
        
        if(!mysqli_query($conn,$sqlInsertProductLine)){
            die("Something went wrong");
        };
        
        $_SESSION["message_string"] = "Product added to list!";
        $_SESSION["message_class"] = "info";
        header("Location: index.php?order_id=".$order_id);
        exit;
        
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $selected_ids_str= mysqli_real_escape_string($conn, $_POST['selected_ids']);
        $selected_qtys= mysqli_real_escape_string($conn, $_POST['selected_items_qty']);
        $selected_discount= mysqli_real_escape_string($conn, $_POST['selected_discount']);
        
        $selected_ids = explode(",", $selected_ids_str);
        $selected_qtys = explode(",", $selected_qtys);

        $sqlResetProductLine = "UPDATE product_line SET for_checkout='0' WHERE cart_id=$cart_id";
        if(!mysqli_query($conn,$sqlResetProductLine)){
            die("Something went wrong");
        };

        $index = 0;
        while ($index < count($selected_ids)) {
            $line_id = $selected_ids[$index];
            $qty = $selected_qtys[$index];

            $sqlUpdateProductLine = "UPDATE product_line SET qty='$qty', for_checkout='1' WHERE id=$line_id";
            if(!mysqli_query($conn,$sqlUpdateProductLine)){
                die("Something went wrong");
            };
            $index++;
        };
        
        if ($order_id){
            $sqlDeletePOSProductLines = "DELETE FROM product_line WHERE order_id='$order_id' AND for_checkout='0' AND id NOT IN ('$selected_ids_str') AND line_type='pos'";
        } else {
            $sqlDeletePOSProductLines = "DELETE FROM product_line WHERE for_checkout='0' AND id NOT IN ('$selected_ids_str') AND line_type='pos'";
        };
        
        // if(!mysqli_query($conn,$sqlDeletePOSProductLines)){
        //     die("Something went wrong");
        // };
        $_SESSION['selected_discount'] = $selected_discount;
        
        header("Location: ./confirm/index.php?order_id=".$order_id);
        exit;

    };
        
?>