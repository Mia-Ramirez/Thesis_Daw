<?php
session_start();
include("connect.php");

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmanest Essentials</title>
</head>
<body>
    
    <?php 
       if(isset($_SESSION['email'])){
        $email=$_SESSION['email'];
        $query=mysqli_query($conn, "SELECT customer.* FROM `customer` WHERE customer.email='$email'");
        while($row=mysqli_fetch_array($query)){
            echo $row['firstname'].' '.$row['lastname'];
        }
       }
       ?>
       
      <a href="logout.php">Logout</a>
    </div>
</body>
</html>