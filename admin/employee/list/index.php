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
            $current_page_title = "list of employees";
            include '../../components/top_nav.php';
        ?>

        <?php
            include($doc_root.'/utils/connect.php');
            $sqlGetEmployees = "SELECT
                                e.first_name,
                                e.last_name,
                                e.job_title,
                                e.contact_number,
                                u.email,
                                e.id AS employee_id,
                                e.user_id
                            FROM employee e
                            INNER JOIN user u ON e.user_id=u.id
                            WHERE u.is_active=1 AND e.job_title!='Not Applicable'";
            
            $filter_str = "";
            
            $query = NULL;
            if (isset($_GET['query'])){
                $query = $_GET['query'];
                $filter_str = " AND CONCAT(e.first_name, e.last_name, e.job_title, e.contact_number, COALESCE(u.email, '')) LIKE '%$query%'";
            };

            $sqlGetEmployees .= $filter_str." ORDER BY e.id DESC";
            
            $result = mysqli_query($conn,$sqlGetEmployees);
        ?>

        <div class="search">
            <form method="GET" action="">
                <input type="text" value="<?php echo $query; ?>" name="query" placeholder="Search anything...">
                <button class="btns" type="submit">Search</button>
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
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Job Position/Title</th>
                        <th>Contact Number</th>
                        <th>Email Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    while($data = mysqli_fetch_array($result)){
                    ?>
                    <tr>
                        
                        <td><?php echo $data["first_name"];?></td>
                        <td><?php echo $data["last_name"];?></td>
                        <td><?php echo $data["job_title"];?></td>
                        <td><?php echo $data["contact_number"];?></td>
                        <td><?php echo $data["email"];?></td>
                        <td>
                            <a href="../edit/index.php?employee_id=<?php echo $data["employee_id"]; ?>"><i class="button-icon fas fa-pen-to-square" title="Edit"></i></a>
                            <?php if ($data["user_id"] != $_SESSION["user_id"]){ echo "<a href='../archive/index.php?employee_id=".$data["employee_id"]."'><i class='button-icon fas fa-box-archive' title='Archive'></i></a>";}?>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>

            </table>
        </div>

        <script>
            window.onload = function() {
                setActivePage("nav_employee");
            };
        </script>
    </body>
</html>