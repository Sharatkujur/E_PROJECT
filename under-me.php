<?php 
include("db.php"); // Include database connection

// Check if the customer is logged in
if (!isset($_SESSION['customer_email'])) {
    die("<p>Please log in to view your referrals.</p>");
}

$customer_email = $_SESSION['customer_email'];

// Fetch logged-in customer data
$query = "SELECT * FROM customer WHERE customer_email = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $customer_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("<p>Customer not found.</p>");
}

$customer_data = $result->fetch_assoc();

$customer_id = $customer_data['customer_id'];
$referral_balance = $customer_data['referral_balance'];

// Query to get all customers with rcustid matching the logged-in customer's ID
$query_referrals = "SELECT * FROM customer WHERE rcustid = ?";
$stmt_referrals = $con->prepare($query_referrals);
$stmt_referrals->bind_param("i", $customer_id);
$stmt_referrals->execute();
$result_referrals = $stmt_referrals->get_result();

// Start of the referral data table
echo "
<div class='cart-table' style='min-height: 150px;'>
    <h2 style='text-align: center;'>Under Me</h2>
    <div class='under-me-info' style='margin-bottom: 20px;'>
        <h4>Referral Balance: ₹" . number_format($referral_balance, 2) . "</h4>
    </div>
    <table>
        <thead style='font-size: larger;'>
            <tr>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Total Orders</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
";

if ($result_referrals->num_rows > 0) {
    while ($referral_data = $result_referrals->fetch_assoc()) {
        $referred_name = $referral_data['customer_name'];
        $referred_email = $referral_data['customer_email'];

        // Query to get the total order value from orderhistory for each referred customer
        $query_orders = "SELECT SUM(total_price) AS total_orders FROM orderhistory WHERE customer_email = ?";
        $stmt_orders = $con->prepare($query_orders);
        $stmt_orders->bind_param("s", $referred_email);
        $stmt_orders->execute();
        $result_orders = $stmt_orders->get_result();
        $order_data = $result_orders->fetch_assoc();

        $total_orders = $order_data['total_orders'] ? $order_data['total_orders'] : 0;

        echo "
        <tr style='border-bottom: 0.5px solid #ebebeb'>
            <td class='first-row'>$referred_name</td>
            <td class='first-row'>$referred_email</td>
            <td class='first-row'>₹" . number_format($total_orders, 2) . "</td>
            <td class='first-row'>
                <form method='GET' action='referral-orders.php'>
                    <input type='hidden' name='customer_email' value='$referred_email'>
                    <button type='submit' class='cancel-btn'>View Details</button>
                </form>
            </td>
        </tr>
        ";
    }
} else {
    echo "
    <tr>
        <td colspan='4' style='text-align: center;'>No referrals found.</td>
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
$stmt_referrals->close();
?>