<?php
    $base_url = $_SESSION["BASE_URL"];
?>
<div class="topnav">
    <a href=<?php echo $base_url."account/logout.php";?>>Logout (Logged-in as <?php echo ucwords($_SESSION['user_role']); ?>)</a>
</div>

<center>
    <div class="head">
        <h2> <?php echo strtoupper($current_page_title); ?> </h2>
        <h4>Welcome <?php echo ucwords($_SESSION['first_name']); ?>!</h4>
    </div>
</center>