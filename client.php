<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
require_once 'config/database.php';

// Get user information
$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['user_email'];
$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];

// Check if user is admin
$is_admin = ($user_role === 'Admin');

// Pagination settings
$records_per_page = 25;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $records_per_page;

// Filter parameters
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$sales_person_filter = isset($_GET['sales_person']) ? $_GET['sales_person'] : '';
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';

// Build the WHERE clause for filtering
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

// Get total count for pagination
$count_query = "SELECT COUNT(*) as total FROM clients $where_clause";
$count_stmt = $pdo->prepare($count_query);
$count_stmt->execute($params);
$total_records = $count_stmt->fetch()['total'];
$total_pages = ceil($total_records / $records_per_page);

// Get clients data with pagination
$query = "SELECT * FROM clients $where_clause ORDER BY date DESC LIMIT ? OFFSET ?";
$params[] = $records_per_page;
$params[] = $offset;

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$clients = $stmt->fetchAll();

// Get statistics
$stats_query = "SELECT 
    COUNT(*) as total_clients,
    SUM(CASE WHEN status = 'ACTIVE' THEN 1 ELSE 0 END) as active_clients,
    SUM(CASE WHEN status = 'INACTIVE' THEN 1 ELSE 0 END) as inactive_clients,
    SUM(CASE WHEN date >= DATE_SUB(NOW(), INTERVAL 1 MONTH) THEN 1 ELSE 0 END) as new_this_month
FROM clients";
$stats_stmt = $pdo->prepare($stats_query);
$stats_stmt->execute();
$stats = $stats_stmt->fetch();

// Get unique sales persons for filter dropdown
$sales_persons_query = "SELECT DISTINCT sales_person FROM clients WHERE sales_person IS NOT NULL AND sales_person != '' ORDER BY sales_person";
$sales_persons_stmt = $pdo->prepare($sales_persons_query);
$sales_persons_stmt->execute();
$sales_persons = $sales_persons_stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tripenure CRM Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f7fa;
            color: #333;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: white;
            padding: 20px 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .logo {
            padding: 0 20px 30px;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }

        .logo h2 {
            color: #ff7b00;
            font-size: 24px;
        }

        .logo span {
            font-weight: normal;
            color: #666;
        }

        .nav-item {
            padding: 12px 20px;
            cursor: pointer;
            transition: background 0.3s;
            display: flex;
            align-items: center;
            text-decoration: none;
            color: inherit;
        }

        .nav-item:hover {
            background: #f8f9fa;
            text-decoration: none;
            color: inherit;
        }

        .nav-item.active {
            background: #fff3e0;
            color: #ff7b00;
            border-right: 3px solid #ff7b00;
        }

        .nav-item span {
            margin-left: 10px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .search-bar {
            flex: 1;
            max-width: 400px;
            margin: 0 20px;
        }

        .search-bar input {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 20px;
            font-size: 14px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .notification {
            position: relative;
            width: 35px;
            height: 35px;
            background: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .notification.has-alert::after {
            content: '';
            position: absolute;
            top: 5px;
            right: 5px;
            width: 8px;
            height: 8px;
            background: #ff4757;
            border-radius: 50%;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .avatar {
            width: 35px;
            height: 35px;
            background: #4a90e2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        /* Page Content */
        .page-content {
            padding: 0;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-title {
            color: #5a5c69;
            font-size: 28px;
            font-weight: 400;
            margin-bottom: 20px;
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #4e73df;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-card.active { border-left-color: #1cc88a; }
        .stat-card.inactive { border-left-color: #e74a3b; }
        .stat-card.new { border-left-color: #f6c23e; }
        .stat-card.vip { border-left-color: #9b59b6; }

        .stat-info h3 {
            font-size: 24px;
            font-weight: 700;
            color: #5a5c69;
            margin-bottom: 5px;
        }

        .stat-info p {
            font-size: 12px;
            color: #858796;
            text-transform: uppercase;
            font-weight: 600;
        }

        .stat-icon {
            font-size: 24px;
            color: #dddfeb;
        }

        /* Filters */
        .filters-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .filters-row {
            display: flex;
            gap: 15px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            min-width: 150px;
        }

        .filter-group label {
            font-size: 12px;
            color: #858796;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .filter-group input,
        .filter-group select {
            padding: 8px 12px;
            border: 1px solid #d1d3e2;
            border-radius: 4px;
            font-size: 14px;
            min-width: 150px;
        }

        .search-btn {
            background: #1cc88a;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: background 0.3s;
        }

        .search-btn:hover {
            background: #17a673;
        }

        /* Add Client Button */
        .add-client-btn {
            background: #e74a3b;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            margin-bottom: 20px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .add-client-btn:hover {
            background: #c0392b;
        }

        /* Clients Table */
        .clients-table-container {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .clients-table {
            width: 100%;
            border-collapse: collapse;
        }

        .clients-table thead {
            background: #5a6c7d;
            color: white;
        }

        .clients-table th,
        .clients-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e3e6f0;
        }

        .clients-table th {
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
        }

        .clients-table tbody tr:hover {
            background: #f8f9fc;
        }

        .client-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #4e73df;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 10px;
        }

        .client-info {
            display: flex;
            align-items: center;
        }

        .client-details h4 {
            color: #5a5c69;
            margin-bottom: 2px;
            font-size: 14px;
        }

        .client-details p {
            color: #858796;
            font-size: 12px;
            margin: 2px 0;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-active {
            background: #d1eddf;
            color: #1cc88a;
        }

        .status-inactive {
            background: #f8d7da;
            color: #e74a3b;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: background 0.3s;
        }

        .edit-btn {
            background: #4e73df;
            color: white;
        }

        .edit-btn:hover {
            background: #2e59d9;
        }

        .archive-btn {
            background: #e74a3b;
            color: white;
        }

        .archive-btn:hover {
            background: #c0392b;
        }

        /* Pagination */
        .pagination-container {
            padding: 20px;
            background: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #e3e6f0;
        }

        .pagination-info {
            color: #858796;
            font-size: 14px;
        }

        .pagination {
            display: flex;
            gap: 5px;
        }

        .page-btn {
            padding: 8px 12px;
            border: 1px solid #d1d3e2;
            background: white;
            color: #858796;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .page-btn:hover,
        .page-btn.active {
            background: #4e73df;
            color: white;
            border-color: #4e73df;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                z-index: 1000;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .menu-toggle {
                display: block;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }

            .filters-row {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-group {
                width: 100%;
            }

            .search-bar {
                width: 100%;
                max-width: none;
            }

            .clients-table-container {
                overflow-x: auto;
            }

            .header {
                flex-direction: column;
                gap: 15px;
                align-items: stretch;
            }

            .header-left {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h2>Tripenure<span> CRM</span></h2>
            </div>
            <a href="dashboard.html" class="nav-item">
                <span>📊</span>
                <span>Dashboard</span>
            </a>
            <a href="leads.html" class="nav-item">
                <span>🔍</span>
                <span>Leads</span>
            </a>
            <a href="#" class="nav-item">
                <span>🧾</span>
                <span>Invoice</span>
            </a>
            <a href="#" class="nav-item">
                <span>🎫</span>
                <span>Voucher</span>
            </a>
            <a href="client.html" class="nav-item active">
                <span>👥</span>
                <span>Clients</span>
            </a>
            <a href="#" class="nav-item">
                <span>⚙️</span>
                <span>Setting</span>
            </a>
            <a href="#" class="nav-item">
                <span>📄</span>
                <span>Reports</span>
            </a>
        </div>

        <!-- Main Content -->
       <div class="main-content">
        <!-- Header -->
        <div class="header">
            <div class="hamburger">☰</div>
            <div class="search-bar">
                <input type="text" placeholder="Search...">
            </div>
            <div class="header-actions">
                <div class="notification has-alert">🔔</div>
                <div class="user-profile">
                    <div class="avatar">TC</div>
                    <span>Tripenure CRM</span>
                    <span>▼</span>
                </div>
            </div>
        </div>

            <!-- Page Content -->
            <div class="page-content">
                <div class="page-header">
                    <h1 class="page-title">Clients Management</h1>
                </div>

                <!-- Stats Cards -->
                <div class="stats-container">
                    <div class="stat-card active">
                        <div class="stat-info">
                            <h3><?php echo number_format($stats['total_clients']); ?></h3>
                            <p>Total Clients</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="stat-card active">
                        <div class="stat-info">
                            <h3><?php echo number_format($stats['active_clients']); ?></h3>
                            <p>Active Clients</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                    <div class="stat-card inactive">
                        <div class="stat-info">
                            <h3><?php echo number_format($stats['inactive_clients']); ?></h3>
                            <p>Inactive</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-user-times"></i>
                        </div>
                    </div>
                    <div class="stat-card new">
                        <div class="stat-info">
                            <h3><?php echo number_format($stats['new_this_month']); ?></h3>
                            <p>New This Month</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                    </div>
                    <div class="stat-card vip">
                        <div class="stat-info">
                            <h3><?php echo number_format($stats['total_clients']); ?></h3>
                            <p>VIP Clients</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="filters-section">
                    <form method="GET" action="client.php">
                        <div class="filters-row">
                            <div class="filter-group">
                                <label>From Date</label>
                                <input type="date" name="from_date" id="fromDate" value="<?php echo htmlspecialchars($from_date); ?>">
                            </div>
                            <div class="filter-group">
                                <label>To Date</label>
                                <input type="date" name="to_date" id="toDate" value="<?php echo htmlspecialchars($to_date); ?>">
                            </div>
                            <div class="filter-group">
                                <label>Client Name/Email</label>
                                <input type="text" name="search" placeholder="Search client..." id="clientSearch" value="<?php echo htmlspecialchars($search_term); ?>">
                            </div>
                            <div class="filter-group">
                                <label>Status</label>
                                <select name="status" id="statusFilter">
                                    <option value="">All Status</option>
                                    <option value="ACTIVE" <?php echo ($status_filter === 'ACTIVE') ? 'selected' : ''; ?>>Active</option>
                                    <option value="INACTIVE" <?php echo ($status_filter === 'INACTIVE') ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                            <div class="filter-group">
                                <label>Sales Person</label>
                                <select name="sales_person" id="salesPersonFilter">
                                    <option value="">All Sales Person</option>
                                    <?php foreach ($sales_persons as $person): ?>
                                        <option value="<?php echo htmlspecialchars($person['sales_person']); ?>" 
                                                <?php echo ($sales_person_filter === $person['sales_person']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($person['sales_person']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="search-btn">
                                <i class="fas fa-search"></i>
                                Search
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Add Client Button -->
                <a href="#" class="add-client-btn" onclick="addNewClient()">
                    <i class="fas fa-plus"></i>
                    Add New Client
                </a>

                <!-- Clients Table -->
                <div class="clients-table-container">
                    <table class="clients-table">
                        <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Contact Info</th>
                                <th>Address</th>
                                <th>Sales Person</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="clientsTableBody">
                            <?php if (empty($clients)): ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 40px; color: #666;">
                                        <i class="fas fa-search" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                                        <p>No clients found</p>
                                        <?php if (!empty($search_term) || !empty($status_filter) || !empty($sales_person_filter) || !empty($from_date) || !empty($to_date)): ?>
                                            <a href="client.php" style="margin-top: 10px; padding: 8px 16px; background: #4e73df; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block;">
                                                Clear All Filters
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($clients as $client): ?>
                                    <tr>
                                        <td>
                                            <div class="client-info">
                                                <div class="client-avatar">
                                                    <?php echo strtoupper(substr($client['client_name'], 0, 1)); ?>
                                                </div>
                                                <div class="client-details">
                                                    <h4><?php echo htmlspecialchars($client['client_name']); ?></h4>
                                                    <p><?php echo htmlspecialchars($client['email']); ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="client-details">
                                                <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($client['email']); ?></p>
                                                <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($client['phone']); ?></p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="client-details">
                                                <p><?php echo htmlspecialchars($client['address']); ?></p>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($client['sales_person'] ?? 'Not Assigned'); ?></td>
                                        <td>
                                            <span class="status-badge <?php echo ($client['status'] === 'ACTIVE') ? 'status-active' : 'status-inactive'; ?>">
                                                <?php echo htmlspecialchars($client['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('d M Y', strtotime($client['date'])); ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="edit_client.php?id=<?php echo $client['id']; ?>" class="action-btn edit-btn">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="#" class="action-btn archive-btn" onclick="archiveClient(<?php echo $client['id']; ?>)">
                                                    <i class="fas fa-archive"></i> Archive
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    <div class="pagination-container">
                        <div class="pagination-info">
                            Showing <?php echo count($clients); ?> Records of <?php echo number_format($total_records); ?> entries
                        </div>
                        <div class="pagination">
                            <?php if ($current_page > 1): ?>
                                <a href="?page=<?php echo ($current_page - 1); ?>&search=<?php echo urlencode($search_term); ?>&status=<?php echo urlencode($status_filter); ?>&sales_person=<?php echo urlencode($sales_person_filter); ?>&from_date=<?php echo urlencode($from_date); ?>&to_date=<?php echo urlencode($to_date); ?>" class="page-btn">Previous</a>
                            <?php endif; ?>
                            
                            <?php
                            $start_page = max(1, $current_page - 2);
                            $end_page = min($total_pages, $current_page + 2);
                            
                            for ($i = $start_page; $i <= $end_page; $i++):
                            ?>
                                <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search_term); ?>&status=<?php echo urlencode($status_filter); ?>&sales_person=<?php echo urlencode($sales_person_filter); ?>&from_date=<?php echo urlencode($from_date); ?>&to_date=<?php echo urlencode($to_date); ?>" 
                                   class="page-btn <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($current_page < $total_pages): ?>
                                <a href="?page=<?php echo ($current_page + 1); ?>&search=<?php echo urlencode($search_term); ?>&status=<?php echo urlencode($status_filter); ?>&sales_person=<?php echo urlencode($sales_person_filter); ?>&from_date=<?php echo urlencode($from_date); ?>&to_date=<?php echo urlencode($to_date); ?>" class="page-btn">Next</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Add new client function
        function addNewClient() {
            window.location.href = 'add_client.php';
        }

        // Archive client function
        function archiveClient(clientId) {
            if (confirm('Are you sure you want to archive this client?')) {
                // Make AJAX request to archive the client
                fetch('archive_client.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'client_id=' + clientId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Client archived successfully');
                        // Reload the page to refresh the data
                        window.location.reload();
                    } else {
                        alert('Error archiving client: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error archiving client');
                });
            }
        }

        // Export filtered data function
        function exportFilteredData() {
            // Get current URL parameters to maintain filters
            const urlParams = new URLSearchParams(window.location.search);
            const exportUrl = 'export_clients.php?' + urlParams.toString();
            
            // Open export in new window
            window.open(exportUrl, '_blank');
        }

        // Add export button to filters section
        document.addEventListener('DOMContentLoaded', function() {
            const filtersRow = document.querySelector('.filters-row');
            const exportBtn = document.createElement('button');
            exportBtn.type = 'button';
            exportBtn.className = 'search-btn';
            exportBtn.style.background = '#6c757d';
            exportBtn.innerHTML = '<i class="fas fa-download"></i> Export CSV';
            exportBtn.onclick = exportFilteredData;
            
            // Add export button to the filters row
            filtersRow.appendChild(exportBtn);
        });

        // Auto-submit form on filter change (optional - for better UX)
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const inputs = form.querySelectorAll('input, select');
            
            // Add event listeners for auto-submit (uncomment if desired)
            /*
            inputs.forEach(input => {
                if (input.type === 'date' || input.tagName === 'SELECT') {
                    input.addEventListener('change', function() {
                        form.submit();
                    });
                }
                
                if (input.type === 'text') {
                    let timeout;
                    input.addEventListener('input', function() {
                        clearTimeout(timeout);
                        timeout = setTimeout(() => {
                            form.submit();
                        }, 1000); // Wait 1 second after user stops typing
                    });
                }
            });
            */
        });
    </script>
</body>
</html>