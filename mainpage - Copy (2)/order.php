<!DOCTYPE html>
<html>

<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" type="text/css" href="css/admin.css">
<link rel="stylesheet" href="css/order.css">

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
		<h4>Welcome, Admin!</h4>
	</div>
	</center>

	<div class="image">
	<div class="history">
		<h3>HISTORY</h3>
		<img class="pic" src="image/history.png" alt="history">
	</div>
	<div class="cancel">
		<h3>CANCELLED</h3>
		<img class="pic" src="image/cancel.png" alt="recovery">
	</div>
	<div class="pickup">
		<h3>FOR PICKUP</h3>
		<img class="pic" src="image/pickup.png" alt="recovery">
	</div>
	<div class="new">
		<h3>NEW ORDER</h3>
		<img class="pic" src="image/new.png" alt="profile">
	</div>
</div>

</body>
</html>