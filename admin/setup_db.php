<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read the SQL file
$sql = file_get_contents(__DIR__ . '/../database_setup.sql');

// Execute multi query
if ($conn->multi_query($sql)) {
    echo "Database and tables created successfully!";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->close();
?>
