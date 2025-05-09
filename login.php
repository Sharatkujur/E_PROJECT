<?php
session_start();
include("db.php");
include("functions.php");
include("header.php");
?>

<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="index.php"><i class="fa fa-home"></i> Home</a>
                    <span>Login</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Section End -->

<!-- Register Section Begin -->
<div class="register-login-section spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="login-form">
                    <h2>Login</h2>
                    <form action="login.php" method="post">
                        <div class="group-input">
                            <label for="username">Email *</label>
                            <input type="text" id="username" name="cemail" required>
                        </div>
                        <div class="group-input">
                            <label for="pass">Password *</label>
                            <input type="password" id="pass" name="password" required>
                        </div>
                        <button name="login" class="site-btn login-btn">Login</button>
                    </form>
                    <div class="switch-login">
                        <a href="register.php" class="or-login">Or Create An Account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Register Section End -->

<?php
include('footer.php');

// Login functionality
if (isset($_POST['login'])) {
    $log_email = $_POST['cemail'];
    $log_pass = $_POST['password'];

    // Check if the user is an admin
    $sel_admin = "SELECT * FROM admin WHERE admin_email = '$log_email' AND admin_pass = '$log_pass'";
    $run_sel_admin = mysqli_query($con, $sel_admin);
    $check_admin = mysqli_num_rows($run_sel_admin);

    if ($check_admin > 0) {
        // Admin login successful
        $_SESSION['admin_email'] = $log_email;

        echo "<script>alert('Logged in as Admin');</script>";
        echo "<script>window.open('admin-panel.php', '_self');</script>";
        exit();
    }

    // Check if the user is a customer
    $sel_customer = "SELECT * FROM customer WHERE customer_email = '$log_email' AND customer_pass = '$log_pass'";
    $run_sel_c = mysqli_query($con, $sel_customer);
    $check_customer = mysqli_num_rows($run_sel_c);

    if ($check_customer == 0) {
        // Invalid login
        echo "
        <script>
            bootbox.alert({
                message: 'Invalid Username or Password',
                backdrop: true
            });
        </script>";
        exit();
    }

    // If customer login is successful, check cart
    $c_id = $log_email; // Assuming customer email is used as ID in the cart table
    $get_ip = getRealIpUser();
    $select_cart = "SELECT * FROM cart WHERE c_id = '$c_id'";
    $run_sel_cart = mysqli_query($con, $select_cart);
    $check_cart = mysqli_num_rows($run_sel_cart);

    if ($check_cart == 0) {
        // No items in cart
        $_SESSION['customer_email'] = $log_email;
        echo "<script>window.open('index.php?stat=1', '_self');</script>";
    } else {
        // Items in cart
        $_SESSION['customer_email'] = $log_email;
        echo "<script>window.open('check-out.php', '_self');</script>";
    }
}
?>
