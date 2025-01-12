<?php
    session_start();
    $base_url = $_SESSION["BASE_URL"];
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="<?php echo $base_url;?>assets/scripts/common_fx.js"></script>
        <?php include '../components/title.php'; ?>
    </head>

    <body>
        <?php include '../components/unauth_redirection.php'; ?>
        <?php include '../components/side_nav.php'; ?>
        
        <?php
            $current_page_title = "point of sale";
            include '../components/top_nav.php';
        ?> 

        <?php
            include('../../utils/connect.php');

            $user_id = $_SESSION['user_id'];

            $sqlGetUserCartID = "SELECT id AS user_cart_id FROM pos_cart WHERE user_id=$user_id";        
            $result = mysqli_query($conn,$sqlGetUserCartID);

            if ($result->num_rows != 0){
                $row = mysqli_fetch_array($result);
                $user_cart_id = $row['user_cart_id'];

            } else {
                $sqlInsertUserCart = "INSERT INTO pos_cart(user_id) VALUES ('$user_id')";
                if(!mysqli_query($conn,$sqlInsertUserCart)){
                    die("Something went wrong");
                };
                $user_cart_id = mysqli_insert_id($conn);  
            };
        ?>

        <div class="main">
            
        </div>

        <script>
            window.onload = function() {
                setActivePage("nav_pos");
            };
        </script>
    </body>
</html>