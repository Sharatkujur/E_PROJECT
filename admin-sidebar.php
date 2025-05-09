<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sidebar</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Sidebar Styles */
        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #343a40;
            color: #fff;
            padding-top: 20px;
            z-index: 1000; /* Ensures sidebar stays on top of the content */
            transition: transform 0.3s ease;
        }

        .sidebar h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 30px;
            color: #f8f9fa;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            color: #dcdfe1;
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
        }

        .sidebar a i {
            margin-right: 10px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #495057;
            color: #fff;
        }

        /* Sidebar toggle button (Hamburger menu for mobile) */
        .sidebar-toggle {
            display: none;
            font-size: 30px; /* Medium icon size */
            background-color: #495057; /* Dark background for contrast */
            color: #fff; /* White icon color */
            border: 2px solid #fff; /* White border around the button */
            padding: 10px; /* Padding to make the button easier to click */
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 1001;
            border-radius: 5px; /* Optional: rounded corners */
            cursor: pointer; /* Change cursor to pointer on hover */
        }

        /* Main Content Styles */
        .main-content {
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        /* Mobile-specific styles */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px; /* Reduced sidebar width */
                transform: translateX(-100%); /* Sidebar is hidden by default */
            }

            .sidebar.active {
                transform: translateX(0); /* Sidebar moves into view */
            }

            .sidebar h2 {
                font-size: 20px;
                margin-bottom: 20px;
            }

            .sidebar a {
                font-size: 16px; /* Smaller font size for mobile */
                padding: 12px;
            }

            .sidebar a i {
                font-size: 20px; /* Adjust icon size */
                margin-right: 10px; /* Maintain spacing between icon and text */
            }

            .main-content {
                padding: 10px;
            }

            /* Show the sidebar toggle button only on mobile */
            .sidebar-toggle {
                display: block; /* Show the toggle button on mobile */
                font-size: 30px; /* Medium icon size for better visibility */
                background-color: #495057; /* Dark background for the icon */
                color: #fff; /* White icon color */
                border: 2px solid #fff; /* White border around the button */
                padding: 10px; /* Padding to make the button easier to click */
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar Toggle Button for Mobile -->
    <button class="sidebar-toggle" id="sidebar-toggle">
        <i class="fa fa-bars"></i> <!-- This is the new icon (bars) -->
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h2>Admin Panel</h2>
        <a href="admin-panel.php" class="<?= basename($_SERVER['PHP_SELF']) == 'admin-panel.php' ? 'active' : '' ?>">
            <i class="fa fa-dashboard"></i> Dashboard
        </a>
        <a href="manage-product.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage-products.php' ? 'active' : '' ?>">
            <i class="fa fa-cogs"></i> Manage Products
        </a>
        <a href="manage-orders.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage-orders.php' ? 'active' : '' ?>">
            <i class="fa fa-cart-plus"></i> Manage Orders
        </a>
        <a href="manage-customers.php" class="<?= basename($_SERVER['PHP_SELF']) == 'customers.php' ? 'active' : '' ?>">
            <i class="fa fa-users"></i> View Customers
        </a>
        <a href="logout.php" class="<?= basename($_SERVER['PHP_SELF']) == 'logout.php' ? 'active' : '' ?>">
            <i class="fa fa-sign-out"></i> Logout
        </a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Welcome, Admin!</h1>
        <p>This is your admin dashboard. You can manage products, orders, and view customer details here.</p>
    </div>

    <!-- JavaScript to toggle sidebar visibility on mobile -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');

        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('active');
        });
    </script>
</body>

</html>
