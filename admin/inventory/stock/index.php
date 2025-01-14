<?php
    session_start();
    $base_url = $_SESSION["BASE_URL"];
    $doc_root = $_SESSION["DOC_ROOT"];
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>assets/styles/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="../../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="<?php echo $base_url;?>assets/scripts/common_fx.js"></script>
        <?php include '../../components/title.php'; ?>
    </head>

    <body>
        <?php include '../../components/unauth_redirection.php'; ?>
        
        <?php include '../../components/side_nav.php'; ?>
        
        <?php
            $current_page_title = "low stocks";
            include '../../components/top_nav.php';
        ?> 
        
        <?php
            include($doc_root.'/utils/connect.php');
            
            $sqlGetLowStockProducts = "SELECT
                                            id,
                                            name,
                                            current_quantity,
                                            maintaining_quantity FROM product
                                        WHERE current_quantity < maintaining_quantity
                                        ORDER BY current_quantity ASC, maintaining_quantity ASC";

            
            $result = mysqli_query($conn,$sqlGetLowStockProducts);
        ?>

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
                        <th>Product</th>
                        <th>Current Number of Stock</th>
                        <th>Maintaining Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    while($data = mysqli_fetch_array($result)){
                    ?>
                    <tr>
                        
                        <td><?php echo $data["name"];?></td>
                        <td><?php echo $data["current_quantity"];?></td>
                        <td><?php echo $data["maintaining_quantity"];?></td>
                        <td>
                            <a href="./add/index.php?product_id=<?php echo $data["id"]; ?>"><i class="button-icon fas fa-plus" title="Stock In"></i></a>
                            <a href="../history/index.php?product_id=<?php echo $data["id"]; ?>"><i class="button-icon fas fa-clock-rotate-left" title="View History"></i></a>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>

            </table>
        </div>

        <script>
            // function redirectToPage(page) {
            //     window.location.href = './'+page+'/index.php';
            // };
            
            window.onload = function() {
                setActivePage("nav_inventory");
            };
        </script>
    </body>
</html>