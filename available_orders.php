<?php
require_once 'config.php';

if (!isLoggedIn() || $_SESSION['user_type'] != 'runner') {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

// Get all pending orders
$query = "SELECT p.*, u.full_name, u.phone 
          FROM parcels p 
          JOIN users u ON p.sender_id = u.user_id 
          WHERE p.status = 'pending' 
          ORDER BY p.order_time DESC";
$orders = mysqli_query($conn, $query);

// Handle accept order
if (isset($_GET['accept'])) {
    $parcel_id = (int)$_GET['accept'];
    
    $update = "UPDATE parcels SET runner_id = $user_id, status = 'accepted' WHERE parcel_id = $parcel_id AND status = 'pending'";
    
    if (mysqli_query($conn, $update)) {
        $_SESSION['success'] = "Order accepted successfully!";
        header("Location: my_deliveries.php");
        exit();
    } else {
        $error = "Failed to accept order.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Orders - RunnerHussein</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <h1>📦 Available Orders</h1>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="filter-section">
            <label>Filter by location:</label>
            <input type="text" id="locationFilter" placeholder="Search location..." onkeyup="filterOrders()">
        </div>
        
        <?php if(mysqli_num_rows($orders) == 0): ?>
            <div class="empty-state">
                <h3>No orders available</h3>
                <p>Check back later for new orders!</p>
            </div>
        <?php else: ?>
            <div class="orders-grid" id="ordersGrid">
                <?php while($order = mysqli_fetch_assoc($orders)): ?>
                    <div class="order-card available-order" data-location="<?php echo strtolower($order['pickup_location'] . ' ' . $order['dropoff_location']); ?>">
                        <div class="order-header">
                            <span class="order-id">#<?php echo $order['parcel_id']; ?></span>
                            <span class="status status-pending">🆓 Available</span>
                        </div>
                        
                        <div class="order-details">
                            <p><strong>👤 From:</strong> <?php echo $order['full_name']; ?></p>
                            <p><strong>📱 Contact:</strong> <?php echo $order['phone']; ?></p>
                            <p><strong>📍 Pickup:</strong> <?php echo $order['pickup_location']; ?></p>
                            <p><strong>📍 Dropoff:</strong> <?php echo $order['dropoff_location']; ?></p>
                            
                            <?php if($order['description']): ?>
                                <p><strong>📝 Description:</strong> <?php echo $order['description']; ?></p>
                            <?php endif; ?>
                            
                            <?php if($order['parcel_weight']): ?>
                                <p><strong>⚖️ Weight:</strong> <?php echo $order['parcel_weight']; ?> kg</p>
                            <?php endif; ?>
                            
                            <p class="fee"><strong>💰 Fee:</strong> RM <?php echo number_format($order['fee'], 2); ?></p>
                        </div>
                        
                        <button onclick="acceptOrder(<?php echo $order['parcel_id']; ?>)" class="btn-success accept-btn">
                            Accept Order
                        </button>
                        
                        <small class="order-time">📅 Posted: <?php echo timeAgo($order['order_time']); ?></small>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
    function acceptOrder(parcelId) {
        if(confirm('Are you sure you want to accept this order?')) {
            window.location.href = 'available_orders.php?accept=' + parcelId;
        }
    }
    
    function filterOrders() {
        const input = document.getElementById('locationFilter');
        const filter = input.value.toLowerCase();
        const orders = document.querySelectorAll('.available-order');
        
        orders.forEach(order => {
            const location = order.getAttribute('data-location');
            if (location.includes(filter)) {
                order.style.display = 'block';
            } else {
                order.style.display = 'none';
            }
        });
    }
    </script>
    
    <?php
    // Helper function
    function timeAgo($timestamp) {
        $time_ago = strtotime($timestamp);
        $current_time = time();
        $time_difference = $current_time - $time_ago;
        $seconds = $time_difference;
        
        $minutes = round($seconds / 60);
        $hours = round($seconds / 3600);
        $days = round($seconds / 86400);
        $weeks = round($seconds / 604800);
        $months = round($seconds / 2629440);
        $years = round($seconds / 31553280);
        
        if ($seconds <= 60) {
            return "Just Now";
        } else if ($minutes <= 60) {
            return ($minutes == 1) ? "1 minute ago" : "$minutes minutes ago";
        } else if ($hours <= 24) {
            return ($hours == 1) ? "1 hour ago" : "$hours hours ago";
        } else if ($days <= 7) {
            return ($days == 1) ? "yesterday" : "$days days ago";
        } else if ($weeks <= 4.3) {
            return ($weeks == 1) ? "1 week ago" : "$weeks weeks ago";
        } else if ($months <= 12) {
            return ($months == 1) ? "1 month ago" : "$months months ago";
        } else {
            return ($years == 1) ? "1 year ago" : "$years years ago";
        }
    }
    ?>
</body>
</html>