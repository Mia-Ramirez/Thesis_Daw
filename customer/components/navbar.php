<?php
    $base_url = $_SESSION["BASE_URL"];
?>
<nav class="navbar">
    <div class="navdiv">
    <div class="logo">
        <img style="width: 100px; float: left; border-radius: 100%; border: solid 1px rgb(0, 0, 0); margin: 1.5rem; margin-left: 3rem;" 
        src=<?php echo $base_url."assets/images/logo.png";?>  alt="PHARMANEST ESSENTIAL LOGO">
    </div>
    <div class="title">
    <h1> PHARMANEST ESSENTIAL </h1>
    </div>
        <div class="li">
            <div id="nav_home" class="menu">
                <a href="<?php echo $base_url."customer/home/index.php";?>">
                    <i class="fas fa-home"></i> <!-- home icon -->
                </a>
            </div>

            <div id="nav_cart" class="menu">
                <a href="<?php echo $base_url."customer/cart/index.php";?>">
                    <i class="fas fa-cart-shopping"></i> <!-- add to cart icon -->
                </a>
            </div>

            <div id="nav_message" class="menu">
                <a href="#">
                    <i class="fas fa-envelope"></i> <!-- chat icon -->
                </a>
            </div>

            <!-- <div id="nav_account" class="menu">
                <a href="#">
                    <i class="fas fa-envelope"></i>
                </a>
            </div>

            <div id="nav_logout" class="menu">
                <a href="#">
                    <i class="fas fa-envelope"></i>
                </a>
            </div> -->

            <div id="nav_menu" class="menu">
                <i class="fas fa-gear"></i>
                <div class="dropdown">
                    <a href="<?php echo $base_url."customer/profile/index.php";?>">My Profile</a>
                    <a href="<?php echo $base_url."account/logout.php";?>">Logout</a>
                </div>
            </div>
        </div>
        
    </div>
</nav>