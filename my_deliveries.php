<?php
require_once 'config.php';

if (!isLoggedIn() || $_SESSION['user_type'] != 'runner') {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

$query = "SELECT p.*, u.full_name, u.phone 
          FROM parcels p 
          JOIN users u ON p.sender_id = u.user_id 
          WHERE p.runner_id = $user_id 
          ORDER BY p.order_time DESC";
$orders = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Deliveries - RunnerHussein</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <h1>📦 My Deliveries</h1>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <?php if(mysqli_num_rows($orders) == 0): ?>
            <div class="empty-state">
                <h3>No deliveries yet</h3>
                <p>Accept orders to start delivering!</p>
                <a href="available_orders.php" class="btn-primary">Browse Orders</a>
            </div>
        <?php else: ?>
            <div class="orders-list">
                <?php while($order = mysqli_fetch_assoc($orders)): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <span class="order-id">#<?php echo $order['parcel_id']; ?></span>
                            <span class="status status-<?php echo $order['status']; ?>">
                                <?php 
                                    $status_labels = [
                                        'pending' => '⏳ Pending',
                                        'accepted' => '✅ Accepted',
                                        'picked_up' => '📦 Picked Up',
                                        'delivered' => '🎉 Delivered',
                                        'cancelled' => '❌ Cancelled'
                                    ];
                                    echo $status_labels[$order['status']] ?? $order['status'];
                                ?>
                            </span>
                        </div>
                        
                        <div class="order-details">
                            <p><strong>👤 Recipient:</strong> <?php echo $order['full_name']; ?></p>
                            <p><strong>📱 Contact:</strong> <?php echo $order['phone']; ?></p>
                            <p><strong>📍 Pickup:</strong> <?php echo $order['pickup_location']; ?></p>
                            <p><strong>📍 Dropoff:</strong> <?php echo $order['dropoff_location']; ?></p>
                            <p><strong>💰 Fee:</strong> RM <?php echo number_format($order['fee'], 2); ?></p>
                        </div>
                        
                        <div class="order-actions">
                            <?php if($order['status'] == 'accepted'): ?>
                                <button onclick="updateStatus(<?php echo $order['parcel_id']; ?>, 'picked_up')" class="btn-warning">
                                    📦 Mark as Picked Up
                                </button>
                            <?php endif; ?>
                            
                            <?php if($order['status'] == 'picked_up'): ?>
                                <button onclick="updateStatus(<?php echo $order['parcel_id']; ?>, 'delivered')" class="btn-success">
                                    ✅ Mark as Delivered
                                </button>
                            <?php endif; ?>
                        </div>
                        
                        <small class="order-time">📅 <?php echo $order['order_time']; ?></small>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="script.js"></script>
</body>
</html>