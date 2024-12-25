<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="../../../assets/styles/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="../../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="../../../assets/scripts/common_fx.js"></script>
        <title>
        Admin Dashboard
        </title>
    </head>

    <body>
        <?php
            session_start();
            $current_page_title = "list of medicines";
            include '../../components/unauth_redirection.php';
        ?>
        <?php include '../../components/side_nav.php'; ?>
                
        <?php include '../../components/top_nav.php'; ?>  

        <?php
            include('../../../utils/connect.php');
            if (isset($_GET['query'])){
                $query = $_GET['query'];
                $sqlGetMedicines = "SELECT * FROM medicine
                            WHERE name LIKE '%$query%'
                            ORDER BY id DESC";
            } else {
                $query = NULL;
                $sqlGetMedicines = "SELECT * FROM medicine
                            ORDER BY id DESC";
            }
            
            $result = mysqli_query($conn,$sqlGetMedicines);
        ?>

        <div class="search">
            <form method="GET" action="">
                <input type="text" value="<?php echo $query; ?>" name="query" placeholder="Search anything...">
                <button class="btns" type="submit">Search</button>
                <button type="button" class="btns" onclick="redirectToPage('add')">Add Medicine</button>  
            </form>
        </div>

        <div class="table">
            <?php
                if (isset($_SESSION["message_string"])) {
                    ?>
                        <div class="alert alert-<?php echo $_SESSION["message_class"] ?>">
                            <?php 
                            echo $_SESSION["message_string"];
                            ?>
                        </div>
                    <?php
                    unset($_SESSION["message_string"]);
                    unset($_SESSION["message_class"]);
                }
            ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Current Number of Stocks</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    while($data = mysqli_fetch_array($result)){
                    ?>
                    <tr>
                        
                        <td><?php echo $data["name"];?></td>
                        <td>₱<?php echo $data["price"];?></td>
                        <td><?php echo $data["current_quantity"];?></td>
                        <td>
                            <a href="./edit/index.php?medicine_id=<?php echo $data["id"]; ?>">Edit</a>
                            | <a href="../stock/movement/index.php?type=in&medicine_id=<?php echo $data["id"]; ?>">Stock In</a>
                            | <a href="../stock/movement/index.php?type=out&medicine_id=<?php echo $data["id"]; ?>">Stock Out</a>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>

            </table>
        </div>

        <script>
            function redirectToPage(page) {
                window.location.href = './'+page+'/index.php';
            };

            window.onload = function() {
                setActivePage("nav_inventory");
            };
        </script>
    </body>
</html>