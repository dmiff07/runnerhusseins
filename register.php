<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $matric = sanitize($_POST['matric_number']);
    $name = sanitize($_POST['full_name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user_type = sanitize($_POST['user_type']);
    
    // Check if matric number or email exists
    $check = "SELECT * FROM users WHERE matric_number = '$matric' OR email = '$email'";
    $result = mysqli_query($conn, $check);
    
    if (mysqli_num_rows($result) > 0) {
        $error = "Matric number or email already registered!";
    } else {
        $query = "INSERT INTO users (matric_number, full_name, email, phone, password, user_type) 
                  VALUES ('$matric', '$name', '$email', '$phone', '$password', '$user_type')";
        
        if (mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Registration successful! Please login.";
            header("Location: login.php");
            exit();
        } else {
            $error = "Registration failed: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - RunnerHussein</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <h1>🏃 RUNNERHUSSEIN</h1>
            <h2>Create Account</h2>
            
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label>Matric Number</label>
                    <input type="text" name="matric_number" placeholder="e.g., A12345" required>
                </div>
                
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" placeholder="Enter your full name" required>
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="your@student.com" required>
                </div>
                
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" placeholder="012-3456789" required>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Min 6 characters" required minlength="6">
                </div>
                
                <div class="form-group">
                    <label>I want to be a:</label>
                    <select name="user_type" required>
                        <option value="student">Student (Send parcels)</option>
                        <option value="runner">Runner (Deliver parcels)</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-primary">Register</button>
            </form>
            
            <p class="auth-link">Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>