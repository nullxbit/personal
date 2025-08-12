<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
require_once 'config/database.php';

// Get filter parameters (same as in client.php)
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$sales_person_filter = isset($_GET['sales_person']) ? $_GET['sales_person'] : '';
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';

// Build the WHERE clause for filtering (same logic as client.php)
$where_conditions = [];
$params = [];

if (!empty($search_term)) {
    $where_conditions[] = "(client_name LIKE ? OR email LIKE ? OR phone LIKE ?)";
    $params[] = "%$search_term%";
    $params[] = "%$search_term%";
    $params[] = "%$search_term%";
}

if (!empty($status_filter)) {
    $where_conditions[] = "status = ?";
    $params[] = $status_filter;
}

if (!empty($sales_person_filter)) {
    $where_conditions[] = "sales_person = ?";
    $params[] = $sales_person_filter;
}

if (!empty($from_date)) {
    $where_conditions[] = "date >= ?";
    $params[] = $from_date;
}

if (!empty($to_date)) {
    $where_conditions[] = "date <= ?";
    $params[] = $to_date;
}

$where_clause = '';
if (!empty($where_conditions)) {
    $where_clause = 'WHERE ' . implode(' AND ', $where_conditions);
}

// Get all matching clients (no pagination for export)
$query = "SELECT * FROM clients $where_clause ORDER BY date DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$clients = $stmt->fetchAll();

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="clients_export_' . date('Y-m-d_H-i-s') . '.csv"');
header('Pragma: no-cache');
header('Expires: 0');

// Create file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Add CSV headers
fputcsv($output, [
    'ID',
    'Client Name',
    'Email',
    'Phone',
    'Address',
    'Sales Person',
    'Status',
    'Date'
]);

// Add data rows
foreach ($clients as $client) {
    fputcsv($output, [
        $client['id'],
        $client['client_name'],
        $client['email'],
        $client['phone'],
        $client['address'],
        $client['sales_person'] ?? 'Not Assigned',
        $client['status'],
        date('d M Y', strtotime($client['date']))
    ]);
}

// Close the file pointer
fclose($output);
exit();
?>