<?php
session_start();
include("db.php");

// Check if referred customer's email is passed via GET
if (!isset($_GET['customer_email'])) {
    echo "<script>alert('No referred customer selected.');</script>";
    echo "<script>window.location.href='under-me.php';</script>";
    exit();
}

$customer_email = $_GET['customer_email'];

// Fetch order history details for the referred customer
$order_query = "
    SELECT 
        order_id, 
        product_name, 
        price, 
        quantity, 
        total_price, 
        order_date, 
        status 
    FROM 
        orderhistory
    WHERE 
        customer_email = '$customer_email'
";
$order_result = mysqli_query($con, $order_query);

if (!$order_result || mysqli_num_rows($order_result) == 0) {
    echo "<script>alert('No orders found for this referred customer.');</script>";
    echo "<script>window.location.href='under-me.php';</script>";
    exit();
}

$order_details = [];
$total_price = 0;
while ($row = mysqli_fetch_assoc($order_result)) {
    $order_details[] = $row;
    $total_price += $row['total_price']; // Calculate the total price
}

// Fetch referrer details if available
$referrer_query = "SELECT rcustid FROM customer WHERE customer_email = '$customer_email'";
$referrer_result = mysqli_query($con, $referrer_query);
$referrer_id = null;

if ($referrer_result && mysqli_num_rows($referrer_result) > 0) {
    $referrer_row = mysqli_fetch_assoc($referrer_result);
    $referrer_id = $referrer_row['rcustid'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Referral Order Details</title>
    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Sofia' rel='stylesheet'>
    <link rel="icon" href="img/icon.svg">
    <link rel='stylesheet' href='css/bootstrap.min.css' type='text/css'>
    <link rel='stylesheet' href='css/font-awesome.min.css' type='text/css'>
    <link rel='stylesheet' href='css/themify-icons.css' type='text/css'>
    <link rel='stylesheet' href='css/elegant-icons.css' type='text/css'>
    <link rel='stylesheet' href='css/owl.carousel.min.css' type='text/css'>
    <link rel='stylesheet' href='css/slicknav.min.css' type='text/css'>
    <link rel='stylesheet' href='css/style.css' type='text/css'>
</head>

<style>
    /* Similar styles to match the provided layout */
    .breadcrumb-section {
        background: #f8f8f8;
        padding: 20px 0;
    }

    .breadcrumb-text a {
        color: #333;
        text-decoration: none;
        margin-right: 10px;
    }

    .breadcrumb-text span {
        color: #999;
    }

    .order-confirmation-section {
        padding: 40px 0;
        background: #f8f8f8;
    }

    .order-confirmation-content {
        background-color: #fff;
        padding: 30px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .order-confirmation-content h4 {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .order-summary-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .order-summary-table th, .order-summary-table td {
        padding: 12px 15px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .order-summary-table th {
        background-color: #f0f0f0;
    }

    .total-price {
        font-size: 18px;
        font-weight: bold;
        margin-top: 15px;
    }

    .site-btn {
        background-color: #007bff;
        color: white;
        padding: 12px 30px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
    }

    .site-btn:hover {
        background-color: #0056b3;
    }
</style>

<body>
    <section class="order-confirmation-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="order-confirmation-content">
                        <h4>Orders for Referred Customer: <?php echo htmlspecialchars($customer_email); ?></h4>

                        <?php if ($referrer_id) : ?>
                            <p>Referred by Customer ID: <strong><?php echo htmlspecialchars($referrer_id); ?></strong></p>
                        <?php endif; ?>

                        <h5>Order Summary</h5>
                        <table class="order-summary-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                    <th>Order Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order_details as $item) : ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['order_id']); ?></td>
                                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td>₹<?php echo number_format($item['price'], 2); ?></td>
                                        <td>₹<?php echo number_format($item['total_price'], 2); ?></td>
                                        <td><?php echo htmlspecialchars($item['order_date']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <div class="total-price">
                            <h3>Total Orders Value: ₹<?php echo number_format($total_price, 2); ?></h3>
                        </div>

                        <a href="under-me.php" class="site-btn">Go Back</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>