<?php
session_start();
include('db.php');

if (isset($_POST['submit'])) {

    $p_cat_id = $_POST['p_cat_id'];
    $product_title = $_POST['product_title'];
    $product_img1 = $_FILES['product_img1']['name'];
    $product_price = $_POST['product_price'];
    $product_keywords = $_POST['product_keywords'];
    $product_desc = $_POST['product_desc'];

    // Temporary file names
    $temp_name1 = $_FILES['product_img1']['tmp_name'];

    // Allowed image file types
    $allowed_image_types = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
    $image1_type = pathinfo($product_img1, PATHINFO_EXTENSION);

    // Check if both images are valid
    if (!in_array(strtolower($image1_type), $allowed_image_types)) {
        echo "<script>alert('Please upload valid image files (jpg, jpeg, png, gif, bmp, webp)')</script>";
        exit();
    }

    // Define the path to store the images
    $upload_dir = "uploads/images/";

    // Ensure the upload directory exists
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true); // Create the directory if it doesn't exist
    }

    // Move uploaded files to the uploads folder
    $image1_path = $upload_dir . $product_img1;

    if (move_uploaded_file($temp_name1, $image1_path)) {

        // Insert product into the database
        $insert_product = "INSERT INTO products (p_cat_id, date, product_title, product_img1, product_price, product_keywords, product_desc)
                           VALUES ('$p_cat_id', NOW(), '$product_title', '$product_img1', '$product_price', '$product_keywords', '$product_desc')";

        $run_insert_product = mysqli_query($con, $insert_product);  

        if ($run_insert_product) {
            echo "<script>alert('Product Inserted Successfully')</script>";
            echo "<script>window.open('insert-product.php','_self')</script>";
        } else {
            echo "<script>alert('Error inserting product into the database')</script>";
        }
    } else {
        echo "<script>alert('Error uploading images')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
            margin: 0 auto;
        }

        h2 {
            font-size: 2.5rem;
            font-weight: 600;
            color: #343a40;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 500;
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

        .form-control::placeholder {
            color: #888;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            width: 100%;
            padding: 12px;
            font-size: 1.1rem;
            border-radius: 5px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .img-preview {
            margin-top: 10px;
        }

        .img-preview img {
            max-width: 150px;
            border-radius: 8px;
        }

        /* Fixing file input field display */
        input[type="file"] {
            padding: 10px;
            font-size: 1rem;
            display: block;
            width: 100%;
        }

        /* Ensure proper width and padding for file inputs */
        .form-group input[type="file"] {
            padding: 10px 10px;
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Insert New Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="product_title">Product Title/Name:</label>
                <input type="text" name="product_title" class="form-control" placeholder="Enter product title" required>
            </div>

            <div class="form-group">
                <label for="p_cat_id">Product Category:</label>
                <select name="p_cat_id" class="form-control" required>
                    <option value="">Select a Product Category</option>
                    <?php
                    $get_p_category = "SELECT * FROM product_categories";
                    $run_p_category = mysqli_query($con, $get_p_category);
                    while ($p_cat_row = mysqli_fetch_array($run_p_category)) {
                        $p_cat_id = $p_cat_row['p_cat_id'];
                        $p_cat_title = $p_cat_row['p_cat_title'];
                        echo "<option value='$p_cat_id'>$p_cat_title</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="product_img1">Product Image:</label>
                <input type="file" name="product_img1" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="product_price">Product Price:</label>
                <input type="text" name="product_price" class="form-control" placeholder="Enter product price" required>
            </div>

            <div class="form-group">
                <label for="product_keywords">Product Keywords:</label>
                <input type="text" name="product_keywords" class="form-control" placeholder="Enter product keywords" required>
            </div>

            <div class="form-group">
                <label for="product_desc">Product Description:</label>
                <textarea name="product_desc" class="form-control" rows="5" placeholder="Enter product description" required></textarea>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">Insert Product</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>