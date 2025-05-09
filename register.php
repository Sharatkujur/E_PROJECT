<?php
$active = "Sign Up";
include("db.php");
include("functions.php");
include('header.php');
?>

<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="Index.php"><i class="fa fa-home"></i> Home</a>
                    <span>Sign Up</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Form Section Begin -->

<!-- Register Section Begin -->
<div class="register-login-section spad">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="register-form">
                    <h2>Sign Up</h2>
                    <form action="register.php" method="post" enctype="multipart/form-data" id="logform">
                        <div class="row">
                            <div class="group-input col-md-6">
                                <label for="username">Name *</label>
                                <input type="text" id="username" name="name" required>
                                <div id="nameerr" style="margin:20px 0"></div>
                            </div>
                            <div class="group-input col-md-6">
                                <label for="con">Contact *</label>
                                <input type="text" id="con" name="contact" pattern="[0-9]{10}" required title="Please enter a valid 10-digit contact number">
                                <div id="conerr" style="margin:20px 0"></div>
                            </div>
                        </div>
                        <div class="group-input">
                            <label for="email">Email *</label>
                            <input type="text" id="eemail" name="cemail" required>
                            <div id="eerr" style="margin:20px 0"></div>
                        </div>
                        <div class="group-input">
                            <label for="pass">Password *</label>
                            <input type="password" id="pass" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="con-pass">Address *</label>
                            <input type="text" id="con-pass" name="address" required>
                        </div>
                        <div class="group-input">
                            <label for="con-pass">Profile Image (optional)</label>
                            <input type="file" name="pimage" style="border: none; margin-top:6px;">
                        </div>
                        <div class="group-input">
                            <label for="referral_code">Referral Code (optional)</label>
                            <input type="text" name="referral_code" placeholder="Enter referral code (if any)">
                        </div>
                        <button type="submit" class="site-btn register-btn" name="register">Sign Up</button>
                    </form>
                    <div class="switch-login">
                        <a href="login.php" class="or-login">Or Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Register Form Section End -->

<?php
include('footer.php');
?>

<script>
    $("#logform").submit(function(event) {
        var name = $('#username').val();
        var email = $('#eemail').val();
        var con = $('#con').val();

        var letters = /^[A-Za-z]+$/;
        var em = /\S+@\S+\.\S+/;
        var numbers = /^[0-9]{10}$/;

        if (!name.match(letters)) {
            $("#nameerr").html(
                "<span class='alert alert-danger'>" +
                "Enter Valid Name (Letters only)</span>");
            event.preventDefault();
        }

        if (!con.match(numbers)) {
            $("#conerr").html(
                "<span class='alert alert-danger'>" +
                "Enter Valid Contact (10 Digit)</span>");
            event.preventDefault();
        }

        if (!email.match(em)) {
            $("#eerr").html(
                "<span class='alert alert-danger'>" +
                "Enter Valid Email</span>");
            event.preventDefault();
        }
    });
</script>

</body>

</html>

<?php
if (isset($_POST['register'])) {

    $c_name = $_POST['name'];
    $c_email = $_POST['cemail'];
    $c_address = $_POST['address'];
    $c_pass = $_POST['password'];
    $c_contact = $_POST['contact'];
    $referral_code = $_POST['referral_code'];  // Referral code (optional)

    // Validate contact number is 10 digits
    if (!preg_match("/^[0-9]{10}$/", $c_contact)) {
        echo "<script>alert('Please enter a valid 10-digit contact number.');</script>";
        exit();
    }

    // Check if the email already exists
    $email_check_query = "SELECT * FROM customer WHERE customer_email = '$c_email'";
    $email_check_result = mysqli_query($con, $email_check_query);

    if (mysqli_num_rows($email_check_result) > 0) {
        echo "<script>alert('This email address is already registered. Please use a different email.');</script>";
        exit();
    }

    // Check if referral code (customer_id) exists
    if (!empty($referral_code)) {
        // Check if the referral code exists as a valid customer_id
        $referral_check_query = "SELECT * FROM customer WHERE customer_id = '$referral_code'";
        $referral_check_result = mysqli_query($con, $referral_check_query);

        if (mysqli_num_rows($referral_check_result) == 0) {
            echo "<script>alert('The referral code does not exist. Please enter a valid referral code.');</script>";
            exit();
        }

        // If the referral code exists, set rcustid to the referral_code (which is a valid customer_id)
        $rcustid = $referral_code;
    } else {
        // If no referral code is entered, set rcustid to 0
        $rcustid = 0;
    }

    $c_ip = getRealIpUser();
    $_SESSION['customer_email'] = $c_email;
    $c_id = $_SESSION['customer_email'];

    $tardir = "img/customer/";

    // Check if a profile image was uploaded
    $fileName = basename($_FILES['pimage']['name']);
    $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

    // Default profile image if no file is uploaded
    $profileImage = NULL;

    if (!empty($fileName)) {
        $allow = array('jpg', 'png', 'jpeg');

        // If file is valid, upload it
        if (in_array($fileType, $allow)) {
            $targetPath = $tardir . $fileName;

            if (move_uploaded_file($_FILES['pimage']['tmp_name'], $targetPath)) {
                $profileImage = $fileName;
            } else {
                echo "<script>alert('Error uploading image.');</script>";
            }
        } else {
            echo "<script>alert('Only JPG, PNG, and JPEG images are allowed.');</script>";
        }
    }

    // Insert user data into the database
    $insert_c = "INSERT INTO customer (customer_name, customer_email, customer_pass, customer_address, customer_contact, customer_image, customer_ip)
                VALUES ('$c_name', '$c_email', '$c_pass', '$c_address', '$c_contact', '$profileImage', '$c_ip')";

    $run_insert = mysqli_query($con, $insert_c);

    // Get the generated customer_id after inserting
    $customer_id = mysqli_insert_id($con);

    // Update the customer with the generated referral code (rcustid)
    $update_referral_code = "UPDATE customer SET rcustid = '$rcustid' WHERE customer_id = '$customer_id'";
    mysqli_query($con, $update_referral_code);

    // Handle the cart logic if any
    $sel_cart = "SELECT * FROM cart WHERE c_id = '$c_id'";
    $run_sel_cart = mysqli_query($con, $sel_cart);
    $check_cart = mysqli_num_rows($run_sel_cart);

    if ($check_cart > 0) {
        $_SESSION['customer_email'] = $c_email;
        echo "<script>alert('Account registered. You are Logged In')</script>";
        echo "<script>window.open('check-out.php','_self')</script>";
    } else {
        $_SESSION['customer_email'] = $c_email;
        echo "<script>alert('Account registered. You are Logged In')</script>";
        echo "<script>window.open('index.php','_self')</script>";
    }
}
?>