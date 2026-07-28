<?php
require_once 'config.php';

if (!isLoggedIn() || $_SESSION['user_type'] != 'student') {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM parcels WHERE sender_id = $user_id ORDER BY order_time DESC";
$orders = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - RunnerHussein</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <h1>📋 My Orders</h1>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <?php if(mysqli_num_rows($orders) == 0): ?>
            <div class="empty-state">
                <h3>No orders yet</h3>
                <p>Create your first order now!</p>
                <a href="create_order.php" class="btn-primary">Create Order</a>
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
                            <p><strong>📍 Pickup:</strong> <?php echo $order['pickup_location']; ?></p>
                            <p><strong>📍 Dropoff:</strong> <?php echo $order['dropoff_location']; ?></p>
                            <?php if($order['description']): ?>
                                <p><strong>📝 Description:</strong> <?php echo $order['description']; ?></p>
                            <?php endif; ?>
                            <p><strong>💰 Fee:</strong> RM <?php echo number_format($order['fee'], 2); ?></p>
                            
                            <?php if($order['status'] == 'delivered' && !empty($order['delivery_time'])): ?>
                                <p><strong>✅ Delivered at:</strong> <?php echo $order['delivery_time']; ?></p>
                            <?php endif; ?>
                            
                            <?php if($order['runner_id']): ?>
                                <?php 
                                    $runner_query = "SELECT full_name FROM users WHERE user_id = {$order['runner_id']}";
                                    $runner_result = mysqli_query($conn, $runner_query);
                                    $runner = mysqli_fetch_assoc($runner_result);
                                ?>
                                <p><strong>🏃 Runner:</strong> <?php echo $runner['full_name'] ?? 'Unknown'; ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="order-actions">
                            <?php if($order['status'] == 'pending'): ?>
                                <button onclick="cancelOrder(<?php echo $order['parcel_id']; ?>)" class="btn-danger">
                                    Cancel Order
                                </button>
                            <?php endif; ?>
                            
                            <?php if($order['status'] == 'delivered'): ?>
                                <button onclick="rateOrder(<?php echo $order['parcel_id']; ?>)" class="btn-primary">
                                    ⭐ Rate Runner
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