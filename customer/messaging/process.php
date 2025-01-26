<!DOCTYPE html>
<?php
    $maxFileSize = 5 * 1024 * 1024;  // 5MB in bytes
    
    session_start();
    $doc_root = $_SESSION["DOC_ROOT"];
    include($doc_root.'/utils/connect.php');

    include($doc_root.'/utils/common_fx_and_const.php'); // loggedChanges
    
    
    if (isset($_POST["submit_inquiry"])) {
        $inquiry = mysqli_real_escape_string($conn, $_POST["inquiry"]);
        $customer_id = $_SESSION["customer_id"];

        $imagePath = NULL;

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
                $uploadDir = '../../assets/photos/';  // The folder where the image will be stored
                
                // Check if the upload directory exists, if not create it
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                };

                $imagePath = getBaseURL() . str_replace("../", "", $uploadDir) . $newImageName;
                
                // Move the uploaded file to the desired location
                if (!move_uploaded_file($imageTmpName, $uploadDir . $newImageName)) {
                    $_SESSION["message_string"] = "Error uploading image.";
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
        };
        
        $sqlInsert = "INSERT INTO messages(customer_id, inquiry) VALUES ('$customer_id', '$inquiry')";
        if ($imagePath){
            $sqlInsert = "INSERT INTO messages(customer_id, inquiry, inquiry_attachment_path) VALUES ('$customer_id', '$inquiry', '$imagePath')";
        };
        
        if(mysqli_query($conn,$sqlInsert)){
            header("Location:index.php");
        }else{
            die("Something went wrong");
        };
    }
?>