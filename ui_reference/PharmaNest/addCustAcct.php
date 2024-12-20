<!DOCTYPE html>
<html>

<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" type="text/css" href="css/adminpage.css">
<link rel="stylesheet" href="css/addCustAcct.css">

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

    <div class="one">
		<div class="row">
			<form>
				<div class="column">
	
					<p>
						<label for="cfname">First Name:</label><br>
						<input type="text" name="cfname">
					</p>
					<p>
						<label for="clname">Last Name:</label><br>
						<input type="text" name="clname">
					</p>
					<p>
						<label for="birth">Date of Birth:</label><br>
						<input type="text" name="birth">
					</p>
				
				</div>
				<div class="column">
                <p>
						<label for="sex">Sex: </label><br>
						<select id="sex" name="sex">
								<option value="selected">Select</option>
								<option>Female</option>
								<option>Male</option>
								<option>Others</option>
						</select>
					</p>

					<p>
						<label for="phno">Phone Number: </label><br>
						<input type="number" name="phno">
					</p>
					<p>
						<label for="emid">Email ID:</label><br>
						<input type="text" name="emid">
					</p>
				</div>
				
			
			<input type="submit" name="add" value="Submit">
			</form>
            </div>
            </div>

</body>
</html>