<?php
session_start();
include("db.php");

// Check admin login
if(!isset($_SESSION['admin_email'])){
    header("Location: login.php");
    exit();
}

// Delete order if requested
if(isset($_GET['delete_order'])){
    $delete_id = $_GET['delete_order'];
    $delete_order = "DELETE FROM orders WHERE order_id='$delete_id'";
    $run_delete = mysqli_query($con,$delete_order);
    if($run_delete){
        echo "<script>alert('Order has been deleted')</script>";
        echo "<script>window.open('manage-orders.php','_self')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
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
        <h2>Manage Orders</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Order ID</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $get_orders = "
                    SELECT o.*, c.customer_name
                    FROM orders o 
                    LEFT JOIN customer c ON o.c_id = c.customer_email 
                    ORDER BY o.date DESC
                ";
                $run_orders = mysqli_query($con, $get_orders);

                while($row_orders = mysqli_fetch_array($run_orders)){
                    $order_id = $row_orders['order_id'];
                    $c_id = $row_orders['c_id'];
                    $order_qty = $row_orders['order_qty'];
                    $order_price = $row_orders['order_price'];
                    $date = $row_orders['date'];
                    $customer_name = $row_orders['customer_name'];
                ?>
                
                <tr>
                    <td><?php echo $order_id; ?></td>
                    <td><?php echo !empty($customer_name) ? $customer_name : 'Unknown'; ?></td>
                    <td>
                        <?php 
                        // You might want to add a separate order_items table to show actual products
                        echo "Order " . $order_id; 
                        ?>
                    </td>
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
