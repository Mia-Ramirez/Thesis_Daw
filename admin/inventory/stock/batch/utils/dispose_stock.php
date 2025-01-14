<?php
    $user_id = $_SESSION['user_id'];

    $batch_id= mysqli_real_escape_string($conn, $_POST['batch_id']);
    $product_id= mysqli_real_escape_string($conn, $_POST['product_id']);
    $disposed_quantity= mysqli_real_escape_string($conn, $_POST['disposed_quantity']);
    $batch_reference_name= mysqli_real_escape_string($conn, $_POST['batch_reference_name']);
    
    $sqlGetProduct = "SELECT current_quantity FROM product WHERE id=$product_id";
    $product_result = mysqli_query($conn,$sqlGetProduct);
    $row = mysqli_fetch_array($product_result);
    if (($row['current_quantity'] - $disposed_quantity) < 0){
        $current_quantity = $row['current_quantity'];
    } else {
        $current_quantity = $row['current_quantity'] - $disposed_quantity;
    };
    
    $sqlUpdateProduct = "UPDATE product SET current_quantity='$current_quantity' WHERE id = $product_id";
    if(!mysqli_query($conn,$sqlUpdateProduct)){
        die("Something went wrong");
    };

    $disposal_date = date('Y-m-d');
    $sqlDisposeBatch = "UPDATE batch SET disposed_quantity='$disposed_quantity', date_disposed='$disposal_date' WHERE id=$batch_id";
    if(!mysqli_query($conn,$sqlDisposeBatch)){
        die("Something went wrong");
    };

    $history_remarks = "Disposed Stock: ".$disposed_quantity." quantity ".$batch_reference_name;
    $sqlInsertProductHistory = "INSERT INTO history(object_type, object_id, remarks, user_id) VALUES ('product','$product_id','$history_remarks','$user_id')";
    if(!mysqli_query($conn,$sqlInsertProductHistory)){
        die("Something went wrong");
    };

    $_SESSION["message_string"] = "Stocks successfully disposed!";
    $_SESSION["message_class"] = "info";
?>