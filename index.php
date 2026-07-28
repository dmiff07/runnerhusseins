<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RunnerHussein - Student Parcel Delivery</title>
    <link rel="stylesheet" href="style.css">
<script src="script.js"></script>
</head>
<body>
    <div class="landing-page">
        <nav class="landing-nav">
            <div class="logo-container">
                <!-- UTHM Logo -->
                <img src="uthm-logo.png" alt="UTHM Logo" class="uthm-logo">
                <div class="logo-text">
                    <span class="logo-main">RUNNERHUSSEIN</span>
                    <span class="logo-sub">UTHM</span>
                </div>
            </div>
            <div class="nav-links">
                <a href="login.php" class="btn-outline">Login</a>
                <a href="register.php" class="btn-primary">Register</a>
            </div>
        </nav>
        
        <div class="hero-section">
            <div class="hero-content">
                <h1>🚚 Send or Earn on Campus</h1>
                <p>Connect with student runners for parcel delivery</p>
                <div class="hero-buttons">
                    <a href="register.php" class="btn-primary btn-large">Get Started</a>
                    <a href="#how-it-works" class="btn-outline btn-large">Learn More</a>
                </div>
            </div>
        </div>
        
        <div class="features-section" id="how-it-works">
            <h2>How It Works</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📦</div>
                    <h3>Post an Order</h3>
                    <p>Students post parcel delivery requests with their desired fee</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🏃</div>
                    <h3>Find a Runner</h3>
                    <p>Student runners accept orders and deliver parcels</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💰</div>
                    <h3>Earn & Save</h3>
                    <p>Runners earn money, students get their parcels delivered</p>
                </div>
            </div>
        </div>
        
        <footer>
            <p>© 2024 RunnerHussein - UTHM Student Parcel Delivery Service</p>
        </footer>
    </div>
</body>
</html>