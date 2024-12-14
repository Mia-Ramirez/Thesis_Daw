<?php
    $base_url = $_SESSION["BASE_URL"];
?>
<div class="sidenav">
	<img id="logo" src=<?php echo $base_url."assets/images/logo.png";?> alt="Logo">
    <h2 style="text-align: center;"> Pharmanest Essential </h2>
    
    <div id="nav_dashboard" class="menu">
        <i class="fas fa-dashboard"></i>
        <a href=<?php echo $base_url."admin/dashboard/index.php";?>>Dashboard</a>
    </div>
    <div id="nav_shop" class="menu">
        <i class="fas fa-shopping-bag"></i>
        <a href=<?php echo $base_url."admin/shop/index.php";?>>Shop</a>
    </div>
    <div id="nav_customer" class="menu">
        <i class="fas fa-user"></i>
        <a href=<?php echo $base_url."admin/customer/index.php";?>>Customer</a>
    </div>
    <div id="nav_order" class="menu">
        <i class="fas fa-cart-shopping"></i>
        <a href=<?php echo $base_url."admin/order/index.php";?>>Order</a>
    </div>
    <div id="nav_pos" class="menu">
        <i class="fas fa-cash-register"></i>
        <a href=<?php echo $base_url."admin/pos/index.php";?>>Point of Sale</a>
    </div>
    <div id="nav_inventory" class="menu">
        <i class="fas fa-warehouse"></i>
        <a href=<?php echo $base_url."admin/inventory/index.php";?>>Inventory</a>
    </div>
    <div id="nav_sales_report" class="menu">
        <i class="fas fa-chart-line"></i>
        <a href=<?php echo $base_url."admin/sales_report/index.php";?>>Sales Report</a>
    </div>
    <div id="nav_employee" class="menu">
        <i class="fas fa-users"></i>
        <a href=<?php echo $base_url."admin/employee/index.php";?>>Employee</a>
    </div>
</div>