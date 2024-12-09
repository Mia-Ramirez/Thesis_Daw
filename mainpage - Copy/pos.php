<!DOCTYPE html>
<html>

<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" type="text/css" href="css/admin.css">
<link rel="stylesheet" href="css/pos.css">
<title>
Admin Dashboard
</title>
</head>

<body>

<div class="sidenav">
	<img id="logo" src="image/logo.png" alt="Logo">
		<h2 style="text-align: center;"> Pharmanest Essential </h2>

		<div class="menu">
			<i class="fas fa-dashboard"></i>
			<a href="adminpage.php">Dashboard</a>
		</div>
		<div class="menu">
			<i class="fas fa-shopping-bag"></i>
			<a href="shop.php">Shop</a>
		</div>
		<div class="menu">
			<i class="fas fa-user"></i>
			<a href="customerpage.php">Customer</a>
		</div>
		<div class="menu">
			<i class="fas fa-cart-shopping"></i>
			<a href="order.php">Order</a>
		</div>
		<div class="menu">
			<i class="fas fa-cash-register"></i>
			<a href="pos.php">Point of Sale</a>
		</div>
		<div class="menu">
			<i class="fas fa-warehouse"></i>
			<a href="inventory.php">Inventory</a>
		</div>
		<div class="menu">
			<i class="fas fa-chart-line"></i>
			<a href="salesReport.php">Sales Report</a>
		</div>
		<div class="menu">
			<i class="fas fa-users"></i>
			<a href="employee.php">Employee</a>
		</div>
</div>
			
	<div class="topnav">
		<a href="logout.php">Logout(Logged in as Admin)</a>
	</div>

	<center>
	<div class="head">
	<h2> ADMIN DASHBOARD </h2>
	</div>
	<br>
	<div class="titolo">
		<h2>POINT OF SALE</h2>
	</div>
	</center>
<div class="search">
	<form method="post">
		<input id="input" type="text" name="search" placeholder="Search for products"> 
		<button id="searchButton" type="submit">Search</button>
	</form>
</div>

<div class="form">
					
					<div class="column">

					<label for="medCode">Medicine Code:</label>
					<input class="inputs" type="number" name="medCode">
					
					<label for="mdname">Medicine Name:</label>
					<input class="inputs" type="text" name="mdname">
					
					</div>

					<div class="column">
					
					<label for="mcat">Category:</label>
					<input class="inputs" type="text" name="mcat">
					
					<label for="mloc">Location:</label>
					<input class="inputs" type="text" name="mloc">
					
					</div>
					<div class="column">
					
					<label for="mqty">Quantity Available:</label>
					<input class="inputs" type="number" name="mqty">
					
					<label for="mprice">Price of One Unit:</label>
					<input class="inputs" type="number" name="mprice">
					
					</div>
					
					<div class="column">
					<label for="mcqty">Quantity Required:</label>
					<input class="inputs" type="number" name="mcqty">
					<label for="saledate">Date:</label>
					<input class="inputs" type="date" name="saledate">
					<br>
					<center>
					<input style="width: 30%; padding: 2%;" class="inputs" type="submit" name="add" value="Save">
					</div>
					</center>
</div>

</body>
</html>