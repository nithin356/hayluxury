<?php
include __DIR__ . '/../includes/db.php';

// Create gold_price_settings table
$sql = "CREATE TABLE IF NOT EXISTS `gold_price_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `karat` varchar(10) NOT NULL,
  `price_per_gram` decimal(10,2) NOT NULL DEFAULT 0.00,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `karat` (`karat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql) === TRUE) {
    echo "Table 'gold_price_settings' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Insert default karat rows
$karats = ['24K', '22K', '21K', '18K'];
foreach ($karats as $k) {
    $stmt = $conn->prepare("INSERT IGNORE INTO gold_price_settings (karat, price_per_gram) VALUES (?, 0)");
    $stmt->bind_param("s", $k);
    if ($stmt->execute()) {
        echo "Inserted default row for $k<br>";
    } else {
        echo "Row for $k already exists or error: " . $stmt->error . "<br>";
    }
    $stmt->close();
}

// Create gold price history table for audit trail
$sql2 = "CREATE TABLE IF NOT EXISTS `gold_price_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `karat` varchar(10) NOT NULL,
  `old_price` decimal(10,2) NOT NULL,
  `new_price` decimal(10,2) NOT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql2) === TRUE) {
    echo "Table 'gold_price_history' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

echo "<br><strong>Gold price setup complete!</strong> <a href='manage_gold_price.php'>Go to Gold Price Manager</a>";
?>
