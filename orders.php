<style>
    .cancel-btn {
    padding: 6px 12px;
    background-color: red;
    border: none;
    color: white;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

.cancel-btn:hover {
    background-color: darkred;
}

</style>
<?php
include("db.php");

// Check if the user is logged in
if (isset($_SESSION['customer_email'])) {
    $customer_email = $_SESSION['customer_email'];

    // Query to fetch order details for the logged-in customer
    $query = "
        SELECT 
            order_id, product_name, price, quantity, total_price, order_date, status 
        FROM orderhistory 
        WHERE customer_email = ? 
        ORDER BY order_date DESC
    ";

    // Prepare and execute the query securely
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $customer_email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Start of the order history table
    echo "
    <div class='cart-table' style='min-height: 150px;'>
    <table>
        <thead style='font-size: larger;'>
            <tr>
                <th>Order ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
    ";

    // Displaying the order history
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "
            <tr style='border-bottom: 0.5px solid #ebebeb'>
                <td class='first-row'>{$row['order_id']}</td>
                <td class='first-row'>{$row['product_name']}</td>
                <td class='first-row'>₹{$row['price']}</td>
                <td class='first-row'>{$row['quantity']}</td>
                <td class='first-row'>₹{$row['total_price']}</td>
                <td class='first-row'>{$row['order_date']}</td>
                <td class='first-row'>{$row['status']}</td>
                <td class='first-row'>
                    " . ($row['status'] == 'Pending' ? 
                    "<form method='POST' action='cancel-order.php'>
                        <input type='hidden' name='order_id' value='{$row['order_id']}'>
                        <button type='submit' class='cancel-btn' style='background-color: red; color: white;'>Cancel Order</button>
                    </form>" : 
                    "<span style='color: gray;'>Cannot Cancel</span>") . "
                </td>
            </tr>";
        }
    } else {
        echo "
        <tr>
            <td colspan='8' style='text-align: center;'>No orders found.</td>
        </tr>";
    }

    // End of the table
    echo "
        </tbody>
    </table>
    </div>
    ";

    // Close the statement
    $stmt->close();
} else {
    echo "<p>Please log in to view your orders.</p>";
}
?>