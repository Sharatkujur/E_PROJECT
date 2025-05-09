<?php
session_start();
include("db.php");

// Check if the user is logged in
if (isset($_SESSION['customer_email'])) {
    if (isset($_POST['order_id'])) {
        $order_id = intval($_POST['order_id']);
        
        // Query to update the status of the order to 'Cancelled'
        $update_query = "
            UPDATE orderhistory
            SET status = 'Cancelled'
            WHERE order_id = ? AND customer_email = ?
        ";

        // Prepare and execute the query securely
        $stmt = $con->prepare($update_query);
        $stmt->bind_param("is", $order_id, $_SESSION['customer_email']);
        $stmt->execute();

        // Check if the query was successful
        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Your order has been canceled successfully.');</script>";
            echo "<script>window.location.href='orders.php';</script>";
        } else {
            echo "<script>alert('Failed to cancel order. Please try again.');</script>";
            echo "<script>window.location.href='orders.php';</script>";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "<script>alert('Invalid order ID.');</script>";
        echo "<script>window.location.href='orders.php';</script>";
    }
} else {
    echo "<script>alert('Please log in to cancel your order.');</script>";
    echo "<script>window.location.href='login.php';</script>";
}
?>
