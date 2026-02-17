<?php
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