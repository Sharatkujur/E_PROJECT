<?php
session_start();
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

// Fetch product details to delete images
$query = "SELECT * FROM products WHERE products_id = '$product_id'";
$result = mysqli_query($con, $query);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo "<script>alert('Product not found!');</script>";
    echo "<script>window.location.href = 'manage-product.php';</script>";
    exit();
}

// Delete the product
$query = "DELETE FROM products WHERE products_id = '$product_id'";
if (mysqli_query($con, $query)) {
    // Optionally, delete images from the server
    if (!empty($product['product_img1']) && file_exists("uploads/" . $product['product_img1'])) {
        unlink("uploads/" . $product['product_img1']);
    }
    if (!empty($product['product_img2']) && file_exists("uploads/" . $product['product_img2'])) {
        unlink("uploads/" . $product['product_img2']);
    }
    echo "<script>alert('Product deleted successfully!');</script>";
} else {
    echo "<script>alert('Error deleting product.');</script>";
}

echo "<script>window.location.href = 'manage-product.php';</script>";
exit();
?>
