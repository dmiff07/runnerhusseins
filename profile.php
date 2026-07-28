<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

// Get user data
$query = "SELECT * FROM users WHERE user_id = $user_id";
$user = mysqli_fetch_assoc(mysqli_query($conn, $query));

// Update profile
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $name = sanitize($_POST['full_name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    
    $update = "UPDATE users SET full_name = '$name', email = '$email', phone = '$phone' WHERE user_id = $user_id";
    
    if (mysqli_query($conn, $update)) {
        $_SESSION['full_name'] = $name;
        $_SESSION['success'] = "Profile updated successfully!";
        header("Location: profile.php");
        exit();
    } else {
        $error = "Failed to update profile.";
    }
}

// Change password
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];
    
    // Verify current password
    $pass_query = "SELECT password FROM users WHERE user_id = $user_id";
    $pass_result = mysqli_fetch_assoc(mysqli_query($conn, $pass_query));
    
    if (!password_verify($current, $pass_result['password'])) {
        $pass_error = "Current password is incorrect!";
    } elseif ($new != $confirm) {
        $pass_error = "New passwords do not match!";
    } elseif (strlen($new) < 6) {
        $pass_error = "Password must be at least 6 characters!";
    } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $update = "UPDATE users SET password = '$hashed' WHERE user_id = $user_id";
        
        if (mysqli_query($conn, $update)) {
            $_SESSION['success'] = "Password changed successfully!";
            header("Location: profile.php");
            exit();
        } else {
            $pass_error = "Failed to change password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - RunnerHussein</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <h1>👤 My Profile</h1>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <div class="profile-grid">
            <!-- Profile Info -->
            <div class="profile-card">
                <h2>Personal Information</h2>
                
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label>Matric Number</label>
                        <input type="text" value="<?php echo $user['matric_number']; ?>" disabled>
                    </div>
                    
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="full_name" value="<?php echo $user['full_name']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
                    </div>
                    
                    <div class="