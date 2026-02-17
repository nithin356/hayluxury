<?php
include __DIR__ . '/../includes/db.php';

$queries = [
    "ALTER TABLE products ADD COLUMN type VARCHAR(255) AFTER name",
    "ALTER TABLE products ADD COLUMN color VARCHAR(255) AFTER type",
    "ALTER TABLE products ADD COLUMN size VARCHAR(255) AFTER color",
    "ALTER TABLE products ADD COLUMN weight VARCHAR(50) AFTER size",
    "ALTER TABLE products ADD COLUMN diamond_info VARCHAR(255) AFTER weight",
];

foreach ($queries as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Column added successfully<br>";
    } else {
        echo "Error creating column: " . $conn->error . "<br>";
    }
}

echo "Database successfully updated!";
?>
