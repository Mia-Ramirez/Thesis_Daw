<!DOCTYPE html>
<?php
    session_start();
    $doc_root = $_SESSION["DOC_ROOT"];
    include($doc_root.'/utils/connect.php');
    include($doc_root.'/utils/common_fx_and_const.php');

    $product_id = $_SESSION['product_id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $modal_supplier_id = mysqli_real_escape_string($conn, $_POST["modal_supplier_id"]);
        $modal_supplier_name = mysqli_real_escape_string($conn, $_POST["modal_supplier_name"]);
        $_SESSION["supplier_name"] = $modal_supplier_name;

        $cost = mysqli_real_escape_string($conn, $_POST["cost"]);
        $selling_price = mysqli_real_escape_string($conn, $_POST["selling_price"]);
        $reference_number = mysqli_real_escape_string($conn, $_POST["reference_number"]);
        $quantity = mysqli_real_escape_string($conn, $_POST["quantity"]);
        $expiration_date = mysqli_real_escape_string($conn, $_POST["expiration_date"]);
        $supplier_id = mysqli_real_escape_string($conn, $_POST["supplier_id"]);
        
        $_SESSION["reference_number"] = $reference_number;
        $_SESSION["expiration_date"] = $expiration_date;
        $_SESSION["cost"] = $cost;
        $_SESSION["quantity"] = $quantity;
        
        if ($_POST['action'] == 'save_supplier'){
            if (isset($_POST['modal_supplier_id']) && !empty($modal_supplier_id)) {
                $sqlCheck="SELECT id FROM supplier WHERE name='$modal_supplier_name' AND id!=$modal_supplier_id";
                $result=$conn->query($sqlCheck);
                
                if ($result->num_rows != 0){
                    $_SESSION["message_string"] = "This Supplier Name already exists !";
                    $_SESSION["message_class"] = "danger";
                    header("Location:index.php?product_id=".$product_id);
                    exit;
                };
    
                // Edit Supplier
                
                $sql = "UPDATE supplier SET name = '$modal_supplier_name' WHERE id = $modal_supplier_id";
                $message_string = "Supplier details updated successfully!";
                $_SESSION["message_class"] = "info";
            } else {
    
                $sqlCheck="SELECT id FROM supplier WHERE name='$modal_supplier_name'";
                $result=$conn->query($sqlCheck);
                
                if ($result->num_rows != 0){
                    $_SESSION["message_string"] = "This Supplier Name already exists !";
                    $_SESSION["message_class"] = "danger";
                    header("Location:index.php?product_id=".$product_id);
                    exit;
                };
    
                // Add new supplier
                $sql = "INSERT INTO supplier (name) VALUES ('$modal_supplier_name')";
                $message_string = "Supplier added successfully !";
                $_SESSION["message_class"] = "success";
            }
    
            if(!mysqli_query($conn,$sql)){
                die("Something went wrong");
            };
    
            $_SESSION["message_string"] = $message_string;

        } elseif ($_POST['action'] == 'add_stock') {
            $user_id = $_SESSION['user_id'];

            $sqlGetEmployee = "SELECT id FROM employee WHERE user_id=".$user_id;
            $employee_result = mysqli_query($conn,$sqlGetEmployee);
            $row = mysqli_fetch_array($employee_result);
            $employee_id = $row['id'];

            // BATCH
            $sqlInsertBatch = "INSERT
                                    INTO batch(reference_number, expiration_date, supplier_id, product_id, employee_id, received_quantity, batch_cost, batch_selling_price)
                                    VALUES ('$reference_number','$expiration_date','$supplier_id','$product_id','$employee_id','$quantity','$cost', '$selling_price')";

            if(!mysqli_query($conn,$sqlInsertBatch)){
                die("Something went wrong");
            };
            // $batch_id = mysqli_insert_id($conn);

            // HISTORY
            $history_remarks = "Add Stock: ".$quantity." quantity ".$reference_number;
            $sqlInsertStockHistory = "INSERT INTO
                                    history(object_type, object_id, remarks, user_id)
                                    VALUES ('product','$product_id','$history_remarks','$user_id')";

            if(!mysqli_query($conn,$sqlInsertStockHistory)){
                die("Something went wrong");
            };

            $sqlGetProduct = "SELECT current_quantity FROM product WHERE id=$product_id";
            $product_result = mysqli_query($conn,$sqlGetProduct);
            $row = mysqli_fetch_array($product_result);
            $current_quantity = $row['current_quantity'] + $quantity;

            // $sqlUpdateProduct = "UPDATE product SET cost = '$cost', current_quantity='$current_quantity' WHERE id = $product_id";
            $sqlUpdateProduct = "UPDATE product SET cost = '$cost', current_quantity='$current_quantity', price='$selling_price' WHERE id = $product_id";
            if(!mysqli_query($conn,$sqlUpdateProduct)){
                die("Something went wrong");
            };

            $_SESSION["message_string"] = "Stock successfully added!";
            $_SESSION["message_class"] = "success";

            unset($_SESSION['reference_number']);
            unset($_SESSION['expiration_date']);
            unset($_SESSION['cost']);
            unset($_SESSION['quantity']);
            unset($_SESSION['supplier_name']);

            header("Location:../index.php");
            exit;
        };
    };

    header("Location:index.php?product_id=".$product_id);
?>