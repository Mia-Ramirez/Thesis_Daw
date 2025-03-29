<div class="head">
    <div class="topnav" style="margin-top:2%">
    <a class="o" style="font-size: 2rem; padding: 5px; padding-top: 0; padding-bottom:0; margin: 0%;" href=<?php echo $base_url."account/logout.php";?>>↪</a> 
    <a class="l">(Logged-in as <?php echo ucwords($_SESSION['user_role']); ?>)</a> 
    <center style="margin-left: 22%;">
        <h2 class="hh"> <?php echo strtoupper($current_page_title); ?> </h2>
        <h4>Welcome <?php echo ucwords($_SESSION['user_first_name']); ?>!</h4>
     </center>  
</div>
    
    </div>
