<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

// Get user stats
if ($user_type == 'student') {
    // Get student's parcels
    $query = "SELECT COUNT(*) as total FROM parcels WHERE sender_id = $user_id";
    $stats = mysqli_fetch_assoc(mysqli_query($conn, $query));
    
    $pending_query = "SELECT COUNT(*) as pending FROM parcels WHERE sender_id = $user_id AND status = 'pending'";
    $pending = mysqli_fetch_assoc(mysqli_query($conn, $pending_query));
    
    // Get recent orders
    $orders_query = "SELECT * FROM parcels WHERE sender_id = $user_id ORDER BY order_time DESC LIMIT 5";
    $orders = mysqli_query($conn, $orders_query);
} else {
    // Get runner's stats
    $query = "SELECT COUNT(*) as total FROM parcels WHERE runner_id = $user_id AND status = 'delivered'";
    $stats = mysqli_fetch_assoc(mysqli_query($conn, $query));
    
    $earnings_query = "SELECT SUM(fee) as earnings FROM parcels WHERE runner_id = $user_id AND status = 'delivered'";
    $earnings = mysqli_fetch_assoc(mysqli_query($conn, $earnings_query));
    
    // Get available orders
    $available_query = "SELECT p.*, u.full_name FROM parcels p 
                        JOIN users u ON p.sender_id = u.user_id 
                        WHERE p.status = 'pending' ORDER BY p.order_time DESC LIMIT 5";
    $available_orders = mysqli_query($conn, $available_query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - RunnerHussein</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">🏃 RUNNERHUSSEIN</div>
            <ul class="nav-menu">
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <?php if($user_type == 'student'): ?>
                    <li><a href="create_order.php">Create Order</a></li>
                    <li><a href="my_orders.php">My Orders</a></li>
                <?php else: ?>
                    <li><a href="available_orders.php">Available Orders</a></li>
                    <li><a href="my_deliveries.php">My Deliveries</a></li>
                <?php endif; ?>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h1>Welcome, <?php echo $_SESSION['full_name']; ?>! 👋</h1>
        
        <?php if($user_type == 'student'): ?>
        <!-- Student Dashboard -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Orders</h3>
                <p class="stat-number"><?php echo $stats['total']; ?></p>
            </div>
            <div class="stat-card">
                <h3>Pending Orders</h3>
                <p class="stat-number"><?php echo $pending['pending']; ?></p>
            </div>
        </div>
        
        <h2>Recent Orders</h2>
        <div class="orders-list">
            <?php while($order = mysqli_fetch_assoc($orders)): ?>
                <div class="order-card">
                    <div class="order-header">
                        <span class="order-id">#<?php echo $order['parcel_id']; ?></span>
                        <span class="status status-<?php echo $order['status']; ?>">
                            <?php echo ucfirst($order['status']); ?>
                        </span>
                    </div>
                    <p><strong>Pickup:</strong> <?php echo $order['pickup_location']; ?></p>
                    <p><strong>Dropoff:</strong> <?php echo $order['dropoff_location']; ?></p>
                    <p><strong>Fee:</strong> RM <?php echo number_format($order['fee'], 2); ?></p>
                    <small><?php echo $order['order_time']; ?></small>
                </div>
            <?php endwhile; ?>
        </div>
        
        <?php else: ?>
        <!-- Runner Dashboard -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Deliveries</h3>
                <p class="stat-number"><?php echo $stats['total']; ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Earnings</h3>
                <p class="stat-number">RM <?php echo number_format($earnings['earnings'] ?? 0, 2); ?></p>
            </div>
        </div>
        
        <h2>Available Orders Near You</h2>
        <div class="orders-list">
            <?php while($order = mysqli_fetch_assoc($available_orders)): ?>
                <div class="order-card">
                    <div class="order-header">
                        <span class="order-id">#<?php echo $order['parcel_id']; ?></span>
                        <span class="status status-pending">Available</span>
                    </div>
                    <p><strong>From:</strong> <?php echo $order['full_name']; ?></p>
                    <p><strong>Pickup:</strong> <?php echo $order['pickup_location']; ?></p>
                    <p><strong>Dropoff:</strong> <?php echo $order['dropoff_location']; ?></p>
                    <p><strong>Fee:</strong> RM <?php echo number_format($order['fee'], 2); ?></p>
                    <button onclick="acceptOrder(<?php echo $order['parcel_id']; ?>)" class="btn-success">
                        Accept Order
                    </button>
                </div>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>
    </div>

    <script src="script.js"></script>
</body>
</html>