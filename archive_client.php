<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

// Include database connection
require_once 'config/database.php';

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

// Get client ID from POST data
$client_id = isset($_POST['client_id']) ? (int)$_POST['client_id'] : 0;

if ($client_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid client ID']);
    exit();
}

try {
    // Update client status to INACTIVE (or you could create an archived status)
    $query = "UPDATE clients SET status = 'INACTIVE' WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $result = $stmt->execute([$client_id]);
    
    if ($result && $stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Client archived successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Client not found or already archived']);
    }
} catch (PDOException $e) {
    error_log("Archive client error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}
?>