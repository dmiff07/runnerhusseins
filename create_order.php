<?php
require_once 'config.php';

if (!isLoggedIn() || $_SESSION['user_type'] != 'student') {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pickup = sanitize($_POST['pickup_location']);
    $dropoff = sanitize($_POST['dropoff_location']);
    $weight = sanitize($_POST['weight']);
    $description = sanitize($_POST['description']);
    $fee = sanitize($_POST['fee']);
    
    $query = "INSERT INTO parcels (sender_id, pickup_location, dropoff_location, parcel_weight, description, fee) 
              VALUES ($user_id, '$pickup', '$dropoff', '$weight', '$description', '$fee')";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Order created successfully!";
        header("Location: my_orders.php");
        exit();
    } else {
        $error = "Failed to create order: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Order - RunnerHussein</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <h1>📦 Create New Order</h1>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="form-container">
            <form method="POST" action="">
                <div class="form-group">
                    <label>Pickup Location *</label>
                    <input type="text" name="pickup_location" placeholder="e.g., Block A, Student Center" required>
                </div>
                
                <div class="form-group">
                    <label>Dropoff Location *</label>
                    <input type="text" name="dropoff_location" placeholder="e.g., Block C, Library" required>
                </div>
                
                <div class="form-group">
                    <label>Parcel Weight (kg)</label>
                    <input type="number" name="weight" step="0.1" placeholder="0.5" min="0">
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" placeholder="Describe your parcel..."></textarea>
                </div>
                
                <div class="form-group">
                    <label>Offered Fee (RM) *</label>
                    <input type="number" name="fee" step="0.01" placeholder="5.00" required min="1">
                </div>
                
                <button type="submit" class="btn-primary">Post Order</button>
                <a href="dashboard.php" class="btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>