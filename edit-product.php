<?php
session_start();
ob_start(); // Start output buffering to prevent output before header

include('db.php');

// Ensure admin is logged in
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin-login.php");
    exit();
}

// Check if a product ID is provided
if (!isset($_GET['id'])) {
    header("Location: manage-product.php");
    exit();
}

$product_id = $_GET['id'];

// Fetch product details
$query = "SELECT * FROM products WHERE products_id = '$product_id'";
$result = mysqli_query($con, $query);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo "<script>alert('Product not found!');</script>";
    echo "<script>window.location.href = '/Ecommerce-Clothing-Website-master/manage-product.php';</script>"; // Use absolute path
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if all required fields are filled
     if (empty($_POST['product_title']) || empty($_POST['p_cat_id']) || 
        empty($_POST['product_price']) || empty($_POST['product_keywords']) || empty($_POST['product_desc'])) {
        echo "<script>alert('Please fill in all fields!');</script>";
    } else {
        // Sanitize form input
        $title = mysqli_real_escape_string($con, $_POST['product_title']);
        $p_cat_id = mysqli_real_escape_string($con, $_POST['p_cat_id']);
        // $cat_id = mysqli_real_escape_string($con, $_POST['cat_id']);
        $price = mysqli_real_escape_string($con, $_POST['product_price']);
        $keywords = mysqli_real_escape_string($con, $_POST['product_keywords']);
        $description = mysqli_real_escape_string($con, $_POST['product_desc']);

        // Handle image upload
        $image1 = $product['product_img1']; // Keep existing image by default
        if (!empty($_FILES['product_img1']['name'])) {
            $image1 = $_FILES['product_img1']['name'];
            $temp_name1 = $_FILES['product_img1']['tmp_name'];
            move_uploaded_file($temp_name1, "uploads/$image1");
        }

        // Update the product details in the database
        $query = "UPDATE products SET 
                    p_cat_id = '$p_cat_id', 
                    --  cat_id = '$cat_id', 
                    product_title = '$title', 
                    product_img1 = '$image1', 
                    product_price = '$price', 
                    product_keywords = '$keywords', 
                    product_desc = '$description' 
                  WHERE products_id = '$product_id'";

        if (mysqli_query($con, $query)) {
            echo "<script>alert('Product updated successfully!');</script>";
            echo "<script>window.location.href = '/SS_PROJECT/admin-panel.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error updating product: " . mysqli_error($con) . "');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <!-- Bootstrap 4.5 CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Custom Styling -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            padding-top: 30px;
        }

        .container {
            max-width: 800px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-left: auto;
            margin-right: auto;
        }

        .form-group label {
            font-weight: 500;
        }

        h2 {
            font-size: 2.5rem;
            font-weight: 600;
            color: #343a40;
            text-align: center;
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            width: 100%;
            padding: 12px;
            font-size: 1.1rem;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .form-control {
            border-radius: 5px;
            padding: 10px;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
        }

        .img-preview {
            margin-top: 10px;
        }

        .img-preview img {
            max-width: 150px;
            border-radius: 8px;
        }

        .alert {
            margin-top: 20px;
            font-size: 1rem;
            text-align: center;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="product_title">Product Title:</label>
                <input type="text" name="product_title" class="form-control" value="<?php echo $product['product_title']; ?>" required>
            </div>

            <!-- <div class="form-group">
                <label for="cat_id">Category ID:</label>
                <input type="number" name="cat_id" class="form-control" value="<?php echo $product['cat_id']; ?>" required>
            </div> -->

            <div class="form-group">
                <label for="p_cat_id">Parent Category ID:</label>
                <input type="number" name="p_cat_id" class="form-control" value="<?php echo $product['p_cat_id']; ?>" required>
            </div>

            <div class="form-group">
                <label for="product_price">Price:</label>
                <input type="number" name="product_price" class="form-control" value="<?php echo $product['product_price']; ?>" required>
            </div>

            <div class="form-group">
                <label for="product_keywords">Keywords:</label>
                <input type="text" name="product_keywords" class="form-control" value="<?php echo $product['product_keywords']; ?>" required>
            </div>

            <div class="form-group">
                <label for="product_desc">Description:</label>
                <textarea name="product_desc" class="form-control" rows="5" required><?php echo $product['product_desc']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="product_img1">Primary Image:</label>
                <input type="file" name="product_img1" class="form-control">
                <div class="img-preview">
                    <img src="uploads/<?php echo $product['product_img1']; ?>" alt="Current Image">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
