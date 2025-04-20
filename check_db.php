<?php
require_once 'config.php';

try {
    // Check database connection
    echo "Database connection successful!\n";

    // Check if users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "Users table exists!\n";
        
        // Check table structure
        $stmt = $pdo->query("DESCRIBE users");
        echo "\nUsers table structure:\n";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "Field: " . $row['Field'] . "\n";
            echo "Type: " . $row['Type'] . "\n";
            echo "Null: " . $row['Null'] . "\n";
            echo "Key: " . $row['Key'] . "\n";
            echo "Default: " . $row['Default'] . "\n";
            echo "Extra: " . $row['Extra'] . "\n\n";
        }
    } else {
        echo "Users table does not exist! Creating it now...\n";
        
        // Create users table
        $sql = "CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $pdo->exec($sql);
        echo "Users table created successfully!\n";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 