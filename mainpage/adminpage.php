<!DOCTYPE html>
<html>

<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" type="text/css" href="css/admin.css">
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
			<a href="#">Dashboard</a>
		</div>
		<div class="menu">
			<i class="fas fa-shopping-bag"></i>
			<a href="#">Shop</a>
		</div>
		<div class="menu">
			<i class="fas fa-user"></i>
			<a href="#">Customer</a>
		</div>
		<div class="menu">
			<i class="fas fa-cart-shopping"></i>
			<a href="#">Order</a>
		</div>
		<div class="menu">
			<i class="fas fa-cash-register"></i>
			<a href="#">Point of Sale</a>
		</div>
		<div class="menu">
			<i class="fas fa-warehouse"></i>
			<a href="#">Inventory</a>
		</div>
		<div class="menu">
			<i class="fas fa-chart-line"></i>
			<a href="#">Sales Report</a>
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
		<div class="pots">
		<div id="two" class="card">
					<h2>0</h2>
                    <p>Not Moving Meds</p>
                </div>
				<div id="four" class="card">
                    <h2>0</h2>
                    <p>Meds Shortage</p>
                </div>
		</div>
				<div id="three" class="card">
                    <h2>0</h2>
                    <p>New Orders</p>
                </div>
				<div id="one" class="card">
			  		 <h2>0</h2>
                    <p>Soon to Expire</p>
                </div>
	</div>
	<div class="main">
	<div class="report">
				<h2 style="text-align: center;">Today's Report</h2>
				<div class="grid">
					<div class="boxs"><h4>Total Sales</h4>
					<h3>0</h3>
					</div>
					<div class="boxs"><h4>Total Purchase</h4>
					<h3>0</h3>
					</div>
				</div>
				</div>
</body>
		</div>
	
</html>