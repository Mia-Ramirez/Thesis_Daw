<!DOCTYPE html>
<?php
    if (!isset($_GET['medicine_id']) || !isset($_GET['action']) ) {
        header("Location:index.php");
        exit;
    };

    session_start();
    include('../../utils/connect.php');
    $medicine_id = $_GET['medicine_id'];
    $action = $_GET['action'];

    $user_id = $_SESSION['user_id'];

    $sqlCheckMedicine = "SELECT name FROM medicine WHERE id=$medicine_id"; 
    $result = mysqli_query($conn, $sqlCheckMedicine);
    
    if ($result->num_rows < 1){
        // If email is not yet registered it will display an error message in Login Form
        $_SESSION["message_string"] = "Medicine not found!";
        $_SESSION["message_class"] = "error";
        header("Location:index.php");
        exit;
    };

    $sqlGetCustomerCartID = "SELECT cc.id AS customer_cart_id, c.id AS customer_id FROM customer_cart cc RIGHT JOIN customer c ON cc.customer_id=c.id WHERE c.user_id=$user_id";
                
    $result = mysqli_query($conn,$sqlGetCustomerCartID);
    $row = mysqli_fetch_array($result);
    $customer_id = $row['customer_id'];
    
    if ($row['customer_cart_id']){
        $customer_cart_id = $row['customer_cart_id'];
    } else {
        $sqlInsertCustomerCart = "INSERT INTO customer_cart(customer_id, product_lines) VALUES ('$customer_id', NULL)";
        if(!mysqli_query($conn,$sqlInsertCustomerCart)){
            die("Something went wrong");
        };
        $customer_cart_id = mysqli_insert_id($conn);  
    };
    
    $sqlGetCustomerCart = "SELECT product_lines FROM customer_cart WHERE id=$customer_cart_id";
    $result = mysqli_query($conn,$sqlGetCustomerCart);
    $row = mysqli_fetch_array($result);
    $productLineData = $row['product_lines'];
    
    if (is_null($productLineData)){
        $productLineArray = [];
    } else {
        $productLineData = str_replace(['"{', '}"'], ['{', '}'], $productLineData);
        $productLineArray = json_decode($productLineData , true);
    };

    if ($productLineArray === false){
        die("JSON encoding error: " . json_last_error_msg());
    };

    if (!is_null($productLineArray) && array_key_exists($medicine_id, $productLineArray)) {
        if ($action == "buy_now"){
            $productLineArray[$medicine_id]["for_checkout"] = true;
        } else {
            $productLineArray[$medicine_id]["qty"] += 1;
            $productLineArray[$medicine_id]["for_checkout"] = false;
        };
    } else {
        $for_checkout = false;
        if ($action == "buy_now"){
            $for_checkout = true;
        };

        $productLineArray[$medicine_id] = json_encode([
            "qty" => 1,
            "for_checkout" => $for_checkout
        ]);

        if ($productLineArray[$medicine_id] === false){
            die("JSON encoding error: " . json_last_error_msg());
        };
    };

    $productLineArray = json_encode($productLineArray);

    $sqlUpdateCustomerCart = "UPDATE customer_cart SET product_lines='$productLineArray' WHERE id=$customer_cart_id";
    if(!mysqli_query($conn,$sqlUpdateCustomerCart)){
        die("Something went wrong");
    };

    if ($action == "buy_now"){
        header("Location:../cart/checkout/index.php");
    } else {
        $_SESSION["message_string"] = "Medicine added to cart!";
        $_SESSION["message_class"] = "info";
        header("Location:index.php");
    };
    exit;
?>