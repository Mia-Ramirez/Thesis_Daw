<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recover Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../../assets/styles/bootstrap.css">
</head>
<body>
    <?php
        session_start();
        if (isset($_SESSION["user_role"])) {
          if ($_SESSION["user_role"] == "customer"){
            header("Location:../../customer/home/index.php");
          } else if ($_SESSION["user_role"] == "admin"){
            header("Location:../../admin/dashboard/index.php");
          } else if ($_SESSION["user_role"] == "pharmacist"){
            header("Location:../../pharmacist/dashboard/index.php");
          };
        };
    ?>
    <div class="container" id="forgotPassword">
        <h1 class="form-title">Forgot Password</h1>
        <form method="post" action="process.php">
          <div class="input-group">
              <i class="fas fa-envelope"></i>
              <input type="text" name="email" id="email" placeholder="Email" required>
              <label for="email">Email</label>
          </div>
         
          <button class="btn" name="action" id="recover-password" value="recover_password">Submit</button>
        </form>
       

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

    </div>

    <script>

    </script>
    
</body>
</html>