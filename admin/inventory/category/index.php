<?php
    session_start();
    $base_url = $_SESSION["BASE_URL"];
?>
<!DOCTYPE html>
<html>
    <head>

        <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="../../../assets/styles/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="../../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">

        <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
        <script src="../../../assets/scripts/common_fx.js"></script>

        <title>
        Admin Dashboard
        </title>

    </head>

    <body>
        <?php include '../../components/unauth_redirection.php'; ?>
        
        <?php include '../../components/side_nav.php'; ?>
        
        <?php
            $current_page_title = "list of categories";
            include '../../components/top_nav.php';
        ?>

        <?php
            include('../../../utils/connect.php');
            if (isset($_GET['query'])){
                $query = $_GET['query'];
                $sqlGetCategories = "SELECT id AS category_id, name AS category_name FROM category
                            WHERE name LIKE '%$query%'
                            ORDER BY id DESC";
            } else {
                $query = NULL;
                $sqlGetCategories = "SELECT id AS category_id, name AS category_name FROM category
                            ORDER BY id DESC";
            }
            
            $result = mysqli_query($conn,$sqlGetCategories);

        ?>

        <div class="search">
            <form method="GET" action="">
                <input type="text" value="<?php echo $query; ?>" name="query" placeholder="Search anything...">
                <button class="btns" type="submit">Search</button>
                <button type="button" class="btns" onclick="openModal('add')">Add Category</button>  
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
            <table id="categoryTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    while($data = mysqli_fetch_array($result)){
                    ?>
                    <tr>
                        <td><?php echo $data["category_name"];?></td>
                        <td>
                            <a href="#" onclick="<?php echo "openModal('edit', '".$data['category_id']."', '".$data['category_name']."')"; ?>">Edit</a>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                    
                </tbody>
            </table>
            
        </div>
        
        <!-- Add Category Modal -->
        <div id="categoryModal" class="modal">
            <div class="modal-content">
                <h3 id="modalTitle">Add Category</h3>
                <form method="POST" action="process.php">
                    <input type="hidden" name="id" id="categoryId">
                    <label for="categoryName">Category Name:</label>
                    <input type="text" name="category_name" id="categoryName" required>
                    <button class="btns" style="padding: 5px; margin: 5px;" type="submit" name="save_category">Save</button>
                    <button class="btns" style="padding: 5px; margin: 5px;" type="button" onclick="closeModal()">Cancel</button>
                </form>
                
            </div>
        </div>

        <script src="script.js"></script>
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