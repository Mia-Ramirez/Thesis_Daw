<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<center>
	<div class="head">
	<h2> Customer Page </h2>
	<h4>Hi, Customer!</h4>
	</div>
	</center>
    
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