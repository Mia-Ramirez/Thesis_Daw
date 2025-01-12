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
        <script src="<?php echo $base_url;?>assets/scripts/common_fx.js"></script>
        <?php include '../../components/title.php'; ?>
    </head>

    <body>
        <?php include '../../components/unauth_redirection.php'; ?>

        <?php include '../../components/side_nav.php'; ?>

        <?php
            $current_page_title = "sales history";
            include '../../components/top_nav.php';
        ?> 

<?php
            include('../../../utils/connect.php');

            $sqlGetOrderHistory = "SELECT
                                h.remarks AS history_remarks,
                                h.date_recorded,
                                t.reference_number AS reference_name,
                                u.username,
                                t.id AS transaction_id
                            FROM history h
                            INNER JOIN transaction t ON h.object_id=t.id
                            INNER JOIN user u ON h.user_id=u.id
                            WHERE h.object_type='transaction'
            ";

            $offset = '0';
            if (isset($_GET['page_no'])){
                $offset = (int)$_GET['page_no'] * 10;
            };

            $sqlGetOrderHistory .= " ORDER BY h.id DESC LIMIT ".$offset.", 10";
            
            $result = mysqli_query($conn,$sqlGetOrderHistory);
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
                        <th>Transaction Number</th>
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
                        
                        <td><a href="../details/index.php?transaction_id=<?php echo $data["transaction_id"]; ?>"><?php echo $data["reference_name"];?></a></td>
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
                setActivePage("nav_sales_report");
            };
        </script>
    </body>
</html>