<!DOCTYPE html>
<?php
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        header("Location:index.php");
        exit;
    };
        
    if (!isset($_GET['product_id']) || !isset($_GET['action']) ) {
        header("Location:index.php");
        exit;
    };

    $qty = 1;
    if (isset($_GET['qty'])){
        $qty = $_GET['qty'];
    };

    session_start();
    $doc_root = $_SESSION["DOC_ROOT"];
    include($doc_root.'/utils/connect.php');
    $product_id = $_GET['product_id'];
    $action = $_GET['action'];

    $user_id = $_SESSION['user_id'];

    $sqlCheckProduct = "SELECT current_quantity FROM product WHERE id=$product_id"; 
    $result = mysqli_query($conn, $sqlCheckProduct);
    
    if ($result->num_rows < 1){
        // If email is not yet registered it will display an error message in Login Form
        $_SESSION["message_string"] = "Product not found!";
        $_SESSION["message_class"] = "danger";
        header("Location:index.php");
        exit;
    };
    $row = mysqli_fetch_array($result);
    $max_quantity = $row['current_quantity'];

    $sqlGetPOSCartID = "SELECT pc.id AS pos_cart_id FROM pos_cart pc INNER JOIN user u ON pc.user_id=u.id WHERE u.id=$user_id";
                
    $result = mysqli_query($conn,$sqlGetPOSCartID);
    $row = mysqli_fetch_array($result);
    
    if ($row['pos_cart_id']){
        $pos_cart_id = $row['pos_cart_id'];
    } else {
        $sqlInsertCustomerCart = "INSERT INTO pos_cart(user_id) VALUES ('$user_id')";
        if(!mysqli_query($conn,$sqlInsertCustomerCart)){
            die("Something went wrong");
        };
        $pos_cart_id = mysqli_insert_id($conn);  
    };
    
    if ($action == "buy_now"){
        $sqlResetProductLine = "UPDATE product_line SET for_checkout='0' WHERE pos_cart_id=$pos_cart_id";
        if(!mysqli_query($conn,$sqlResetProductLine)){
            die("Something went wrong");
        };
        $for_checkout = 1;
    } else {
        $for_checkout = 0;
    };

    $sqlGetProductLine = "SELECT id, qty FROM product_line WHERE pos_cart_id=$pos_cart_id AND product_id=$product_id";
    $result = mysqli_query($conn,$sqlGetProductLine);
    if ($result->num_rows != 0){
        $row = mysqli_fetch_array($result);
        $line_qty = $row['qty'] + $qty;
        if (intval($line_qty) > intval($max_quantity)){
            $_SESSION["message_string"] = "Quantity exceeded!";
            $_SESSION["message_class"] = "danger";
            header("Location:index.php");
            exit;
        };

        $line_id = $row['id'];
        $sqlUpdateProductLine = "UPDATE product_line SET qty='$line_qty', for_checkout='$for_checkout' WHERE id=$line_id";
        if(!mysqli_query($conn,$sqlUpdateProductLine)){
            die("Something went wrong");
        };

    } else {
        $line_qty = $qty;
        if (intval($line_qty) > intval($max_quantity)){
            $_SESSION["message_string"] = "Quantity exceeded!";
            $_SESSION["message_class"] = "danger";
            header("Location:index.php");
            exit;
        };
        $sqlInsertProductLine = "INSERT INTO product_line(pos_cart_id, product_id, qty, for_checkout, line_type) VALUES ('$pos_cart_id','$product_id','$line_qty','$for_checkout','pos')";
        if(!mysqli_query($conn,$sqlInsertProductLine)){
            die("Something went wrong");
        };
    };

    $row = mysqli_fetch_array($result);
    if ($action == "buy_now"){
        header("Location:../pos/index.php");
    } else {
        $_SESSION["message_string"] = "Product added to POS!";
        $_SESSION["message_class"] = "info";
        header("Location:index.php");
    };
    exit;
?>