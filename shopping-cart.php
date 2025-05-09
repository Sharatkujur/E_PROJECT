<?php
$active = "Shopping Cart";
include("db.php");
include("functions.php");
include('header.php');

// Insert cart items into temp table when proceeding to checkout
if (isset($_GET['checkout'])) { 
    // Check if customer is logged in
    if (isset($_SESSION['customer_email'])) {
        $customer_email = $_SESSION['customer_email'];
        
        // Check if cart is empty
        $cart_check_query = "SELECT COUNT(*) AS cart_count FROM cart WHERE c_id = '$customer_email'";
        $cart_check_result = mysqli_query($con, $cart_check_query);
        $cart_check_row = mysqli_fetch_assoc($cart_check_result);

        if ($cart_check_row['cart_count'] == 0) {
            echo "<script>alert('Your cart is empty. Add items to proceed to checkout.');</script>";
            echo "<script>window.open('shopping-cart.php', '_self');</script>";
            exit();
        }

        $query = "SELECT cart.qty, products.products_id, products.product_price, products.product_title 
                  FROM cart 
                  JOIN products ON cart.products_id = products.products_id 
                  WHERE cart.c_id = '$customer_email'";

        $run_query = mysqli_query($con, $query);

        while ($row = mysqli_fetch_assoc($run_query)) {
            $product_id = $row['products_id'];  
            $product_name = $row['product_title'];
            $quantity = $row['qty'];
            $price = $row['product_price'];
            $total_price = $price * $quantity;

            // Insert into temp table
            $insert_temp_query = "INSERT INTO temp (customer_id, product_id, product_name, quantity, price, total_price) 
                                  VALUES ('$customer_email', '$product_id', '$product_name', '$quantity', '$price', '$total_price')";
            $run_temp_query = mysqli_query($con, $insert_temp_query);
        }

        // Redirect to the checkout page after storing data in temp
        echo "<script>window.open('check-out.php', '_self')</script>";
    } else {
        // Redirect to login page if user is not logged in
        echo "<script>window.open('login.php', '_self')</script>";
    }
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
                    <span>Shopping Cart</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Section End -->

<!-- Shopping Cart Section Begin -->
<section class="shopping-cart spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="cart-table" style="min-height: 150px;">
                    <table>
                        <tbody>
                            <?php cart_items(); ?>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="cart-buttons">
                            <a href="shop.php" class="primary-btn continue-shop">Continue shopping</a>
                        </div>
                    </div>
                    <div class="col-lg-4 offset-lg-4">
                        <div class="proceed-checkout">
                            <ul>
                                <li class="subtotal">Subtotal <span><?php total_price() ?></span></li>
                                <li class="cart-total">Total <span><?php total_price() ?></span></li>
                            </ul>
                            <?php 
                            // Check if cart is empty
                            $cart_empty = true;
                            if (isset($_SESSION['customer_email'])) {
                                $customer_email = $_SESSION['customer_email'];
                                $cart_check_query = "SELECT COUNT(*) AS cart_count FROM cart WHERE c_id = '$customer_email'";
                                $cart_check_result = mysqli_query($con, $cart_check_query);
                                $cart_check_row = mysqli_fetch_assoc($cart_check_result);

                                if ($cart_check_row['cart_count'] > 0) {
                                    $cart_empty = false;
                                }
                            }
                            ?>
                            <?php if ($cart_empty): ?>
                                <a href="#" class="proceed-btn disabled" style="pointer-events: none; opacity: 0.5;">PROCEED TO CHECK OUT</a>
                                <p style="color: red; font-size: 14px;">Your cart is empty. Add items to proceed to checkout.</p>
                            <?php else: ?>
                                <a href="shopping-cart.php?checkout=true" class="proceed-btn">PROCEED TO CHECK OUT</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Shopping Cart Section End -->

<?php
include('footer.php');
?>

</body>
</html>

<?php
// Delete item from cart
if (isset($_GET['del'])) {
    $p_id = $_GET['del'];
    $query = "DELETE FROM cart WHERE products_id='$p_id'";
    $run_query = mysqli_query($con, $query);
    echo "<script>window.open('shopping-cart.php', '_self')</script>";
}

// Update item quantity in cart
if (isset($_POST['update_qty'])) {
    $p_id = $_POST['product_id'];
    $new_qty = $_POST['qty'];

    $query = "UPDATE cart SET qty='$new_qty' WHERE products_id='$p_id' AND c_id='{$_SESSION['customer_email']}'";
    $run_query = mysqli_query($con, $query);
    echo "<script>window.open('shopping-cart.php', '_self')</script>";
}
?>
