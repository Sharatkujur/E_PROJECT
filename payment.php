<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    include("db.php");
    include("functions.php");
    include("header.php");

    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Check if the user is logged in
    if (!isset($_SESSION['customer_email'])) {
        echo "<script>alert('Please log in to proceed to payment.');</script>";
        echo "<script>window.location.href='login.php';</script>";
        exit();
    }

    // Fetch customer email from session
    $customer_email = $_SESSION['customer_email'];

    // Fetch data from the temp table for all products
    $temp_query = "
        SELECT * 
        FROM temp 
        WHERE customer_id = '$customer_email'
        ORDER BY temp_id DESC
    ";
    $temp_result = mysqli_query($con, $temp_query);

    if (!$temp_result || mysqli_num_rows($temp_result) == 0) {
        echo "<script>alert('No pending payment found. Please place your order again.');</script>";
        echo "<script>window.location.href='place-order.php';</script>";
        exit();
    }

    // Calculate the total amount
    $total_amount_query = "
        SELECT SUM(total_price) AS total_amount 
        FROM temp 
        WHERE customer_id = '$customer_email'
    ";
    $total_amount_result = mysqli_query($con, $total_amount_query);

    if ($total_amount_result) {
        $total_amount_row = mysqli_fetch_assoc($total_amount_result);
        $total_amount = $total_amount_row['total_amount'];
    } else {
        $total_amount = 0; // Default to 0 if the query fails
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Payment Page</title>
        <style>
            .payment-section {
                background: #ffffff;
                padding: 40px;
                border: 1px solid #ebebeb;
                width: 100%;
                max-width: 600px;
                margin: 0 auto;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            .payment-title {
                margin-bottom: 30px;
                border-bottom: 1px solid #e5e5e5;
                padding-bottom: 20px;
            }

            .payment-title h4 {
                color: #252525;
                font-size: 24px;
                font-weight: 700;
                margin-bottom: 12px;
            }

            .payment-btn {
                font-size: 16px;
                color: #ffffff;
                font-weight: 700;
                background: #fe4231;
                padding: 15px 30px 12px;
                border: 1px solid #fe4231;
                text-transform: uppercase;
                letter-spacing: 2px;
                width: 100%;
                text-align: center;
                cursor: pointer;
                transition: all 0.3s;
                margin-top: 20px;
                text-decoration: none;
            }

            .payment-btn:hover {
                opacity: 0.8;
            }

            .order-summary {
                margin-top: 30px;
                padding: 20px;
                border: 1px solid #ebebeb;
                width: 100%;
                background-color: #f9f9f9;
            }

            .order-summary h5 {
                font-size: 20px;
                font-weight: bold;
            }

            .order-summary p {
                font-size: 16px;
                margin: 5px 0;
            }

        </style>
    </head>
    <body>
        <div class="payment-section">
            <div class="payment-title">
                <h4>Payment Details</h4>
                <p>Total Amount: &#8377;<?php echo $total_amount; ?></p>
            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <h5>Order Summary</h5>
                <?php 
                while ($temp_row = mysqli_fetch_assoc($temp_result)) {
                    echo "<p><strong>Product:</strong> " . $temp_row['product_name'] . "</p>";
                    echo "<p><strong>Quantity:</strong> " . $temp_row['quantity'] . "</p>";
                    echo "<p><strong>Price:</strong> &#8377;" . $temp_row['price'] . "</p>";
                    echo "<p><strong>Total Price:</strong> &#8377;" . $temp_row['total_price'] . "</p>";
                    echo "<hr>";
                }
                ?>
            </div>

            <p>Please choose a payment method below:</p>

            <!-- Payment Options -->
            <!-- <a href="online-payment-gateway.php" class="payment-btn">Pay Online</a> -->
            <a href="process_order.php" class="payment-btn">Cash on Delivery</a>
        </div>

    <?php include('footer.php'); ?>
    </body>
    </html>