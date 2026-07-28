<?php
// config.php - Database configuration
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'runnerhussein_db';

// Create connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set timezone
date_default_timezone_set('Asia/Kuala_Lumpur');

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Function to sanitize input
function sanitize($input) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars($input));
}
?>