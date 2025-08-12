<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
require_once 'config/database.php';

echo "<h2>Debugging Clients Table</h2>";

try {
    // Check if table exists and get structure
    $query = "DESCRIBE clients";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $columns = $stmt->fetchAll();
    
    echo "<h3>Table Structure:</h3>";
    echo "<pre>";
    print_r($columns);
    echo "</pre>";
    
    // Get sample data
    $query = "SELECT * FROM clients LIMIT 5";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $sample_data = $stmt->fetchAll();
    
    echo "<h3>Sample Data (First 5 records):</h3>";
    echo "<pre>";
    print_r($sample_data);
    echo "</pre>";
    
    // Count total records
    $query = "SELECT COUNT(*) as total FROM clients";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $count = $stmt->fetch();
    
    echo "<h3>Total Records: " . $count['total'] . "</h3>";
    
} catch (PDOException $e) {
    echo "<h3>Database Error:</h3>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}
?>