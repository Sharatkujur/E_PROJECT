<?php
session_start();
include('db.php');

// Ensure admin is logged in
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin-login.php");
    exit();
}

// Fetch products
$query = "SELECT * FROM products";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
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
    </style>
</head>
<body>
    <!-- Include Sidebar -->
    <?php include('admin-sidebar.php'); ?>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Manage Products</h2>
        <a href="insert-product.php" class="btn btn-primary">Add New Product</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Keywords</th>
                    <th>Description</th>
                    <th>Date Added</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['products_id']; ?></td>
                        <td><?php echo $row['product_title']; ?></td>
                        <td><?php echo $row['product_price']; ?></td>
                        <td><?php echo $row['product_keywords']; ?></td>
                        <td><?php echo $row['product_desc']; ?></td>
                        <td><?php echo $row['date']; ?></td>
                        <td>
                            <a href="edit-product.php?id=<?php echo $row['products_id']; ?>" class="btn btn-info btn-sm">Edit</a>
                            <a href="delete-product.php?id=<?php echo $row['products_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
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
