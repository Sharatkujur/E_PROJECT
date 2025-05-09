<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// You can set default session variables if necessary
if (!isset($_SESSION['customer_email'])) {
    $_SESSION['customer_email'] = 'unset'; // Set a default value for 'customer_email'
}
?>
