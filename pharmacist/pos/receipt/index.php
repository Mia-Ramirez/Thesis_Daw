<?php
    if (!isset($_GET['transaction_id'])) {
        header("Location:../../../page/404.php");
        exit;
    };

    session_start();
    $doc_root = $_SESSION["DOC_ROOT"];
    if (!isset($_SESSION['receipt_displayed_from'])){
        header("Location:../../index.php");
        exit;
    };

    $receipt_displayed_from = $_SESSION['receipt_displayed_from'];
    // PHP can dynamically generate content, if needed.
    $content = "<center><h1>Pharmanest</h1></center>";

    include($doc_root.'/utils/connect.php');
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
    Transacted By: ".$row['first_name']." ".$row['last_name']."<br/>
    ";

    $content .= "--------------------------------------------------------------------------------<br/>";
    $content .= "<table><thead><tr><th>QTY</th><th>PRODUCT</th><th>PRICE</th><th>SUBTOTAL</th></tr></thead>";
    $content .= "<tbody>";
    
    $subtotal = 0;
    $total_discount = 0;
    while($data = mysqli_fetch_array($product_lines)){
        $content .= "<tr>";
        $line_subtotal = $data['price'] * $data['qty'];

        $discount_rate = 0;
        if ($selected_discount && ($selected_discount == $data['applicable_discounts'] || $data['applicable_discounts'] == 'Both')){
            $discount_rate = 0.2;
        };

        $line_discount = $data['price'] * (1 - $discount_rate);
        
        $subtotal += $line_subtotal;
        $total_discount += ($line_subtotal - ($line_discount * $data['qty']));
        $content .= "<td>".$data['qty']."</td><td>".$data['product_name']."</td><td>₱".$data['price']."</td><td>₱".$line_subtotal."</td></tr>";
        
    };
    $content .= "</tbody>";
    $content .= "</table>";

    $content .= "--------------------------------------------------------------------------------<br/>";
    $total = $subtotal - $total_discount;
    $content .= "No. of Item(s): ".$product_lines->num_rows."<br/>";
    $content .= "Subtotal: ₱".number_format($subtotal, 2)."<br/>";
    $content .= "Discount: ₱".number_format($total_discount, 2)."<br/>";
    $content .= "Total: ₱".number_format($total, 2)."<br/>";
    $content .= "Amount Paid: ₱".number_format($row['amount_paid'], 2)."<br/>";
    $content .= "Change: ₱".number_format(($row['amount_paid'] - $total), 2)."<br/>";

    if (!is_null($row['order_id'])){
        $date = new DateTime($row["date_ordered"]);

        // Format the DateTime object to 'Y-m-d h:i A' (12-hour format with AM/PM)
        $formattedDate = $date->format('F j, Y h:i A');
        $content .= "--------------------------------------------------------------------------------<br/>";
        $content .= "Order Reference Number: ".$row['order_reference']."<br/>";
        $content .= "Date Ordered: ".$formattedDate."<br/>";
    };

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmanest Sales Receipt</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

    <div id="content">
        <div id="printArea">
            <!-- This is the specific div that will be printed -->
            <p><?php echo $content; ?></p>
        </div>
        <div id="printCustomerArea" style="display: none">
            <!-- This is the specific div that will be printed -->
            <p><?php echo $content; ?></p>
            <span>------------------- CUSTOMER's COPY -------------------</span><br/>
        </div>
    </div>

    <?php
        if ($receipt_displayed_from == 'pos'){
    ?>
    <button class="receipt_button" id="printButton">Print</button>
    <button class="receipt_button" id="printCustomerCopyButton">Print Customer's Copy</button>
    <?php
        };
    ?>
    <button class="receipt_button" id="closeButton">Close</button>

    <script src="script.js"></script>

</body>
</html>
