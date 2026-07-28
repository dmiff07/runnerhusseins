<?php
require_once 'config.php';
header('Content-Type: application/json');

if (!isLoggedIn() || $_SESSION['user_type'] != 'runner') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $parcel_id = (int)$_POST['parcel_id'];
    $new_status = sanitize($_POST['status']);
    $user_id = $_SESSION['user_id'];
    
    // Verify runner owns this order
    $check = "SELECT * FROM parcels WHERE parcel_id = $parcel_id AND runner_id = $user_id";
    $result = mysqli_query($conn, $check);
    
    if (mysqli_num_rows($result) == 0) {
        echo json_encode(['success' => false, 'message' => 'Order not found or not assigned to you']);
        exit();
    }
    
    $valid_statuses = ['pending', 'accepted', 'picked_up', 'delivered', 'cancelled'];
    if (!in_array($new_status, $valid_statuses)) {
        echo json_encode(['success' => false, 'message' => 'Invalid status']);
        exit();
    }
    
    $update = "UPDATE parcels SET status = '$new_status'";
    
    if ($new_status == 'picked_up') {
        $update .= ", pickup_time = NOW()";
    } elseif ($new_status == 'delivered') {
        $update .= ", delivery_time = NOW()";
    }
    
    $update .= " WHERE parcel_id = $parcel_id";
    
    if (mysqli_query($conn, $update)) {
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status']);
    }
}
?>