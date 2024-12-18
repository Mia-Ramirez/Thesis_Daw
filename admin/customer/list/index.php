<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
            $current_page_title = "list of customers";
            include '../../components/unauth_redirection.php';
        ?>
        <?php include '../../components/side_nav.php'; ?>
                
        <?php include '../../components/top_nav.php'; ?>  

        <?php
            include('../../../utils/connect.php');
            $sqlGetLogs = "SELECT c.first_name, c.last_name, c.address, c.contact_number, u.email FROM customer c
                            LEFT JOIN user u ON c.user_id=u.id
                            ORDER BY c.id DESC";
    
            $result = mysqli_query($conn,$sqlGetLogs);
        ?>

        <div class="search">
            <form method="GET" action="">
                <input type="text" name="query" placeholder="Search anything..." required>
                <button class="btns" type="submit">Search</button>
            </form>
        </div>

        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Address</th>
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
                        <td><?php echo $data["address"];?></td>
                        <td><?php echo $data["contact_number"];?></td>
                        <td><?php echo $data["email"];?></td>
                        <td>

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
                setActivePage("nav_customer");
            };
        </script>
    </body>
</html>