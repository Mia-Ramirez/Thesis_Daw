<?php
    session_start();
    $base_url = $_SESSION["BASE_URL"];
    $doc_root = $_SESSION["DOC_ROOT"];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHARMANEST ESSENTIAL</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>assets/styles/bootstrap.css">
        <link rel="stylesheet" href="../../styles.css">
        <link rel="stylesheet" href="styles.css">
        <script src="<?php echo $base_url;?>assets/scripts/common_fx.js"></script>
    </head>
    <body class="body">
        <?php include '../../components/unauth_redirection.php';?>

        <?php include '../../components/navbar.php'; ?>
        
        <?php
            include($doc_root.'/utils/connect.php');
            
            $user_id = $_SESSION['user_id'];

            $sqlGetCustomerCartID = "SELECT cc.id AS customer_cart_id, c.id AS customer_id FROM customer_cart cc INNER JOIN customer c ON cc.customer_id=c.id WHERE c.user_id=$user_id";
            $customer_result = mysqli_query($conn,$sqlGetCustomerCartID);
            if ($customer_result->num_rows == 0){
                header("Location:../../../page/404.php");
            };

            $row = mysqli_fetch_array($customer_result);
            $customer_id = $row['customer_id'];
            $customer_cart_id = $row['customer_cart_id'];  
            
            if (isset($_SESSION['customer_id']) == false){
                $_SESSION['customer_id'] = $customer_id;
            };

            if (isset($_SESSION['customer_cart_id']) == false){
                $_SESSION['customer_cart_id'] = $customer_cart_id;
            };
            
            $sqlGetPrescribedProducts = "SELECT
                                            mp.id AS line_id,
                                            p.name AS product_name,
                                            photo,
                                            prescription_id,
                                            cp.reference_name AS prescription_name,
                                            cp.prescription_photo AS prescription_photo,
                                            mp.product_id AS product_id
                                        FROM product_prescription mp
                                        LEFT JOIN customer_prescription cp ON mp.prescription_id=cp.id
                                        INNER JOIN product p ON mp.product_id=p.id
                                        WHERE mp.customer_id=$customer_id
                                            AND product_id IN (
                                                    SELECT product_id FROM product_line
                                                    WHERE for_checkout=1 AND cart_id=$customer_cart_id AND line_type='cart'
                                            )
            ";
            
            $prescribed_meds = mysqli_query($conn,$sqlGetPrescribedProducts);
            if ($prescribed_meds->num_rows == 0){
                $_SESSION["message_string"] = "Cart is empty!";
                $_SESSION["message_class"] = "danger";
                header("Location:../../home/index.php");
            };
            
            $sqlGetCustomerPrescriptions = "SELECT
                                                id AS prescription_id,
                                                reference_name,
                                                prescription_photo
                                            FROM customer_prescription
                                            WHERE customer_id=$customer_id";
            
            $customer_prescriptions = mysqli_query($conn,$sqlGetCustomerPrescriptions);
            
            $prescriptions = array();
            
        ?>
        
        
        
        <!-- Select Prescription Modal -->
        <div class="custom-modal" id="prescriptionModal">
            <div class="modal-content">
            <form action="process.php" method="POST" enctype="multipart/form-data">
                <h1 id="modal_header">Upload Prescription</h1>
                <div class="form-group">
                    <label for="prescription-option">Choose an option:</label>
                    <select id="prescription-option" name="prescription_id">
                        <option value="">Select</option>
                        <?php
                            $index = 0;
                            
                            while($data = mysqli_fetch_array($customer_prescriptions)){
                                $dictionary = [
                                    "prescriptionPhoto" => $data['prescription_photo'],
                                ];
                                array_push($prescriptions, $dictionary);
                                echo "<option data-index='$index' value='".$data['prescription_id']."'>".$data['reference_name']."</option>";
                                $index++;
                            };
                            echo "
                                <script>
                                    let prescriptions = ".json_encode($prescriptions).";
                                </script>
                            ";
                        ?>
                        <option value="new">Upload New Prescription</option>
                    </select>
                </div>

                <div id="div_prescription" class="form-group" style="display: none">
                    <p id="p_prescription_name">
                        <label for="prescription_name">Prescription Name:</label>
                        <input type="text" id="prescription_name" name="prescription_name" disabled>
                    </p>
                    
                    <div class="image-container">
                        <input name="image" id="prescription_file" type="file" accept="image/*" onchange="previewImage(event)" disabled>
                        <span id="span_image_text">Click to upload an image</span>
                        <img id="img_photo">
                    </div>
                </div>

                <input id="line_id" type="hidden" name="line_id">
                <button id="button_apply" type="submit" name="action" class="modal-button yes-button" value="apply_prescription">Apply</button>
                <button type="button" class="modal-button no-button" onclick="onNo('prescriptionModal')" style="margin-left: 28%">Cancel</button>
            </form>
            </div>
        </div>
        
        <?php
            if (isset($_SESSION["message_string"])) {
                ?>
                    <div class="alert alert-<?php echo $_SESSION["message_class"] ?>">
                        <?php 
                        echo $_SESSION["message_string"];
                        ?>
                    </div>
                <?php
                unset($_SESSION["message_string"]);
                unset($_SESSION["message_class"]);
            }
        ?>

        <div class="card">
            <h2>
                Product(s) that requires Prescription
                <button id="proceed_button" type="button" class="modal-button yes-button" onclick="redirectToOrderPage()" style="margin-left: 45%">Proceed</button>
            </h2>

            <table id="productTable">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Prescription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $enable_proceed_button = "1";
                        while($data = mysqli_fetch_array($prescribed_meds)){
                    ?> 
                        <?php
                            if (is_null($data['prescription_name'])){
                                $enable_proceed_button = "0";
                            };
                        ?>
                        <tr>
                            <td>
                                <img src="<?php echo $data['photo'];?>" style="width:100px; height:100px"><br/>
                                <?php echo $data['product_name'];?>
                            </td>
                            <td>
                                <?php
                                    if ($data['prescription_name']){
                                        echo "<img class='prescription_photo' src=\"".$data['prescription_photo']."\" style='width:100px; height:100px' onclick=\"openFullscreen(this)\"><br/>";
                                    };
                                ?>
                                <span><?php echo $data['prescription_name'];?></span>
                            <td>
                                <?php
                                    if (!is_null($data['prescription_name'])){
                                        echo '<u class="action_modal" onclick="showPrescriptionModal(\''.$data['line_id'].'\', \''.$data['product_name'].'\', \''.$data['prescription_id'].'\')">Change</u>';   
                                    } else {
                                        echo '<u class="action_modal" onclick="showPrescriptionModal(\''.$data['line_id'].'\', \''.$data['product_name'].'\', \'\')">Add</u>';   
                                    };
                                ?>
                                | <a href="process.php?action=remove&product_id=<?php echo $data['product_id'];?>">Remove</a>
                            </td>
                        </tr>
                    <?php
                        };
                        echo "
                            <script>
                                let enableProceedButton = ".$enable_proceed_button.";
                            </script>
                        ";
                    ?>
                </tbody>
            </table>


        </div>
        
        <script src="script.js"></script>

        <script src="../../script.js"></script>
        
        <script>
            window.onload = function() {
                setActivePage("nav_cart");
            };

            function redirectToOrderPage() {
                window.location.href = '../confirm/index.php';
            };

        </script>
    </body>
</html>