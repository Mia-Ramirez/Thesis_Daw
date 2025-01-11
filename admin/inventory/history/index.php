<?php
    session_start();
    $base_url = $_SESSION["BASE_URL"];
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="../../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="../../../assets/scripts/common_fx.js"></script>
        <?php include '../../components/title.php'; ?>
    </head>

    <body>
        <?php include '../../components/unauth_redirection.php'; ?>

        <?php include '../../components/side_nav.php'; ?>

        <?php
            $current_page_title = "stock history";
            include '../../components/top_nav.php';
        ?> 

<?php
            include('../../../utils/connect.php');

            $sqlGetInventoryHistory = "SELECT
                                h.remarks AS history_remarks,
                                h.date_recorded,
                                m.name AS reference_name,
                                u.username
                            FROM history h
                            INNER JOIN medicine m ON h.object_id=m.id
                            INNER JOIN user u ON h.user_id=u.id
                            WHERE h.object_type='medicine'   
            ";

            $filter_str = "";
            if (isset($_GET['medicine_id'])){
                $medicine_id = $_GET['medicine_id'];
                $filter_str = " AND m.id=".$medicine_id;
            };
            
            $offset = '0';
            if (isset($_GET['page_no'])){
                $offset = (int)$_GET['page_no'] * 10;
            };

            $sqlGetInventoryHistory .= $filter_str ." ORDER BY h.id DESC LIMIT ".$offset.", 10";
            
            $result = mysqli_query($conn,$sqlGetInventoryHistory);
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
                        <th>Medicine</th>
                        <th>Date Recorded</th>
                        <th>Recorded By</th>
                        <th>Remarks</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    while($data = mysqli_fetch_array($result)){
                        $date = new DateTime($data["date_recorded"]);

                        // Format the DateTime object to 'Y-m-d h:i A' (12-hour format with AM/PM)
                        $formattedDate = $date->format('F j, Y h:i A');
                        
                        if (strpos($data['username'], "_") !== false){
                            list($first_name, $last_name, $date_created) = explode("_", $data['username']);
                        } else {
                            $first_name = "Super";
                            $last_name = "Admin";
                        };
                        
                    ?>
                    <tr>
                        
                        <td><?php echo $data["reference_name"];?></td>
                        <td><?php echo $formattedDate;?></td>
                        <td><?php echo $first_name." ".$last_name;?></td>
                        <td><?php echo $data['history_remarks']; ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>

            </table>
        </div>

        <script>
            window.onload = function() {
                setActivePage("nav_inventory");
            };
        </script>
    </body>
</html>