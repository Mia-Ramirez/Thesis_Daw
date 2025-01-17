<!DOCTYPE html>
<?php
    $maxFileSize = 5 * 1024 * 1024;  // 5MB in bytes

    session_start();
    $doc_root = $_SESSION["DOC_ROOT"];
    $customer_id = $_SESSION['customer_id'];
    
    include($doc_root.'/utils/connect.php');
    include($doc_root.'/utils/common_fx_and_const.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['action'] == 'apply_prescription'){
            $line_id = mysqli_real_escape_string($conn, $_POST["line_id"]);
            $prescription_id = mysqli_real_escape_string($conn, $_POST["prescription_id"]);

            if ($prescription_id == ""){
                header("Location:index.php");
                exit;
            } elseif ($prescription_id == "new"){
                $prescription_name = mysqli_real_escape_string($conn, $_POST["prescription_name"]);
                
                // Input Validation for Name
                $checkCustomerPrescription="SELECT id FROM customer_prescription WHERE reference_name='$prescription_name' AND customer_id=$customer_id";
                $prescription_result=$conn->query($checkCustomerPrescription);

                if ($prescription_result->num_rows != 0){
                    $_SESSION["message_string"] = "This Prescription Name already exists !";
                    $_SESSION["message_class"] = "danger";
                    header("Location:index.php");
                    exit;
                };

                // Input Validation for Photo
                if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                    // Get image details
                    $imageTmpName = $_FILES['image']['tmp_name'];
                    $imageName = $_FILES['image']['name'];
                    $imageSize = $_FILES['image']['size'];
                    $imageType = $_FILES['image']['type'];
            
                    // Define allowed image types (optional)
                    $allowedTypes = ['image/jpeg','image/png'];
                    
                    // Validate image type
                    if ($imageSize > $maxFileSize) {
                        $_SESSION["message_string"] = "File size exceeds the maximum limit of 5MB.";
                        $_SESSION["message_class"] = "danger";
                        header("Location:index.php");
                        exit;
                    };
                    
                    if (in_array($imageType, $allowedTypes)) {
                        // Generate a unique name for the image to prevent overwriting
                        $newImageName = uniqid() . '-' . str_replace([" "," "], "_", $imageName);
                        $uploadDir = '../../../assets/photos/';  // The folder where the image will be stored
                        
                        // Check if the upload directory exists, if not create it
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        };

                        $imagePath = getBaseURL() . str_replace("../", "", $uploadDir) . $newImageName;
                        
                        // Move the uploaded file to the desired location
                        if (!move_uploaded_file($imageTmpName, $uploadDir . $newImageName)) {
                            $_SESSION["message_string"] = "Error uploading image. Path Not found";
                            $_SESSION["message_class"] = "danger";
                            header("Location:index.php");
                            exit;
                        };

                    } else {
                        $_SESSION["message_string"] = "Invalid image type. Only JPG, and PNG are allowed.";
                        $_SESSION["message_class"] = "danger";
                        header("Location:index.php");
                        exit;
                    };

                } else {
                    $_SESSION["message_string"] = "No file uploaded or error uploading file.";
                    $_SESSION["message_class"] = "danger";
                    header("Location:index.php");
                    exit;
                };

                $sqlInsertCustomerPrescription = "INSERT INTO customer_prescription(reference_name, customer_id, prescription_photo) VALUES ('$prescription_name','$customer_id','$imagePath')";
                if(!mysqli_query($conn,$sqlInsertCustomerPrescription)){
                    die("Something went wrong");
                };
                $prescription_id = mysqli_insert_id($conn);

            };
            
            $sqlUpdateProductPrescription = "UPDATE product_prescription SET prescription_id='$prescription_id' WHERE id=$line_id";
            if(!mysqli_query($conn,$sqlUpdateProductPrescription)){
                die("Something went wrong");
            };

            $_SESSION["message_string"] = "Product Prescription attached successfully!";
            $_SESSION["message_class"] = "info";
            
        };

    } else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if ($_GET['action'] == 'remove'){
            if (isset($_GET['product_line_id']) && isset($_GET['cart_id'])){
                $line_id = $_GET['product_line_id'];
                $cart_id = $_GET['cart_id'];
                $sqlUpdateCartLineStatus = "UPDATE product_line SET for_checkout='0' WHERE id=$line_id AND cart_id=$cart_id";
                if(!mysqli_query($conn,$sqlUpdateCartLineStatus)){
                    die("Something went wrong");
                };
                $_SESSION["message_string"] = "Product removed successfully!";
                $_SESSION["message_class"] = "info";
            };
        };

    };

    header("Location:index.php");
    exit;
?>