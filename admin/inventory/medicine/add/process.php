<!DOCTYPE html>
<?php
    $maxFileSize = 5 * 1024 * 1024;  // 5MB in bytes

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        session_start();
        if (isset($_POST["action"])) {
            include('../../../../utils/connect.php');
            include('../../../../utils/common_fx_and_const.php'); // getBaseURL
            if ($_POST['action'] === 'add_medicine') {
                $category_names = mysqli_real_escape_string($conn, $_POST["category_names"]);
                $medicine_name = mysqli_real_escape_string($conn, $_POST["medicine_name"]);
                $price = mysqli_real_escape_string($conn, $_POST["price"]);
                $valid_discounts = mysqli_real_escape_string($conn, $_POST["valid_discounts"]);
                $required_prescription = mysqli_real_escape_string($conn, $_POST["required_prescription"]);
                $rack_location = mysqli_real_escape_string($conn, $_POST["rack_location"]);
                $maintaining_quantity = mysqli_real_escape_string($conn, $_POST["maintaining_quantity"]);
                
                $imagePath = '';
                
                $_SESSION["medicine_name"] = $medicine_name;
                $_SESSION["price"] = $price;
                $_SESSION["rack_location"] = $rack_location;
                $_SESSION["maintaining_quantity"] = $maintaining_quantity;
                
                $category_names = str_replace(", ", ",", $category_names);
                $category_names = "'".str_replace(",", "','", $category_names)."'";
                $getCategoryIDs="SELECT id FROM category WHERE name IN ($category_names)";
                $categoryIDs_result=$conn->query($getCategoryIDs);
                $category_ids = "";
                while($data = mysqli_fetch_array($categoryIDs_result)){
                    if ($category_ids == ""){
                        $category_ids = $data['id'];
                    } else {
                        $category_ids .= ",".$data['id'];
                    };
                };

                if ($required_prescription == "Yes"){
                    $required_prescription = 1;
                } else {
                    $required_prescription = 0;
                };

                // Input Validation for Name
                $checkMedicine="SELECT id FROM medicine WHERE name='$medicine_name'";
                $medicine_result=$conn->query($checkMedicine);

                if ($medicine_result->num_rows != 0){
                    $_SESSION["message_string"] = "This Medicine Name already exists !";
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
                    $allowedTypes = ['image/jpeg', 'image/png'];
                    
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
                        $uploadDir = '../../../../assets/photos/';  // The folder where the image will be stored
                        
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

                } else {
                    $_SESSION["message_string"] = "No file uploaded or error uploading file.";
                    $_SESSION["message_class"] = "danger";
                    header("Location:index.php");
                    exit;
                };
                
                $sqlInsertMedicine = "INSERT INTO medicine(name , price , applicable_discounts, prescription_is_required, photo, rack_location, maintaining_quantity) VALUES ('$medicine_name','$price', '$valid_discounts', '$required_prescription', '$imagePath', '$rack_location', '$maintaining_quantity')";
                if(!mysqli_query($conn,$sqlInsertMedicine)){
                    die("Something went wrong");
                };
                $medicine_id = mysqli_insert_id($conn);

                $sqlInsertMedicineCategory = "INSERT INTO medicine_categories(medicine_id , category_ids) VALUES ('$medicine_id','$category_ids')";
                if(!mysqli_query($conn,$sqlInsertMedicineCategory)){
                    die("Something went wrong");
                };

                $_SESSION["message_string"] = "Medicine added successfully!";
                $_SESSION["message_class"] = "success";

                unset($_SESSION["medicine_name"]);
                unset($_SESSION["price"]);

                header("Location:index.php");
                exit;
            };

        };
    };
?>