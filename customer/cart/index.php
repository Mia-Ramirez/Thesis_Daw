<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHARMANEST ESSENTIAL</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet" href="../styles.css">
        <script src="../../assets/scripts/common_fx.js"></script>
    </head>
    <body class="body">
        <?php
            session_start();
            include '../components/unauth_redirection.php';
            $base_url = $_SESSION["BASE_URL"];



        ?>
        
        <?php include '../components/navbar.php'; ?> 

        <div class="container">
        <!-- Product Table -->
            <div class="cart-left">
                <div class="card">
                    <h2>Product List</h2>
                    <table id="productTable">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Discounted Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Sample Products -->
                            <tr>
                                <td><input type="checkbox" class="product-checkbox" data-index="0"></td>
                                <td>Paracetamol 500mg</td>
                                <td class="price">10</td>
                                <td class="discounted-price">10</td>
                                <td><input type="number" value="1" class="quantity" data-index="0" min="1"></td>
                                <td class="total">10</td>
                                <td><a href="#">Delete</a></td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="product-checkbox" data-index="1"></td>
                                <td>Ibuprofen 400mg</td>
                                <td class="price">15</td>
                                <td class="discounted-price">15</td>
                                <td><input type="number" value="1" class="quantity" data-index="1" min="1"></td>
                                <td class="total">15</td>
                                <td><a href="#">Delete</a></td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="product-checkbox" data-index="2"></td>
                                <td>Amoxicillin 500mg</td>
                                <td class="price">25</td>
                                <td class="discounted-price">25</td>
                                <td><input type="number" value="1" class="quantity" data-index="2" min="1"></td>
                                <td class="total">25</td>
                                <td><a href="#">Delete</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Summary -->
            <div class="cart-right">
                <div class="card">
                    <h2>Summary</h2>
                    <div class="discount">
                        <label for="discount">Discount</label>
                        <select id="discount">
                            <option value="0">No Discount</option>
                            <option value="0.2">Senior Citizen (20%)</option>
                            <option value="0.3">PWD (30%)</option>
                        </select>
                    </div>
                    <div id="summary">
                        <p>Subtotal: <span id="subtotal">0</span></p>
                        <p>Discount: <span id="discountAmount">0</span></p>
                        <p>Total: <span id="total">0</span></p>
                    </div>
                    <button id="checkout">Checkout</button>
                </div>
            </div>
        </div>

        <script src="script.js"></script>

        <script src="../script.js"></script>

        <script>
            window.onload = function() {
                setActivePage("nav_cart");
            };
        </script>
    </body>
</html>