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
    <div class="container">
        <h1 class="form-title">Set Password</h1>
        <?php
          include("../../utils/connect.php");
          if (isset($_GET['key'])) {
              $decoded_key = base64_decode($_GET['key']);
              list($key_origin, $email, $timeRequested) = explode("|", $decoded_key);
              if ($key_origin != "recover_password"){
                  header("Location:../../page/404.php");
              };
              $sqlCheck = "SELECT id, username FROM user WHERE email=\"$email\"";
              $result = mysqli_query($conn, $sqlCheck);
              if ($result->num_rows < 1){
                  // If key is not found it will redirect to 404 Page
                  header("Location:../../page/404.php");
              };
              $row = mysqli_fetch_array($result);
              $_SESSION["key"] = $_GET['key'];
              $_SESSION["email"] = $email;
              // $_SESSION["user_id"] = $row["id"];
              // $_SESSION["user_name"] = $row["username"];
        ?>
        <form method="post" action="process.php">
          <div class="input-group">
              <i class="fas fa-envelope"></i>
              <input type="text" name="email" id="email" placeholder="Email" value="<?php echo $email; ?>" disabled />
              <label for="email">Email</label>
          </div>

          <div class="input-group">
              <i class="fas fa-lock"></i>
              <input type="password" name="password" id="password" placeholder="Password" required>
              <label for="password">Password</label>
          </div>

          <div class="input-group">
              <i class="fas fa-lock"></i>
              <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
              <label for="confirm_password">Confirm Password</label>
          </div>
         
          <button class="btn" name="action" id="set-password" value="set_password">Submit</button>
          <?php 
              if (isset($_SESSION["message_string"])) {
              ?>
                  <hr style="border: 1px solid #000 !important; width: 80%; margin: 20px auto;">
                  <div class="alert alert-<?php echo $_SESSION["message_class"] ?>">
                      <?php 
                      echo $_SESSION["message_string"];
                      ?>
                  </div>
              <?php
              unset($_SESSION["message_string"]);
              }
          } else {
              header("Location:../../page/404.php");
          }
          ?>
        </form>
       

        <?php 
            if (isset($_SESSION["message_string"])) {
            ?>
                <hr style="border: 1px solid #000 !important; width: 80%; margin: 20px auto;">
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