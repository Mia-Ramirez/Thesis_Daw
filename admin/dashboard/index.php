<?php
    session_start();
    $base_url = $_SESSION["BASE_URL"];
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="<?php echo $base_url;?>assets/scripts/common_fx.js"></script>
        <?php include '../components/title.php'; ?>
    </head>

    <body>
        <?php include '../components/unauth_redirection.php'; ?>

        <?php include '../components/side_nav.php'; ?>
        
        <?php
            $current_page_title = "dashboard";
            include '../components/top_nav.php';
        ?>



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
			
            <div class="card">
                <h2>0</h2>
                <p>New Orders</p>
            </div>

            <div class="card">
                <h2>0</h2>
                <p>Meds Shortage</p>
            </div>

            <div class="card">
                <h2>0</h2>
                <p>Soon to Expire</p>
            </div>

            <div class="card">
                <h2>0</h2>
                <p>Not Moving Meds</p>
            </div>
        </div>
        
        <script>
            window.onload = function() {
                setActivePage("nav_dashboard");
            };
        </script>
    </body>
</html>