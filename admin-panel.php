<?php
session_start();
include('db.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_email'])) {
    echo "<script>alert('Please log in as an admin to access this page.')</script>";
    echo "<script>window.open('admin-login.php', '_self')</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        /* Adjust the main content for the sidebar */
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
        }
        .card a {
            text-decoration: none;  /* Remove the underline */
            color: inherit;         /* Use the parent element's text color */
        }

        .card a:hover {
            text-decoration: none;  /* Ensure underline doesn't appear on hover */
            color: inherit;         /* Ensure the color remains the same on hover */
        }


        .card h4 {
            margin: 0;
        }

        .card p {
            font-size: 18px;
            margin-top: 10px;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            /* Adjust Main Content Margin for Mobile */
            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            /* Card Layout - Full Width */
            .row {
                display: flex;
                flex-direction: column;
            }

            .col-md-4 {
                width: 100%;
                margin-bottom: 20px; /* Adds space between the cards */
            }

            /* Adjust the card's padding and content */
            .card {
                padding: 15px;
                margin: 0 auto;
                width: 90%; /* Cards take up 90% of the screen width */
            }

            .card h4 {
                font-size: 20px;
            }

            .card p {
                font-size: 18px;
            }

            /* Additional Styles for Small Mobile Devices */
            body {
                padding: 0;
                margin: 0;
                font-size: 14px; /* Adjust base font size for smaller screens */
            }

            /* Add padding and margin to the title */
            h1 {
                font-size: 24px;
                margin-bottom: 20px;
            }

            /* Optional: Add a container for better mobile UI experience */
            .container {
                padding: 0 10px;
            }
        }
    </style>
</head>

<body>
    <!-- Include Sidebar -->
    <?php include('admin-sidebar.php'); ?>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Welcome, Admin!</h1>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <a href="manage-product.php">
                    <h4>Total Products</h4>
                    <p>
                        <?php
                        $query = "SELECT COUNT(*) as count FROM products";
                        $result = mysqli_query($con, $query);
                        $data = mysqli_fetch_assoc($result);
                        echo $data['count'];
                        ?>
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <a href="manage-orders.php">
                    <h4>Total Orders</h4>
                    <p>
                        <?php
                        $query = "SELECT COUNT(*) as count FROM orders";
                        $result = mysqli_query($con, $query);
                        $data = mysqli_fetch_assoc($result);
                        echo $data['count'];
                        ?>
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <a href="manage-customers.php">
                    <h4>Total Customers</h4>
                    <p>
                        <?php
                        $query = "SELECT COUNT(*) as count FROM customer";
                        $result = mysqli_query($con, $query);
                        $data = mysqli_fetch_assoc($result);
                        echo $data['count'];
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>

</html>
