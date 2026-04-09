<?php
require_once __DIR__ . '/config/database.php';
try {
    $pdo = getDB();
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "SUCCESS: Connected to DB. Tables found: " . implode(", ", $tables) . "<br>";
    
    // Check if kas exists
    $stmt = $pdo->query("SELECT COUNT(*) FROM kas");
    echo "Table 'kas' has " . $stmt->fetchColumn() . " rows.<br>";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "<br>";
}
