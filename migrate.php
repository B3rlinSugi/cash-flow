<?php
require_once __DIR__ . '/config/database.php';

echo "<h2>Database Migration System</h2>";

try {
    $pdo = getDB();
    echo "<p>Connected to database successfully.</p>";

    // Read the SQL file
    $sql_file = __DIR__ . '/database/cashflow.sql';
    if (!file_exists($sql_file)) {
        die("<p style='color:red'>SQL file not found at: $sql_file</p>");
    }

    $sql = file_get_contents($sql_file);
    
    // Execute the full SQL dump
    echo "<p>Executing SQL queries...</p>";
    $pdo->exec($sql);
    
    echo "<p style='color:green;font-weight:bold'>Migration completed! The tables should now be created.</p>";
    
    // Check tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "<p>Tables in database now: " . implode(", ", $tables) . "</p>";
    
    echo "<a href='/index.php'>Go to App</a>";

} catch (Exception $e) {
    echo "<p style='color:red'><strong>Error:</strong> " . $e->getMessage() . "</p>";
}
