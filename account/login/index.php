<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
    <div class="container" id="signIn">
        <h1 class="form-title">Sign In</h1>
        <form method="post" action="process.php">
          <div class="input-group">
              <i class="fas fa-envelope"></i>
              <input type="text" name="email_or_username" id="email_or_username" placeholder="Email or Username" required value="<?php if(isset($_SESSION["email_username"])){echo $_SESSION["email_username"];unset($_SESSION["email_username"]);}?>">
              <label for="email_or_username">Email or Username</label>
          </div>
          <div class="input-group">
              <i class="fas fa-lock"></i>
              <input type="password" name="password" id="password" placeholder="Password" required>
              <label for="password">Password</label>
          </div>
          <p class="recover">
            <a href="../recover/index.php">Recover Password</a>
          </p>
         <input type="submit" class="btn" value="Sign In" name="signIn">
        </form>
       
        <div class="links">
          <p>Don't have account yet?</p>
          <button onclick="redirectToPage('register')" id="signUpButton">Sign Up</button>
        </div>
        <?php 
        // DISPLAY SUCCESS MESSAGE WHEN REGISTRATION SUCCEEDED
        if (isset($_SESSION["register_success"])) {
        ?>
        
        <div class="alert alert-success">
            <?php 
            echo $_SESSION["register_success"];
            ?>
        </div>
        <?php
        unset($_SESSION["register_success"]);
        }

        // DISPLAY ERROR MESSAGE WHEN EMAIL IS NOT YET REGISTERED OR WRONG PASSWORD
        if (isset($_SESSION["login_error"])) {
        ?>
        
        <div class="alert alert-danger">
            <?php 
            echo $_SESSION["login_error"];
            ?>
        </div>
        <?php
        unset($_SESSION["login_error"]);
        }
    ?>
    </div>
    <script>
        function redirectToPage(page) {
            window.location.href = '../'+page+'/index.php';
        }
    </script>
    
</body>
</html>