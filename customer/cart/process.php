<!DOCTYPE html>
<?php
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location:index.php");
        exit;
    };

    session_start();
    include('../../utils/connect.php');

    if ($_POST['action'] == 'remove_line'){
        $line_id= mysqli_real_escape_string($conn, $_POST['remove_line']);

        $sqlRemoveCartLine = "DELETE FROM product_line WHERE id=$line_id";
        
        if(!mysqli_query($conn,$sqlRemoveCartLine)){
            die("Something went wrong");
        };

        header("Location:index.php");
        exit();

    } elseif ($_POST['action'] == 'checkout_cart'){
        $user_id = $_SESSION['user_id'];

        $sqlGetCustomerCartID = "SELECT cc.id AS customer_cart_id, c.id AS customer_id FROM customer_cart cc INNER JOIN customer c ON cc.customer_id=c.id WHERE c.user_id=$user_id";
        $cart_result = mysqli_query($conn,$sqlGetCustomerCartID);
        if ($cart_result->num_rows == 0){
            $_SESSION["message_string"] = "Cart is empty!";
            $_SESSION["message_class"] = "error";
            header("Location:../index.php");
        };

        $cart_row = mysqli_fetch_array($cart_result);
        $customer_cart_id = $cart_row['customer_cart_id'];
        $customer_id = $cart_row['customer_id'];

        $selected_ids= mysqli_real_escape_string($conn, $_POST['selected_ids']);
        $selected_qtys= mysqli_real_escape_string($conn, $_POST['selected_items_qty']);
        $selected_discount= mysqli_real_escape_string($conn, $_POST['selected_discount']);
        
        $selected_ids = explode(",", $selected_ids);
        $selected_qtys = explode(",", $selected_qtys);
        
        if ($selected_discount == 'No Discount'){
            $sqlUpdateCart = "UPDATE customer_cart SET selected_discount=NULL WHERE id=$customer_cart_id";
        } else {
            $sqlUpdateCart = "UPDATE customer_cart SET selected_discount='$selected_discount' WHERE id=$customer_cart_id";
        };
        
        if(!mysqli_query($conn,$sqlUpdateCart)){
            die("Something went wrong");
        };

        $sqlResetProductLine = "UPDATE product_line SET for_checkout='0' WHERE cart_id=$customer_cart_id";
        if(!mysqli_query($conn,$sqlResetProductLine)){
            die("Something went wrong");
        };

        $index = 0;
        $prescription_is_required = false;

        while ($index < count($selected_ids)) {
            $line_id = $selected_ids[$index];
            $qty = $selected_qtys[$index];

            $sqlUpdateProductLine = "UPDATE product_line SET qty='$qty', for_checkout='1' WHERE id=$line_id";
            if(!mysqli_query($conn,$sqlUpdateProductLine)){
                die("Something went wrong");
            };

            $sqlGetMedicine = "SELECT medicine_id, prescription_is_required FROM product_line pl INNER JOIN medicine m ON pl.medicine_id=m.id WHERE pl.cart_id=$customer_cart_id AND pl.id=$line_id";
            $medicine_result = mysqli_query($conn,$sqlGetMedicine);
            $medicine_row = mysqli_fetch_array($medicine_result);
            
            $medicine_id = $medicine_row['medicine_id'];
            $medicine_prescription_is_required = $medicine_row['prescription_is_required'];
            if ($medicine_prescription_is_required == '1'){
                $prescription_is_required = true;
            
                $sqlGetMedicinePrescription = "SELECT id FROM medicine_prescription WHERE cart_id=$customer_cart_id AND medicine_id=$medicine_id";
                $prescription_result = mysqli_query($conn,$sqlGetMedicinePrescription);
                if ($prescription_result->num_rows == 0){
                    $sqlInsertMedicinePrescription = "INSERT INTO medicine_prescription(cart_id , medicine_id) VALUES ('$customer_cart_id','$medicine_id')";
                    if(!mysqli_query($conn,$sqlInsertMedicinePrescription)){
                        die("Something went wrong");
                    };
                };
            };

            $index++;
        };

        if ($prescription_is_required){
            header("Location:prescription/index.php");
        } else {
            header("Location:checkout/index.php");
        };
        exit;

    } else {
        header("Location:index.php");
        exit();
    };
?>
