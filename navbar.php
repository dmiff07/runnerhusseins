<!-- navbar.php -->
<nav class="navbar">
    <div class="nav-container">
        <div class="logo-container">
            <!-- UTHM Logo -->
            <img src="uthm-logo.png" alt="UTHM Logo" class="uthm-logo">
            <div class="logo-text">
                <span class="logo-main">RUNNERHUSSEIN</span>
                <span class="logo-sub">UTHM</span>
            </div>
        </div>
        <ul class="nav-menu">
            <li><a href="dashboard.php">Dashboard</a></li>
            
            <?php if($_SESSION['user_type'] == 'student'): ?>
                <li><a href="create_order.php">Create Order</a></li>
                <li><a href="my_orders.php">My Orders</a></li>
            <?php else: ?>
                <li><a href="available_orders.php">Available Orders</a></li>
                <li><a href="my_deliveries.php">My Deliveries</a></li>
            <?php endif; ?>
            
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php" class="logout-btn">Logout</a></li>
        </ul>
    </div>
</nav>