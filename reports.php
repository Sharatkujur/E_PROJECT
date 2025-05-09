<?php
session_start();
include("db.php");

// Ensure admin is logged in
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin-login.php");
    exit();
}

// Fetch reports data (orders and customer info)
$query = "SELECT o.*, c.customer_name, c.customer_email 
          FROM orders o 
          LEFT JOIN customer c ON o.c_id = c.customer_id 
          ORDER BY o.date DESC";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .main-content {
            margin-left: 250px; /* Adjust for the sidebar */
            padding: 20px;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .btn {
            margin-right: 5px;
        }

        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <!-- Include Sidebar -->
    <?php include('admin-sidebar.php'); ?>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Reports</h2>
        
        <!-- Add filters or date picker if required -->
        <form method="get">
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" name="start_date" class="form-control">
            </div>
            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" id="end_date" name="end_date" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Filter Reports</button>
        </form>
        
        <table class="table table-bordered table-responsive">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer Name</th>
                    <th>Customer Email</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_array($result)) {
                    $order_id = $row['order_id'];
                    $customer_name = $row['customer_name'];
                    $customer_email = $row['customer_email'];
                    $order_qty = $row['order_qty'];
                    $order_price = $row['order_price'];
                    $date = $row['date'];
                ?>
                <tr>
                    <td><?php echo $order_id; ?></td>
                    <td><?php echo $customer_name; ?></td>
                    <td><?php echo $customer_email; ?></td>
                    <td><?php echo $order_qty; ?></td>
                    <td>₹<?php echo $order_price; ?></td>
                    <td><?php echo $date; ?></td>
                    <td>
                        <a href="view-order.php?order_id=<?php echo $order_id; ?>" class="btn btn-info btn-sm">
                            View
                        </a>
                        <a href="manage-orders.php?delete_order=<?php echo $order_id; ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('Are you sure you want to delete this order?')">
                            Delete
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>
