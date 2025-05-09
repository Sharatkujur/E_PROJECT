<?php
session_start();
include("db.php");

// Check if the order ID is stored in the session
if (!isset($_SESSION['order_id'])) {
    echo "<script>alert('Order not found. Please try again.');</script>";
    echo "<script>window.location.href='index.php';</script>"; // Redirect to homepage or another appropriate page
    exit();
}

$order_id = $_SESSION['order_id'];
$customer_email = $_SESSION['customer_email'];

// Fetch order details from the ordersdetails table (including product name, price, qty, total price)
$order_details_query = "
    SELECT od.o_id, od.p_id, p.product_title AS p_name, od.price, od.qty, od.pricqty
    FROM ordersdetails od
    LEFT JOIN products p ON p.products_id = od.p_id
    WHERE od.o_id = '$order_id'
";
$order_details_result = mysqli_query($con, $order_details_query);

if (!$order_details_result || mysqli_num_rows($order_details_result) == 0) {
    echo "<script>alert('No order details found.');</script>";
    echo "<script>window.location.href='index.php';</script>";
    exit();
}

$order_details = [];
$total_price = 0;
while ($row = mysqli_fetch_assoc($order_details_result)) {
    $order_details[] = $row;
    $total_price += $row['pricqty']; // Add total price of each product (price * quantity)
}

// Insert order data into the orderhistory table
foreach ($order_details as $item) {
    $product_id = $item['p_id'];
    $product_name = $item['p_name'];
    $price = $item['price'];
    $quantity = $item['qty'];
    $total_price_per_item = $item['pricqty'];

    $insert_order_history_query = "
        INSERT INTO orderhistory (customer_email, product_id, product_name, quantity, price, total_price) 
        VALUES ('$customer_email', '$product_id', '$product_name', '$quantity', '$price', '$total_price_per_item')
    ";

    $run_insert_query = mysqli_query($con, $insert_order_history_query);

    if (!$run_insert_query) {
        echo "<script>alert('Error storing order in order history.');</script>";
    }
}

// Fetch referrer details if available
$referrer_query = "SELECT rcustid FROM customer WHERE customer_email = '$customer_email'";
$referrer_result = mysqli_query($con, $referrer_query);

if ($referrer_result && mysqli_num_rows($referrer_result) > 0) {
    $referrer_row = mysqli_fetch_assoc($referrer_result);
    $referrer_id = $referrer_row['rcustid'];  // Get the referrer's customer ID

    if ($referrer_id) {
        // Calculate the referral bonus (10% of the total order price)
        $referral_amount = $total_price * 0.10;

        // Update the referrer's balance (add the referral bonus)
        $update_referrer_balance_query = "
            UPDATE customer
            SET referral_balance = referral_balance + $referral_amount
            WHERE customer_id = '$referrer_id'
        ";
        if (mysqli_query($con, $update_referrer_balance_query)) {
            echo "<script>alert('Referral balance updated for referrer!');</script>";
        } else {
            echo "<script>alert('Error updating referral balance!');</script>";
        }

        // Log the referral transaction
        $insert_referral_transaction_query = "
            INSERT INTO referral_transactions (referrer_id, referred_customer_id, referral_amount)
            VALUES ('$referrer_id', '$order_id', '$referral_amount')
        ";
        if (mysqli_query($con, $insert_referral_transaction_query)) {
            echo "<script>alert('Referral transaction logged!');</script>";
        } else {
            echo "<script>alert('Error logging referral transaction!');</script>";
        }
    } else {
        echo "<script>alert('No referrer found for this customer!');</script>";
    }
} else {
    echo "<script>alert('No referrer found for this email address!');</script>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <!-- Google Fonts Used -->
    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Sofia' rel='stylesheet'>

    <!-- Tab Icon -->
    <link rel="icon" href="img/icon.svg">

    <!-- Css Styles -->
    <link rel='stylesheet' href='css/bootstrap.min.css' type='text/css'>
    <link rel='stylesheet' href='css/font-awesome.min.css' type='text/css'>
    <link rel='stylesheet' href='css/themify-icons.css' type='text/css'>
    <link rel='stylesheet' href='css/elegant-icons.css' type='text/css'>
    <link rel='stylesheet' href='css/owl.carousel.min.css' type='text/css'>
    <link rel='stylesheet' href='css/slicknav.min.css' type='text/css'>
    <link rel='stylesheet' href='css/style.css' type='text/css'>
</head>

<style>
    /* Breadcrumb Section Styling */
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

    /* Order Confirmation Section Styling */
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
    <!-- Order Confirmation Section Begin -->
    <section class="order-confirmation-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="order-confirmation-content">
                        <h4>Your Order Has Been Successfully Placed!</h4>
                        <p>Thank you for your order! Your order ID is: <strong><?php echo $order_id; ?></strong></p>
                        
                        <h5>Order Summary</h5>
                        <table class="order-summary-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order_details as $item) : ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['p_name']); ?></td>
                                        <td><?php echo $item['qty']; ?></td>
                                        <td>₹<?php echo number_format($item['price'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        
                        <div class="total-price">
                            <h3>Total Price: ₹<?php echo number_format($total_price, 2); ?></h3>
                        </div>
                        
                        <a href="index.php" class="site-btn">Go Back to Homepage</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Order Confirmation Section End -->

    <?php
    // Optionally, you can clear the session order ID after showing the success page
    unset($_SESSION['order_id']);
    ?>
</body>
</html>

<?php include('footer.php'); ?>