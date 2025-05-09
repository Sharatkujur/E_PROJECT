<?php
session_start();
include("db.php");

// Check if order ID is passed in the URL
if (!isset($_GET['order_id'])) {
    echo "<script>alert('No order selected.');</script>";
    echo "<script>window.location.href='manage-orders.php';</script>";
    exit();
}

$order_id = intval($_GET['order_id']);

// Fetch order details including product_id (from orders table)
$order_query = "
    SELECT o.*, c.customer_name, c.customer_email, c.customer_address, c.customer_contact
    FROM orders o
    JOIN customer c ON o.c_id = c.customer_email
    WHERE o.order_id = $order_id
";
$order_result = mysqli_query($con, $order_query);

if (!$order_result || mysqli_num_rows($order_result) == 0) {
    echo "<script>alert('Order not found.');</script>";
    echo "<script>window.location.href='manage-orders.php';</script>";
    exit();
}

$order = mysqli_fetch_assoc($order_result);

// Fetch products based on the product_id from orders table
$product_query = "
    SELECT od.p_id, od.p_name, od.price, od.qty, (od.price * od.qty) AS total_price
    FROM ordersdetails od
    WHERE od.o_id = $order_id
";
$product_result = mysqli_query($con, $product_query);

// Initialize a variable for the grand total
$grand_total = 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 20px;
        }

        .order-summary {
            background-color: #f1f1f1;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .order-summary h2 {
            font-size: 1.6em;
            color: #007bff;
            margin-bottom: 10px;
        }

        .order-summary p {
            font-size: 1.1em;
            line-height: 1.6;
        }

        .order-products {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            display: none; /* Initially hide the products section */
        }

        .order-products table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .order-products th,
        .order-products td {
            text-align: left;
            padding: 12px 15px;
            border: 1px solid #ddd;
        }

        .order-products th {
            background-color: #007bff;
            color: #fff;
        }

        .order-products tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .order-products tr:hover {
            background-color: #eaeaea;
        }

        .grand-total {
            font-size: 1.4em;
            font-weight: bold;
            margin-top: 20px;
            margin-right: 20px;
            text-align: right;
            color: black;
        }

        .actions {
            text-align: center;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 25px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.2em;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 0.9em;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Order Details</h1>

        <div class="order-summary">
            <h2>Customer Information</h2>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($order['customer_address']); ?></p>
            <p><strong>Contact:</strong> <?php echo htmlspecialchars($order['customer_contact']); ?></p>
        </div>

        <div class="order-summary">
            <h2>Order Information</h2>
            <p><strong>Order ID:</strong> <?php echo $order['order_id']; ?></p>
            <p><strong>Date:</strong> <?php echo $order['date']; ?></p>
            <p><strong>Total Price:</strong> &#8377;<?php echo number_format($order['order_price'], 2); ?></p>
        </div>

        <div class="actions">
            <!-- Button to toggle products visibility -->
            <button class="btn" onclick="toggleProducts()">Order Info</button>
        </div>

        <div class="order-products" id="productsSection">
            <h2>Products in this Order</h2>
            <?php if (mysqli_num_rows($product_result) > 0) { ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        while ($product = mysqli_fetch_assoc($product_result)) {
                            echo "<tr>";
                            echo "<td>" . $i++ . "</td>";
                            echo "<td>" . htmlspecialchars($product['p_name']) . "</td>";
                            echo "<td>&#8377;" . number_format($product['price'], 2) . "</td>";
                            echo "<td>" . $product['qty'] . "</td>";
                            echo "<td>&#8377;" . number_format($product['total_price'], 2) . "</td>";
                            echo "</tr>";

                            // Add the total price of the product to the grand total
                            $grand_total += $product['total_price'];
                        }
                        ?>
                    </tbody>
                </table>
                <div class="grand-total">
                    <strong>Grand Total: &#8377;<?php echo number_format($grand_total, 2); ?></strong>
                </div>
            <?php } else { ?>
                <p>No products found for this order.</p>
            <?php } ?>
        </div>

        <div class="actions">
            <a href="manage-orders.php" class="btn">Back to Orders</a>
        </div>

    </div>

    <div class="footer">
        <p>&copy; 2025 Your Company. All rights reserved.</p>
    </div>

    <script>
        // Function to toggle the visibility of the products section
        function toggleProducts() {
            var productsSection = document.getElementById('productsSection');
            if (productsSection.style.display === "none" || productsSection.style.display === "") {
                productsSection.style.display = "block";
            } else {
                productsSection.style.display = "none";
            }
        }
    </script>

</body>
</html>
