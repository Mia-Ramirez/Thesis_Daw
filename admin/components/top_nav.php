<div class="head">
    <div class="topnav">
    <a href=<?php echo $base_url."account/logout.php";?>>↪Logout</a> 
    <a>(Logged-in as <?php echo ucwords($_SESSION['user_role']); ?>)</a>   
</div>
    <center>
        <h2 class="hh"> <?php echo strtoupper($current_page_title); ?> </h2>
        <h4>Welcome <?php echo ucwords($_SESSION['user_first_name']); ?>!</h4>
        </center>
    </div>
