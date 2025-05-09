<?php
session_start();
include('db.php');
include("functions.php");
include("header.php");

// Check if the user is logged in
if (!isset($_SESSION['customer_email'])) {
    echo "<script>alert('Please log in first.');</script>";
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

?>

<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text product-more">
                    <a href="index.php"><i class="fa fa-home"></i> Home</a>
                    <a href="shop.php">Shop</a>
                    <span>Check Out</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Section End -->

<!-- Shopping Cart Section Begin -->
<section class="checkout-section spad">
    <div class="container">
        <form class="checkout-form">
            <div class="row">
                <div class="col-lg-6" <?php if (!($_SESSION['customer_email'] == 'unset')) { echo "style = 'margin: 0 auto'"; } ?>>
                    <div class="checkout-content">
                        <a href="shop.php" class="content-btn">Continue Shopping</a>
                    </div>
                    <div class="place-order">
                        <h4>Your Order</h4>
                        <div class="order-total">
                            <ul class="order-table">
                                <li>Products <span>Total</span></li>
                                <?php checkoutProds(); ?>

                                <li class="fw-normal">Subtotal <span><?php total_price(); ?></span></li>
                                <li class="total-price">Total <span><?php total_price(); ?></span></li>
                            </ul>

                            <form action="check-out.php" method="post">
                                <div class="order-btn">
                                    <a href="place-order.php?place=1" class="site-btn place-btn">Place Order</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<!-- Shopping Cart Section End -->

<?php
include('footer.php');

// Order Placement Logic
if (isset($_GET['place'])) {
    $c_id = $_SESSION['customer_email'];

    // Fetch customer ID based on email
    $query = "SELECT * FROM customer WHERE customer_email = '$c_id'";
    $run_query = mysqli_query($con, $query);
    $get_query = mysqli_fetch_array($run_query);
    $custom_id = $get_query['customer_id'];

    // Initialize order summary variables
    $final_price = 0;
    $total_q = 0;
    $product_details = [];

    // Fetch cart items
    $get_items = "SELECT * FROM cart WHERE c_id = '$c_id'";
    $run_items = mysqli_query($db, $get_items);

    // Process each cart item
    while ($row_items = mysqli_fetch_array($run_items)) {
        $p_id = $row_items['products_id'];
        $pro_qty = $row_items['qty'];

        // Fetch product details
        $get_item = "SELECT * FROM products WHERE products_id = '$p_id'";
        $run_item = mysqli_query($db, $get_item);

        while ($row_item = mysqli_fetch_array($run_item)) {
            $pro_name = $row_item['product_name'];  // Product name
            $pro_price = $row_item['product_price']; // Product price

            // Calculate total price for this product
            $pro_total_price = $pro_price * $pro_qty;

            // Update overall total price and quantity
            $total_q += $pro_qty;
            $final_price += $pro_total_price;

            // Append product details for later storage
            $product_details[] = $pro_name . " (x" . $pro_qty . ") - ₹" . $pro_total_price;
        }
    }

    // Combine all product names and details into a single string
    $product_names = implode(", ", $product_details);

    // Insert the entire order into the temp table as a single entry
    $insert_temp = "INSERT INTO temp (customer_id, product_name, quantity, price, total_price)
                    VALUES ('$custom_id', '$product_names', '$total_q', '$final_price', '$final_price')";
    mysqli_query($con, $insert_temp);
    
    // Redirect to the payment page after inserting the order
    $_SESSION['order_total_price'] = $final_price; // Store the final price for the session
    echo "<script>window.location.href='payment.php';</script>";
}
?>

</body>
</html>
