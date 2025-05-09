<?php
session_start();
include("db.php");

// Ensure the customer is logged in
if (!isset($_SESSION['customer_email'])) {
    echo "<script>alert('Please log in to place an order.');</script>";
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

// Fetch customer email
$customer_email = $_SESSION['customer_email'];

// Fetch products from the temp table
$temp_query = "
    SELECT product_id, product_name, price, quantity, total_price 
    FROM temp 
    WHERE customer_id = '$customer_email'
";
$temp_result = mysqli_query($con, $temp_query);

// If no items in the cart, redirect
if (!$temp_result || mysqli_num_rows($temp_result) == 0) {
    echo "<script>alert('Your cart is empty. Please add products to proceed.');</script>";
    echo "<script>window.location.href='shopping-cart.php';</script>";
    exit();
}

// Start transaction
mysqli_begin_transaction($con);

try {
    $total_price = 0; // Total price for the order
    $order_id = null; // Will store the first order_id for referencing in ordersdetails

    $all_products = []; // To hold product data for ordersdetails

    // Process each product and insert into orders table
    while ($row = mysqli_fetch_assoc($temp_result)) {
        $product_id = $row['product_id'];
        $product_name = $row['product_name'];
        $price = $row['price'];
        $quantity = $row['quantity'];
        $total_price_per_product = $row['total_price'];

        // Add to the total price for the entire order
        $total_price += $total_price_per_product;

        // Insert into orders table
        $insert_order_query = "
            INSERT INTO orders (order_qty, order_price, c_id, product_id, date)
            VALUES ($quantity, $total_price_per_product, '$customer_email', $product_id, NOW())
        ";
        if (!mysqli_query($con, $insert_order_query)) {
            throw new Exception("Error inserting into orders table: " . mysqli_error($con));
        }

        // Capture the order_id for referencing in ordersdetails
        if (!$order_id) {
            $order_id = mysqli_insert_id($con); // Capture the first auto-incremented order ID
        }

        // Add product data to the array for ordersdetails
        $all_products[] = [
            'p_id' => $product_id,
            'p_name' => $product_name,
            'price' => $price,
            'qty' => $quantity,
            'pricqty' => $total_price_per_product
        ];
    }

    // Insert summarized data into ordersdetails
    foreach ($all_products as $product) {
        $p_id = $product['p_id'];
        $p_name = $product['p_name'];
        $price = $product['price'];
        $qty = $product['qty'];
        $pricqty = $product['pricqty'];

        $insert_orderdetails_query = "
            INSERT INTO ordersdetails (o_id, p_id, p_name, price, qty, pricqty)
            VALUES ($order_id, $p_id, '$p_name', $price, $qty, $pricqty)
        ";
        if (!mysqli_query($con, $insert_orderdetails_query)) {
            throw new Exception("Error inserting into ordersdetails table: " . mysqli_error($con));
        }
    }

    // Clear the temp table for this customer
    $clear_temp_query = "DELETE FROM temp WHERE customer_id = '$customer_email'";
    if (!mysqli_query($con, $clear_temp_query)) {
        throw new Exception("Error clearing temp table: " . mysqli_error($con));
    }

    // Clear the cart table for this customer
    $clear_cart_query = "DELETE FROM cart WHERE c_id = '$customer_email'";
    if (!mysqli_query($con, $clear_cart_query)) {
        throw new Exception("Error clearing cart table: " . mysqli_error($con));
    }

    // Commit the transaction
    mysqli_commit($con);

    // Set the order ID in the session for confirmation
    $_SESSION['order_id'] = $order_id;

    echo "<script>alert('Your order has been successfully placed!');</script>";
    echo "<script>window.location.href='order-success.php';</script>";
    exit();
} catch (Exception $e) {
    // Rollback transaction in case of error
    mysqli_rollback($con);
    echo "<script>alert('There was an error processing your order: " . $e->getMessage() . "');</script>";
    echo "<script>window.location.href='shopping-cart.php';</script>";
    exit();
}
?>