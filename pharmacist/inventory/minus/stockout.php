<?php
// Database connection
$conn = mysqli_connect("localhost", "username", "password", "pharmanest_db");

// Create table if not exists
$sql = "CREATE TABLE IF NOT EXISTS stock_outs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    date_reported DATE NOT NULL,
    status VARCHAR(50) DEFAULT 'pending'
)";
mysqli_query($conn, $sql);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $quantity = (int)$_POST['quantity'];
    $date = date('Y-m-d');
    
    $sql = "INSERT INTO stock_outs (item_name, quantity, date_reported) 
            VALUES ('$item_name', $quantity, '$date')";
    mysqli_query($conn, $sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ValueMed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        button:hover {
            background-color: #45a049;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f8f9fa;
        }
        
        .week-selector {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Weekly Stock Out Tracker</h1>
        
        <!-- Add new stock out form -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="item_name">Item Name:</label>
                <input type="text" id="item_name" name="item_name" required>
            </div>
            
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" required min="1">
            </div>
            
            <button type="submit">Report Stock Out</button>
        </form>
        
        <!-- Week selector -->
        <div class="week-selector">
            <label for="week">Select Week:</label>
            <select id="week" onchange="updateTable()">
                <!-- Will be populated by JavaScript -->
            </select>
        </div>
        
        <!-- Stock out table -->
        <table id="stockOutTable">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Date Reported</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM stock_outs ORDER BY date_reported DESC";
                $result = mysqli_query($conn, $sql);
                
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['item_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['date_reported']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php
// get_stock_outs.php
header('Content-Type: application/json');

$conn = mysqli_connect("localhost", "username", "password", "pharmanest_db");

$week_start = mysqli_real_escape_string($conn, $_GET['week']);
$week_end = date('Y-m-d', strtotime($week_start . ' +6 days'));

$sql = "SELECT * FROM stock_outs 
        WHERE date_reported BETWEEN '$week_start' AND '$week_end' 
        ORDER BY date_reported DESC";

$result = mysqli_query($conn, $sql);
$items = array();

while ($row = mysqli_fetch_assoc($result)) {
    $items[] = array(
        'item_name' => $row['item_name'],
        'quantity' => $row['quantity'],
        'date_reported' => $row['date_reported'],
        'status' => $row['status']
    );
}

echo json_encode($items);
mysqli_close($conn);
?>

    <script>
        // Populate week selector
        function populateWeeks() {
            const select = document.getElementById('week');
            const today = new Date();
            const oneWeek = 7 * 24 * 60 * 60 * 1000;
            
            for (let i = 0; i < 52; i++) {
                const weekStart = new Date(today - (i * oneWeek));
                const weekEnd = new Date(weekStart.getTime() + (6 * 24 * 60 * 60 * 1000));
                const option = document.createElement('option');
                option.value = weekStart.toISOString().split('T')[0];
                option.text = `Week of ${weekStart.toLocaleDateString()} - ${weekEnd.toLocaleDateString()}`;
                select.appendChild(option);
            }
        }

        // Update table based on selected week
        function updateTable() {
            const selectedWeek = document.getElementById('week').value;
            fetch(`get_stock_outs.php?week=${selectedWeek}`)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.querySelector('#stockOutTable tbody');
                    tbody.innerHTML = '';
                    
                    data.forEach(item => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${item.item_name}</td>
                            <td>${item.quantity}</td>
                            <td>${item.date_reported}</td>
                            <td>${item.status}</td>
                        `;
                        tbody.appendChild(row);
                    });
                });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            populateWeeks();
            updateTable();
        });
    </script>
</body>
</html>