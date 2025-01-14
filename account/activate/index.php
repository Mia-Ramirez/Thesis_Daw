<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
        if (isset($_GET['key'])) {
            include('../../utils/connect.php');
            // include('../../utils/common_fx_and_const.php');

            $reference_key = $_GET['key'];
            $decoded_key = base64_decode($reference_key);
            list($key_origin, $email, $deletionDate) = explode("|", $decoded_key);
            if ($key_origin != "register"){
                header("Location:../../page/404.php");
            }
            $sqlCheck = "SELECT data FROM temporary_record WHERE reference_key=\"$reference_key\"";
            $result = mysqli_query($conn, $sqlCheck);
            if ($result->num_rows < 1){
                // If key is not found it will redirect to 404 Page
                header("Location:../../page/404.php");
            };
            $row = mysqli_fetch_array($result);
            
            $jsonData = $row['data']; // Access the JSON string from the associative array
            $dataArray = json_decode($jsonData, true); // Decode JSON to associative array

            if (json_last_error() !== JSON_ERROR_NONE) {
                die("JSON decoding error: " . json_last_error_msg());
            };

            $username = $dataArray['username'];
            $password = $dataArray['password'];
            $role = $dataArray['role'];

            $contact = $dataArray['contact'];
            $address = $dataArray['address'];
            $first_name = $dataArray['first_name'];
            $last_name = $dataArray['last_name'];
            
            $password_length = $dataArray['password_length'];

            $checkCustomer="SELECT c.id AS customer_id, u.id AS user_id FROM customer c LEFT JOIN user u ON c.user_id=u.id WHERE c.first_name='$first_name' AND c.last_name='$last_name' AND (c.contact_number='$contact' OR u.email='$email')";
            $customer_result=$conn->query($checkCustomer);
            $user_id = NULL;
            
            if ($customer_result->num_rows != 0){
                $row = mysqli_fetch_array($customer_result);
                $user_id = $row['user_id'];
            };
            
            if ($user_id == NULL){
                $sqlInsertUser = "INSERT INTO user(username, email, password, role, password_length) VALUES ('$username','$email','$password','$role','$password_length')";
                if(!mysqli_query($conn,$sqlInsertUser)){
                    die("Something went wrong");
                };
                $user_id = mysqli_insert_id($conn);
            };

            if ($customer_result->num_rows == 0){
                $sqlInsertCustomer = "INSERT INTO customer(first_name, last_name, contact_number, address, user_id) VALUES ('$first_name','$last_name','$contact','$address','$user_id')";
                if(!mysqli_query($conn,$sqlInsertCustomer)){
                    die("Something went wrong");
                };

            } else {
                $customer_id = $row['customer_id'];
                $sqlUpdateCustomer = "UPDATE customer SET first_name = '$first_name', last_name = '$last_name', contact_number = '$contact', address='$address', user_id='$user_id' WHERE id='$customer_id'";
                if(!mysqli_query($conn,$sqlUpdateCustomer)){
                    die("Something went wrong");
                };
            };
            
            $sqlDelete = "DELETE FROM temporary_record WHERE reference_key=\"$reference_key\"";
            if(!mysqli_query($conn,$sqlDelete)){
                die("Something went wrong");
            };
            
            ?>
            <div class="container">
                <h1>Account Activation</h1>
                <p>Congratulations! Your account is now activated</p>
                <p><a href="../login/index.php">Click here to Login</a></p>
            </div>
            <?php
            exit;
        }
    ?>
</body>
</html>
