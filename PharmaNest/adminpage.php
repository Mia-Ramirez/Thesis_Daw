<!DOCTYPE html>
<html>

<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" type="text/css" href="css/adminpage.css">

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

	<div class="pot">
	<div class="main">
				<h2 style="text-align: center;">Today's Report</h2>
				<br>
				<div class="grid">
					<div class="boxs"><h4>Total Sales</h4>
					<h3>0</h3>
					</div>
					<div class="boxs"><h4>Total Purchase</h4>
					<h3>0</h3>
					</div>
				</div>
				</div>
				<div class="onecard">
					<h2>0</h2>
					<p>New Orders</p>
                </div>
				<div class="twocard">
                    <h2>0</h2>
                    <p>Meds Shortage</p>
                </div>
				<div class="threecard">
			  		 <h2>0</h2>
                    <p>Recovery Request</p>
            	</div>	
				<div class="fourcard">
                    <h2>0</h2>
                    <p>Soon to Expire</p>
                </div>
				<div class="fivecard">
			  		 <h2>0</h2>
					 <p>Not Moving Meds</p>
                </div>
	

</body>
</html>