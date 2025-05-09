<?php
session_start();

// Database connection
include('db.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_email'])) {
    echo "<script>alert('Please log in as an admin to access this page.')</script>";
    echo "<script>window.open('admin-login.php', '_self')</script>";
    exit();
}

// Fetch customers from the database
$query = "SELECT * FROM customer";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Customers</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .table {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <?php include('admin-sidebar.php'); ?>


    <!-- Main Content -->
    <div class="main-content">
        <h1>View Customers</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['customer_id']; ?></td>
                        <td><?php echo $row['customer_name']; ?></td>
                        <td><?php echo $row['customer_email']; ?></td>
                        <td><?php echo $row['customer_contact']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>

</html>