<?php
// Define base URL for assets
if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) {
    define('BASE_URL', 'http://localhost/hayluxury');
} else {
    define('BASE_URL', 'https://' . $_SERVER['HTTP_HOST']);
}

$servername = "68.178.236.80";
$username = "hayluxury";
$password = "hayluxury@123"; // Default XAMPP password is empty
$dbname = "hayluxury";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>