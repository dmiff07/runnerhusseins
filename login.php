<?php
require_once 'config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $matric = sanitize($_POST['matric_number']);
    $password = $_POST['password'];
    
    $query = "SELECT * FROM users WHERE matric_number = '$matric'";
    $result = mysqli_query($conn, $query);
    
    if ($user = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['matric_number'] = $user['matric_number'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['user_type'] = $user['user_type'];
            
            redirect('dashboard.php');
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Matric number not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RunnerHussein</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <h1>🏃 RUNNERHUSSEIN</h1>
            <h2>Welcome Back!</h2>
            
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label>Matric Number</label>
                    <input type="text" name="matric_number" placeholder="Enter your matric number" required>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>
                
                <button type="submit" class="btn-primary">Login</button>
            </form>
            
            <p class="auth-link">Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>