<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
    <div class="container" id="signup">
        <!-- <img id="logo" src="../../assets/images/pharmLogo.png" alt="Logo"> -->
        <h1 class="form-title">Register</h1>
        <form method="post" action="process.php">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="fName" id="fName" placeholder="First Name" required>
                <label for="fname">First Name</label>
            </div>
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="lName" id="lName" placeholder="Last Name" required>
                <label for="lName">Last Name</label>
            </div>
            <div class="input-group">
                <i class="fas fa-address-book"></i>
                <input type="text" name="address" id="address" placeholder="Address" required>
                <label for="address">Address</label>
            </div>
            <div class="input-group">
                <i class="fas fa-address-book"></i>
                <input type="text" name="contact" id="contact" placeholder="Contact" required>
                <label for="contact">Contact Number (Digits Only)</label>
            </div>
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <input type="submit" class="btn" value="Sign Up" name="signUp">
            <?php 
                if (isset($_SESSION["register_error"])) {
                ?>
                    <hr style="border: 1px solid #000 !important; width: 80%; margin: 20px auto;">
                    <div class="alert alert-danger">
                        <?php 
                        echo $_SESSION["register_error"];
                        ?>
                    </div>
                <?php
                unset($_SESSION["register_error"]);
                }
            ?>
        </form>
     
        <div class="links">
            <p>Already Have Account ?</p>
            <button onclick="redirectToPage('login')" id="signIn" style="margin: 10px">Sign In</button>
        </div>
    </div>
    <script>
        function redirectToPage(page) {
            window.location.href = '../'+page+'/index.php';
        }
    </script>
</body>
</html>