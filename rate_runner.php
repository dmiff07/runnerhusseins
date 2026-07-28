<?php
require_once 'config.php';
header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $parcel_id = (int)$_POST['parcel_id'];
    $rating = (int)$_POST['rating'];
    $review = sanitize($_POST['review'] ?? '');
    $rated_by = $_SESSION['user_id'];
    
    // Get runner_id from parcel
    $query = "SELECT runner_id FROM parcels WHERE parcel_id = $parcel_id";
    $result = mysqli_query($conn, $query);
    $parcel = mysqli_fetch_assoc($result);
    
    if (!$parcel) {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        exit();
    }
    
    $runner_id = $parcel['runner_id'];
    
    // Insert rating
    $insert = "INSERT INTO ratings (parcel_id, rated_by, rated_user, rating_value, review) 
               VALUES ($parcel_id, $rated_by, $runner_id, $rating, '$review')";
    
    if (mysqli_query($conn, $insert)) {
        // Update runner's average rating
        $avg_query = "SELECT AVG(rating_value) as avg_rating FROM ratings WHERE rated_user = $runner_id";
        $avg_result = mysqli_query($conn, $avg_query);
        $avg = mysqli_fetch_assoc($avg_result);
        
        $new_rating = round($avg['avg_rating'], 2);
        $update = "UPDATE users SET rating = $new_rating WHERE user_id = $runner_id";
        mysqli_query($conn, $update);
        
        echo json_encode(['success' => true, 'message' => 'Rating submitted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to submit rating']);
    }
}
?>