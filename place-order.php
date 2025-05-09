<?php
session_start();
include("db.php"); // Ensure this file establishes a connection and sets $con
include("functions.php");
include("header.php");

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in
if (!isset($_SESSION['customer_email'])) {
    echo "Please log in first.";
    exit(); // Stop further execution if not logged in
}

// Prevent infinite redirection
// if (!isset($_GET['place']) && $_GET['place'] == 1) {
//     echo "<script>alert('Order placed successfully');</script>";
//     exit();
// }
// ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        /* Centering the form */
        .checkout-form-section {
            background: #ffffff;
            padding: 40px;
            border: 1px solid #ebebeb;
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .checkout-title {
            margin-bottom: 30px;
            border-bottom: 1px solid #e5e5e5;
            padding-bottom: 20px;
        }

        .checkout-title h4 {
            color: #252525;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .checkout-title p {
            color: #636363;
            font-size: 16px;
            margin-bottom: 0;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            color: #252525;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 12px;
            display: block;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            height: 50px;
            border: 1px solid #ebebeb;
            padding: 0 20px;
            font-size: 16px;
            color: #252525;
            font-family: "Muli", sans-serif;
            transition: all 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #fe4231;
        }

        .form-group textarea {
            height: 120px;
            padding: 15px 20px;
            resize: none;
        }

        .checkout-btn {
            font-size: 14px;
            color: #ffffff;
            font-weight: 700;
            background: #fe4231;
            padding: 15px 30px 12px;
            border: 1px solid #fe4231;
            text-transform: uppercase;
            letter-spacing: 2px;
            width: 100%;
            margin-top: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .checkout-btn:hover {
            opacity: 0.7;
            background: #fe4231;
        }

        .required-mark {
            color: #fe4231;
            margin-left: 3px;
        }

        .input-validation {
            font-size: 14px;
            color: #636363;
            margin-top: 5px;
            display: none;
        }

        input:invalid ~ .input-validation {
            display: block;
            color: #fe4231;
        }
    </style>
</head>
<body>
<div class="col-lg-6">
    <div class="checkout-form-section">
        <div class="checkout-title">
            <h4>Delivery Details</h4>
            <p>Please fill in your delivery information</p>
        </div>
        <form method="post" class="checkout-form">
            <div class="form-group">
                <label>Full Name <span class="required-mark">*</span></label>
                <input type="text" name="customer_name" required>
            </div>

            <div class="form-group">
                <label>Phone Number <span class="required-mark">*</span></label>
                <input type="tel" name="phone" pattern="[0-9]{10}" required
                       placeholder="Enter your 10-digit mobile number">
                <span class="input-validation">Please enter a valid 10-digit mobile number</span>
            </div>

            <div class="form-group">
                <label>Delivery Address <span class="required-mark">*</span></label>
                <textarea name="address" required
                          placeholder="Enter your complete delivery address"></textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>City <span class="required-mark">*</span></label>
                        <input type="text" name="city" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>State <span class="required-mark">*</span></label>
                        <input type="text" name="state" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>PIN Code <span class="required-mark">*</span></label>
                <input type="text" name="pincode" pattern="[0-9]{6}" required
                       placeholder="Enter 6-digit PIN code">
                <span class="input-validation">Please enter a valid 6-digit PIN code</span>
            </div>

            <button type="submit" name="submit_order" class="checkout-btn">
                Place Order
            </button>
        </form>
    </div>
</div>
<?php
if (isset($_POST['submit_order'])) {
    // Sanitize form input data
    $customer_name = mysqli_real_escape_string($con, $_POST['customer_name']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $city = mysqli_real_escape_string($con, $_POST['city']);
    $state = mysqli_real_escape_string($con, $_POST['state']);
    $pincode = mysqli_real_escape_string($con, $_POST['pincode']);

    // Prepare the SQL query
    $insert_query = "INSERT INTO delivery (d_fname, d_contact, d_address, d_city, d_state, d_pc) 
                     VALUES ('$customer_name', '$phone', '$address', '$city', '$state', '$pincode')";

    // Execute the query
//isset($_SESSION['order_id'])


    if (mysqli_query($con, $insert_query)) {
        // Redirect to success page
        $order_id = mysqli_insert_id($con);  // This fetches the ID of the last inserted record
    
        // Store the order_id in the session
        $_SESSION['order_id'] = $order_id;
        echo "<script>window.location.href = 'payment.php';</script>";
        exit();
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>

<?php include('footer.php'); ?>
</body>
</html>