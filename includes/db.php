<?php
// Define base URL for assets
if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === 'localhost:80' || $_SERVER['HTTP_HOST'] === 'localhost:8080') {
    define('BASE_URL', 'http://localhost/hayluxury');
} else {
    define('BASE_URL', 'https://' . $_SERVER['HTTP_HOST'] . '/hayluxury');
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