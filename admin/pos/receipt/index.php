<?php
    // PHP can dynamically generate content, if needed.
    $content = "<center><h1>Pharmanest</h1></center>";

    include('../../../utils/connect.php');
    if (!isset($_GET['transaction_id'])) {
        header("Location:../../../page/404.php");
        exit;
    };

    $transaction_id = $_GET['transaction_id'];
    $sqlGetTransaction = "SELECT
                            co.reference_number AS order_reference,
                            co.date_ordered,
                            t.transaction_date,
                            t.selected_discount,
                            t.amount_paid,
                            e.first_name,
                            e.last_name,
                            t.receipt_reference,
                            t.reference_number,
                            co.id AS order_id
                        FROM transaction t
                        LEFT JOIN customer_order co ON t.order_id=co.id
                        INNER JOIN employee e ON t.employee_id=e.id
                        WHERE t.id=$transaction_id";

    $transaction_result = mysqli_query($conn,$sqlGetTransaction);
    if ($transaction_result->num_rows == 0){
        header("Location:../../../page/404.php");
    };

    $row = mysqli_fetch_array($transaction_result);

    $sqlGetProductLines = "SELECT 
                            p.name AS product_name,
                            price,
                            applicable_discounts,
                            prescription_is_required,
                            photo,
                            qty
                        FROM product_line pl
                        INNER JOIN product p ON pl.product_id=p.id
                        WHERE pl.transaction_id=$transaction_id
    ";
    $product_lines = mysqli_query($conn,$sqlGetProductLines);

    $selected_discount = $row['selected_discount'];
    $date = new DateTime($row["transaction_date"]);

    // Format the DateTime object to 'Y-m-d h:i A' (12-hour format with AM/PM)
    $formattedDate = $date->format('F j, Y h:i A');

    $content .= "
    <br/><br/>
    Sales Receipt: ".$row['receipt_reference']."<br/>
    Transaction Number: ".$row['reference_number']."<br/>
    Transaction Date: ".$formattedDate."<br/>
    Transaction By: ".$row['first_name']." ".$row['last_name']."<br/>
    ";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Specific Division</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

    <div id="content">
        <h1>Printable Content</h1>
        <div id="printArea">
            <!-- This is the specific div that will be printed -->
            <p><?php echo $content; ?></p>
        </div>
    </div>

    <button id="printButton">Print</button>
    <button id="printCustomerCopyButton">Print Customer's Copy</button>

    <script src="script.js"></script>

</body>
</html>
