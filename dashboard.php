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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tripenure CRM Dashboard</title>
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

        /* Add this CSS to remove underlines from nav items */
        .nav-item {
            padding: 12px 20px;
            cursor: pointer;
            transition: background 0.3s;
            display: flex;
            align-items: center;
            text-decoration: none; /* This removes the underline */
            color: inherit;
        }

        .nav-item:hover {
            background: #f8f9fa;
            text-decoration: none; /* Ensures no underline on hover */
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

        /* Logout button styles */
        .logout-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.3s ease;
            margin: 20px;
            width: calc(100% - 40px);
            text-align: center;
            text-decoration: none;
            display: block;
        }

        .logout-btn:hover {
            background: #c82333;
        }

        /* Add User button styles */
        .add-user-btn {
            background: #ff7b00;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            float: right;
            margin-top: -15px;
        }

        .add-user-btn:hover {
            background: #e66a00;
            transform: translateY(-1px);
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

        /* Metrics Cards */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .metric-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .metric-card.dark::before { background: #2c3e50; }
        .metric-card.blue::before { background: #3498db; }
        .metric-card.green::before { background: #27ae60; }
        .metric-card.purple::before { background: #8e44ad; }
        .metric-card.orange::before { background: #f39c12; }

        .metric-value {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .metric-card.dark .metric-value { color: #2c3e50; }
        .metric-card.blue .metric-value { color: #3498db; }
        .metric-card.green .metric-value { color: #27ae60; }
        .metric-card.purple .metric-value { color: #8e44ad; }
        .metric-card.orange .metric-value { color: #f39c12; }

        .metric-label {
            color: #666;
            font-size: 14px;
        }

        /* SPOC Management Section */
        .spoc-management {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .spoc-header {
            background: linear-gradient(135deg, #4ecdc4, #44a08d);
            margin: -20px -20px 20px -20px;
            padding: 20px;
            border-radius: 12px 12px 0 0;
            color: white;
        }

        .spoc-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .spoc-tabs {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .spoc-tab {
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .spoc-tab.active {
            background: white;
            color: #4ecdc4;
            font-weight: 600;
        }

        .spoc-filters {
            display: flex;
            gap: 15px;
            align-items: center;
            margin-bottom: 20px;
        }

        .filter-dropdown {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: white;
            font-size: 14px;
            cursor: pointer;
        }

        .refresh-btn {
            background: #4ecdc4;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            margin-left: auto;
        }

        .spoc-stats {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 20px;
            margin-bottom: 30px;
            text-align: center;
        }

        .stat-item {
            padding: 15px;
            border-radius: 8px;
            background: #f8f9fa;
        }

        .stat-value {
            font-size: 28px;
            font-weight: bold;
            color: #4ecdc4;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 12px;
            color: #666;
            line-height: 1.3;
        }

        .spoc-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .spoc-table th,
        .spoc-table td {
            padding: 15px 10px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        .spoc-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
            font-size: 12px;
            text-transform: uppercase;
        }

        .spoc-table td {
            color: #666;
            font-size: 14px;
        }

        .spoc-table tbody tr:hover {
            background: #f8f9fa;
        }

        .agent-name {
            text-align: left;
            font-weight: 600;
            color: #333;
        }

        .bottom-stats {
            display: flex;
            justify-content: flex-end;
            gap: 30px;
            margin-top: 20px;
            padding: 15px 0;
            border-top: 1px solid #eee;
        }

        .bottom-stat {
            text-align: center;
        }

        .bottom-stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #4ecdc4;
            margin-bottom: 5px;
        }

        .bottom-stat-label {
            font-size: 12px;
            color: #666;
        }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
        }

        .section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        /* Tasks Section */
        .task-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            border-left: 4px solid #ddd;
        }

        .task-item.completed {
            border-left-color: #27ae60;
            background: #f8fff8;
        }

        .task-item.pending {
            border-left-color: #e74c3c;
            background: #fff8f8;
        }

        .task-item.overdue {
            border-left-color: #95a5a6;
            background: #f8f9fa;
        }

        .task-date {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        .task-description {
            font-size: 14px;
            margin-bottom: 8px;
        }

        .task-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #f8d7da;
            color: #721c24;
        }

        .status-overdue {
            background: #e2e3e5;
            color: #383d41;
        }

        .task-actions {
            margin-left: auto;
            display: flex;
            gap: 10px;
        }

        .action-btn {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .btn-call { background: #27ae60; }
        .btn-pending { background: #e74c3c; }
        .btn-overdue { background: #95a5a6; }

        /* Payments Section */
        .payment-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            border: 1px solid #eee;
        }

        .payment-info {
            flex: 1;
        }

        .payment-id {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .payment-payer {
            font-size: 12px;
            color: #666;
        }

        .payment-amount {
            text-align: right;
        }

        .amount-value {
            font-weight: bold;
            font-size: 16px;
            color: #2c3e50;
        }

        .amount-currency {
            font-size: 12px;
            color: #666;
        }

        .payment-date {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }

        .payment-status {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            background: #d4edda;
            color: #155724;
        }

        /* Users Section */
        .user-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .user-item:last-child {
            border-bottom: none;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 12px;
        }

        .user-avatar.m { background: #3498db; }
        .user-avatar.k { background: #e74c3c; }
        .user-avatar.g { background: #27ae60; }

        .user-info {
            flex: 1;
        }

        .user-name {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 2px;
        }

        .user-role {
            font-size: 12px;
            color: #666;
        }

        .user-status {
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 8px;
            font-weight: bold;
        }

        .status-days { background: #fff3cd; color: #856404; }
        .status-week { background: #f8d7da; color: #721c24; }
        .status-weeks { background: #d1ecf1; color: #0c5460; }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
            }
            
            .content-grid {
                grid-template-columns: 1fr;
            }
            
            .metrics-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
            
            .spoc-stats {
                grid-template-columns: repeat(3, 1fr);
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
            <a href="dashboard.php" class="nav-item active">
                <span>📊</span>
                <span>Dashboard</span>
            </a>
            <a href="leads.php" class="nav-item">
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
            <a href="client.php" class="nav-item">
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
            
            <!-- Logout Button -->
            <a href="logout.php" class="logout-btn">🚪 Logout</a>
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
                        <div class="avatar"><?php echo strtoupper(substr($user_name, 0, 1)); ?></div>
                        <span><?php echo htmlspecialchars($user_name); ?></span>
                        <span>▼</span>
                    </div>
                </div>
            </div>

            <!-- Metrics Grid -->
            <div class="metrics-grid">
                <div class="metric-card dark">
                    <div class="metric-value">11.00</div>
                    <div class="metric-label">Today's Leads</div>
                </div>
                <div class="metric-card dark">
                    <div class="metric-value">18.00</div>
                    <div class="metric-label">Yesterday's Leads</div>
                </div>
                <div class="metric-card blue">
                    <div class="metric-value">79.00</div>
                    <div class="metric-label">Jun Month Leads</div>
                </div>
                <div class="metric-card green">
                    <div class="metric-value">34.00</div>
                    <div class="metric-label">Jun Confirmed Leads</div>
                </div>
                <div class="metric-card purple">
                    <div class="metric-value">31.00</div>
                    <div class="metric-label">Jun Invoice</div>
                </div>
                <div class="metric-card orange">
                    <div class="metric-value">31.00</div>
                    <div class="metric-label">Jun Voucher</div>
                </div>
            </div>

            <!-- SPOC Management Section -->
            <div class="spoc-management">
                <div class="spoc-header">
                    <div class="spoc-title">📋 SPOC MANAGEMENT</div>
                    
                    <div class="spoc-tabs">
                        <div class="spoc-tab active">Funnel Management</div>
                        <div class="spoc-tab">Performance Metric</div>
                        <div class="spoc-tab">SPOC Capacity</div>
                    </div>
                </div>



                                 <!-- Performance Metric Tab Content -->
                 <div id="performance-metric-content" style="display: none;">
                                         <!-- Time Period Filters -->
                     <div style="display: flex; gap: 20px; margin-bottom: 20px; align-items: center;">
                         <div style="display: flex; gap: 15px;">
                             <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                                 <input type="radio" name="timeFilter" value="today" checked style="accent-color: #4ecdc4;">
                                 <span>Today</span>
                             </label>
                             <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                                 <input type="radio" name="timeFilter" value="yesterday" style="accent-color: #4ecdc4;">
                                 <span>Yesterday</span>
                             </label>
                             <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                                 <input type="radio" name="timeFilter" value="last7days" style="accent-color: #4ecdc4;">
                                 <span>Last 7 days</span>
                             </label>
                             <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                                 <input type="radio" name="timeFilter" value="last30days" style="accent-color: #4ecdc4;">
                                 <span>Last 30 days</span>
                             </label>
                         </div>
                     </div>

                    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 30px; color: #666; font-size: 14px;">
                        Click on the card to see the detailed view
                    </div>

                    <!-- Performance Metrics Grid -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                        <!-- Time to first quote -->
                        <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); cursor: pointer; transition: transform 0.3s ease;" onclick="showMetricDetails('quote')">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                                <h3 style="color: #333; margin: 0; font-size: 16px;">Time to first quote (in hrs)</h3>
                                <span style="color: #999; cursor: help;">ℹ️</span>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                                <div style="text-align: center; background: #ffebee; padding: 15px; border-radius: 8px;">
                                    <div style="font-size: 12px; color: #666; margin-bottom: 10px; font-weight: 600;">Benchmark</div>
                                    <div style="font-size: 32px; font-weight: bold; color: #d32f2f;">0.0</div>
                                </div>
                                <div style="text-align: center; background: #ffeaa7; padding: 15px; border-radius: 8px;">
                                    <div style="font-size: 12px; color: #666; margin-bottom: 10px; font-weight: 600;">Yours</div>
                                    <div style="font-size: 32px; font-weight: bold; color: #e17055;">NA</div>
                                </div>
                                <div style="text-align: center; background: #ddd; padding: 15px; border-radius: 8px;">
                                    <div style="font-size: 12px; color: #666; margin-bottom: 10px; font-weight: 600;">Ideal</div>
                                    <div style="font-size: 32px; font-weight: bold; color: #333;">0.0</div>
                                </div>
                            </div>
                        </div>

                        <!-- Time to first follow-up -->
                        <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); cursor: pointer; transition: transform 0.3s ease;" onclick="showMetricDetails('followup')">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                                <h3 style="color: #333; margin: 0; font-size: 16px;">Time to first follow-up (in hrs)</h3>
                                <span style="color: #999; cursor: help;">ℹ️</span>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                                <div style="text-align: center; background: #ffebee; padding: 15px; border-radius: 8px;">
                                    <div style="font-size: 12px; color: #666; margin-bottom: 10px; font-weight: 600;">Benchmark</div>
                                    <div style="font-size: 32px; font-weight: bold; color: #d32f2f;">0.0</div>
                                </div>
                                <div style="text-align: center; background: #ffeaa7; padding: 15px; border-radius: 8px;">
                                    <div style="font-size: 12px; color: #666; margin-bottom: 10px; font-weight: 600;">Yours</div>
                                    <div style="font-size: 32px; font-weight: bold; color: #e17055;">NA</div>
                                </div>
                                <div style="text-align: center; background: #ddd; padding: 15px; border-radius: 8px;">
                                    <div style="font-size: 12px; color: #666; margin-bottom: 10px; font-weight: 600;">Ideal</div>
                                    <div style="font-size: 32px; font-weight: bold; color: #333;">0.0</div>
                                </div>
                            </div>
                        </div>

                        <!-- L2C for best leads -->
                        <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); cursor: pointer; transition: transform 0.3s ease;" onclick="showMetricDetails('l2c')">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                                <h3 style="color: #333; margin: 0; font-size: 16px;">L2C for best leads (in %)</h3>
                                <span style="color: #999; cursor: help;">ℹ️</span>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                                <div style="text-align: center; background: #ffebee; padding: 15px; border-radius: 8px;">
                                    <div style="font-size: 12px; color: #666; margin-bottom: 10px; font-weight: 600;">Benchmark</div>
                                    <div style="font-size: 32px; font-weight: bold; color: #d32f2f;">0.0</div>
                                </div>
                                <div style="text-align: center; background: #ffeaa7; padding: 15px; border-radius: 8px;">
                                    <div style="font-size: 12px; color: #666; margin-bottom: 10px; font-weight: 600;">Yours</div>
                                    <div style="font-size: 32px; font-weight: bold; color: #f39c12;">0.0</div>
                                </div>
                                <div style="text-align: center; background: #ddd; padding: 15px; border-radius: 8px;">
                                    <div style="font-size: 12px; color: #666; margin-bottom: 10px; font-weight: 600;">Ideal</div>
                                    <div style="font-size: 32px; font-weight: bold; color: #333;">0.0</div>
                                </div>
                            </div>
                        </div>

                        <!-- My Hot & Shortlisted leads per spoc -->
                        <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); cursor: pointer; transition: transform 0.3s ease;" onclick="showMetricDetails('hotleads')">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                                <h3 style="color: #333; margin: 0; font-size: 16px;">My Hot & Shortlisted leads per spoc</h3>
                                <span style="color: #999; cursor: help;">ℹ️</span>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                                <div style="text-align: center; background: #ffebee; padding: 15px; border-radius: 8px;">
                                    <div style="font-size: 12px; color: #666; margin-bottom: 10px; font-weight: 600;">Benchmark</div>
                                    <div style="font-size: 32px; font-weight: bold; color: #d32f2f;">0.0</div>
                                </div>
                                <div style="text-align: center; background: #ffeaa7; padding: 15px; border-radius: 8px;">
                                    <div style="font-size: 12px; color: #666; margin-bottom: 10px; font-weight: 600;">Yours</div>
                                    <div style="font-size: 32px; font-weight: bold; color: #f39c12;">0.0</div>
                                </div>
                                <div style="text-align: center; background: #ddd; padding: 15px; border-radius: 8px;">
                                    <div style="font-size: 12px; color: #666; margin-bottom: 10px; font-weight: 600;">Ideal</div>
                                    <div style="font-size: 32px; font-weight: bold; color: #333;">0.0</div>
                                </div>
                            </div>
                        </div>

                        <!-- Leads worked per SPOC per day -->
                        <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); cursor: pointer; transition: transform 0.3s ease;" onclick="showMetricDetails('leadsworked')">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                                <h3 style="color: #333; margin: 0; font-size: 16px;">Leads worked per SPOC per day</h3>
                                <span style="color: #999; cursor: help;">ℹ️</span>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                                <div style="text-align: center; background: #ffebee; padding: 15px; border-radius: 8px;">
                                    <div style="font-size: 12px; color: #666; margin-bottom: 10px; font-weight: 600;">Benchmark</div>
                                    <div style="font-size: 32px; font-weight: bold; color: #d32f2f;">0.0</div>
                                </div>
                                <div style="text-align: center; background: #ffeaa7; padding: 15px; border-radius: 8px;">
                                    <div style="font-size: 12px; color: #666; margin-bottom: 10px; font-weight: 600;">Yours</div>
                                    <div style="font-size: 32px; font-weight: bold; color: #f39c12;">0.0</div>
                                </div>
                                <div style="text-align: center; background: #ddd; padding: 15px; border-radius: 8px;">
                                    <div style="font-size: 12px; color: #666; margin-bottom: 10px; font-weight: 600;">Ideal</div>
                                    <div style="font-size: 32px; font-weight: bold; color: #333;">0.0</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Last Updated Info -->
                    <div style="text-align: right; color: #999; font-size: 12px; margin-top: 30px;">
                        Last updated today at 02:29 AM
                    </div>
                </div>

                                 <!-- Funnel Management Tab Content -->
                 <div id="funnel-management-content">
                     <div style="margin-bottom: 20px;">
                         <span style="font-weight: 600; color: #666;">Admin view</span>
                     </div>

                     <div class="spoc-stats">
                    <div class="stat-item">
                        <div class="stat-value">0</div>
                        <div class="stat-label">No Manual Quotes</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">0</div>
                        <div class="stat-label">No Follow-up</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">0</div>
                        <div class="stat-label">No Follow-up in < 24hrs</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">0</div>
                        <div class="stat-label">No Follow-up in 24-48hrs</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">0</div>
                        <div class="stat-label">No Follow-up in 48-72hrs</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">0</div>
                        <div class="stat-label">No Follow-up in 72+hrs</div>
                    </div>
                </div>

                <table class="spoc-table">
                    <thead>
                        <tr>
                            <th class="agent-name">Spoc Detail</th>
                            <th>0<br>No Manual Quotes</th>
                            <th>0<br>No Follow-up</th>
                            <th>0<br>No Follow-up in < 24hrs</th>
                            <th>0<br>No Follow-up in 24-48hrs</th>
                            <th>0<br>No Follow-up in 48-72hrs</th>
                            <th>0<br>No Follow-up in 72+hrs</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="agent-name">Ravinder Biswal</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td class="agent-name">Ankita Negi</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td class="agent-name">Altaf ahmad</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td class="agent-name">Aditya kumar</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                    </tbody>
                </table>

                                     <div class="bottom-stats">
                         <div class="bottom-stat">
                             <div class="bottom-stat-value">0%</div>
                             <div class="bottom-stat-label">Pending Followup Reach</div>
                         </div>
                         <div class="bottom-stat">
                             <div class="bottom-stat-value">0%</div>
                             <div class="bottom-stat-label">Pending Quote Reach</div>
                         </div>
                     </div>
                 </div>
                 </div>

            <!-- Content Grid -->
            <div class="content-grid">
                <!-- Recent Tasks -->
                <div class="section">
                    <h3 class="section-title">RECENT TASKS & FOLLOW-UP'S</h3>
                    
                    <div class="task-item completed">
                        <div style="flex: 1;">
                            <div class="task-date">📅 22 June 2023 02:00</div>
                            <div class="task-description">📞 call to client</div>
                            <div class="task-status status-completed">Task Completed</div>
                            <div style="margin-top: 8px; color: #27ae60;">✅ Task Completed: done</div>
                        </div>
                        <div class="task-actions">
                            <button class="action-btn btn-call">📞</button>
                        </div>
                    </div>

                    <div class="task-item pending">
                        <div style="flex: 1;">
                            <div class="task-date">📅 20 June 2023 03:00</div>
                            <div class="task-description">📞 Call to agent</div>
                            <div class="task-status status-pending">Task Pending</div>
                        </div>
                        <div class="task-actions">
                            <button class="action-btn btn-pending">📞</button>
                        </div>
                    </div>

                    <div class="task-item overdue">
                        <div style="flex: 1;">
                            <div class="task-date">📅 21 May 2023 00:15</div>
                            <div class="task-description">Task details...</div>
                        </div>
                    </div>
                </div>

                <!-- Scheduled Payments -->
                <div class="section">
                    <h3 class="section-title">SCHEDULED PAYMENT'S</h3>
                    
                    <div class="payment-item">
                        <div class="payment-info">
                            <div class="payment-id">#T0042</div>
                            <div class="payment-payer">Payer: Nitin</div>
                        </div>
                        <div class="payment-amount">
                            <div class="payment-date">📅 18-05-2023</div>
                            <div class="amount-value">477,000.00</div>
                            <div class="amount-currency">INR</div>
                            <div class="payment-status">ACTIVE</div>
                        </div>
                    </div>

                    <div class="payment-item">
                        <div class="payment-info">
                            <div class="payment-id">#T0030</div>
                            <div class="payment-payer">Payer: Rajnish Kumar</div>
                        </div>
                        <div class="payment-amount">
                            <div class="payment-date">📅 20-05-2023</div>
                            <div class="amount-value">86,160.00</div>
                            <div class="amount-currency">INR</div>
                            <div class="payment-status">ACTIVE</div>
                        </div>
                    </div>
                </div>

                <!-- Login Users -->
                <div class="section">
                    <h3 class="section-title">
                        LOGIN USER'S
                        <?php if ($is_admin): ?>
                            <button class="add-user-btn" onclick="openAddUserModal()">+ Add User</button>
                        <?php endif; ?>
                    </h3>
                    
                    <?php
                    // Fetch real users from database
                    try {
                        $stmt = $pdo->query("SELECT id, first_name, last_name, designation, role, email, last_login FROM users ORDER BY id DESC LIMIT 5");
                        $users = $stmt->fetchAll();
                        
                        if (empty($users)) {
                            echo '<div style="text-align: center; color: #666; padding: 20px;">No users found</div>';
                        } else {
                            foreach ($users as $user) {
                                $initial = strtoupper(substr($user['first_name'], 0, 1));
                                $fullName = $user['first_name'] . ' ' . $user['last_name'];
                                $designation = $user['designation'] ?: $user['role'];
                                
                                // Generate random avatar color based on user ID
                                $colors = ['#3498db', '#e74c3c', '#27ae60', '#f39c12', '#9b59b6', '#1abc9c'];
                                $colorIndex = $user['id'] % count($colors);
                                $avatarColor = $colors[$colorIndex];
                                
                                // Calculate real time since last login
                                $lastSeen = 'Never logged in';
                                $statusClass = 'status-weeks';
                                
                                if (!empty($user['last_login'])) {
                                    $lastLogin = new DateTime($user['last_login']);
                                    $now = new DateTime();
                                    $interval = $now->diff($lastLogin);
                                    
                                    if ($interval->y > 0) {
                                        $lastSeen = $interval->y . ' YEAR' . ($interval->y > 1 ? 'S' : '') . ' AGO';
                                        $statusClass = 'status-weeks';
                                    } elseif ($interval->m > 0) {
                                        $lastSeen = $interval->m . ' MONTH' . ($interval->m > 1 ? 'S' : '') . ' AGO';
                                        $statusClass = 'status-weeks';
                                    } elseif ($interval->d > 0) {
                                        if ($interval->d == 1) {
                                            $lastSeen = '1 DAY AGO';
                                            $statusClass = 'status-days';
                                        } elseif ($interval->d < 7) {
                                            $lastSeen = $interval->d . ' DAYS AGO';
                                            $statusClass = 'status-days';
                                        } else {
                                            $weeks = floor($interval->d / 7);
                                            $lastSeen = $weeks . ' WEEK' . ($weeks > 1 ? 'S' : '') . ' AGO';
                                            $statusClass = 'status-week';
                                        }
                                    } elseif ($interval->h > 0) {
                                        $lastSeen = $interval->h . ' HOUR' . ($interval->h > 1 ? 'S' : '') . ' AGO';
                                        $statusClass = 'status-days';
                                    } elseif ($interval->i > 0) {
                                        $lastSeen = $interval->i . ' MINUTE' . ($interval->i > 1 ? 'S' : '') . ' AGO';
                                        $statusClass = 'status-days';
                                    } else {
                                        $lastSeen = 'JUST NOW';
                                        $statusClass = 'status-days';
                                    }
                                }
                                ?>
                                
                                <div class="user-item">
                                    <div class="user-avatar" style="background: <?php echo $avatarColor; ?>"><?php echo $initial; ?></div>
                                    <div class="user-info">
                                        <div class="user-name"><?php echo htmlspecialchars($fullName); ?></div>
                                        <div class="user-role"><?php echo htmlspecialchars($designation); ?></div>
                                    </div>
                                    <div class="user-status <?php echo $statusClass; ?>"><?php echo $lastSeen; ?></div>
                                </div>
                                
                                <?php
                            }
                        }
                    } catch (PDOException $e) {
                        echo '<div style="text-align: center; color: #e74c3c; padding: 20px;">Error loading users</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <?php if ($is_admin): ?>
    <div id="addUserModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
        <div class="modal-content" style="background-color: white; margin: 5% auto; padding: 30px; border-radius: 12px; width: 90%; max-width: 500px; position: relative;">
            <span class="close" onclick="closeAddUserModal()" style="position: absolute; right: 20px; top: 20px; font-size: 28px; cursor: pointer; color: #666;">&times;</span>
            
            <h2 style="margin-bottom: 25px; color: #333;">Add New User</h2>
            
            <form id="addUserForm" action="add_user.php" method="POST">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Email *</label>
                    <input type="email" name="email" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Phone Number</label>
                    <input type="text" name="number" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">First Name *</label>
                        <input type="text" name="first_name" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Last Name *</label>
                        <input type="text" name="last_name" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Password *</label>
                    <input type="password" name="password" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 25px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Designation</label>
                        <input type="text" name="designation" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Role *</label>
                        <select name="role" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                            <option value="">Select Role</option>
                            <option value="Admin">Admin</option>
                            <option value="User">User</option>
                            <option value="Manager">Manager</option>
                            <option value="CEO">CEO</option>
                            <option value="CMO">CMO</option>
                            <option value="CTO">CTO</option>
                        </select>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px; justify-content: flex-end;">
                    <button type="button" onclick="closeAddUserModal()" style="padding: 12px 24px; border: 1px solid #ddd; background: #f8f9fa; border-radius: 8px; cursor: pointer;">Cancel</button>
                    <button type="submit" style="padding: 12px 24px; background: #ff7b00; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Add User</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <script>
        // Add click handlers for navigation
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
            });
        });

                 // SPOC Management tab functionality
         document.querySelectorAll('.spoc-tab').forEach(tab => {
             tab.addEventListener('click', function() {
                 document.querySelectorAll('.spoc-tab').forEach(t => t.classList.remove('active'));
                 this.classList.add('active');
                 
                 // Show/hide different content based on the tab
                 const tabName = this.textContent;
                 console.log('Switched to tab:', tabName);
                 
                 // Hide all tab contents first
                 document.getElementById('performance-metric-content').style.display = 'none';
                 document.getElementById('funnel-management-content').style.display = 'none';
                 
                 // Show content based on selected tab
                 if (tabName === 'Performance Metric') {
                     document.getElementById('performance-metric-content').style.display = 'block';
                 } else if (tabName === 'Funnel Management') {
                     document.getElementById('funnel-management-content').style.display = 'block';
                 } else if (tabName === 'SPOC Capacity') {
                     // Show SPOC capacity content (you can add this later)
                     // For now, show funnel management as default
                     document.getElementById('funnel-management-content').style.display = 'block';
                 }
             });
         });

        // Filter dropdown functionality
        document.querySelectorAll('.filter-dropdown').forEach(dropdown => {
            dropdown.addEventListener('change', function() {
                console.log('Filter changed:', this.value);
                // Add filter logic here
                refreshSPOCData();
            });
        });

        // Refresh button functionality
        document.querySelector('.refresh-btn').addEventListener('click', function() {
            console.log('Refreshing SPOC data...');
            refreshSPOCData();
        });

        function refreshSPOCData() {
            // Add loading state
            const refreshBtn = document.querySelector('.refresh-btn');
            const originalText = refreshBtn.textContent;
            refreshBtn.textContent = 'Refreshing...';
            refreshBtn.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                refreshBtn.textContent = originalText;
                refreshBtn.disabled = false;
                console.log('SPOC data refreshed');
            }, 1500);
        }

        // Add click handlers for task action buttons
        document.querySelectorAll('.action-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (this.classList.contains('btn-call')) {
                    alert('Initiating call...');
                } else if (this.classList.contains('btn-pending')) {
                    alert('Opening pending task...');
                }
            });
        });

        // Search functionality
        document.querySelector('.search-bar input').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            // Add search logic here
            console.log('Searching for:', query);
        });

        // Notification clicks
        document.querySelectorAll('.notification').forEach(notification => {
            notification.addEventListener('click', function() {
                console.log('Notification clicked');
                // Remove alert indicator if present
                this.classList.remove('has-alert');
            });
        });

        // User profile dropdown
        document.querySelector('.user-profile').addEventListener('click', function() {
            alert('User profile menu');
        });

        // Make metrics cards clickable
        document.querySelectorAll('.metric-card').forEach(card => {
            card.style.cursor = 'pointer';
            card.addEventListener('click', function() {
                const label = this.querySelector('.metric-label').textContent;
                alert(`Viewing details for: ${label}`);
            });
        });

        // Payment item clicks
        document.querySelectorAll('.payment-item').forEach(item => {
            item.style.cursor = 'pointer';
            item.addEventListener('click', function() {
                const paymentId = this.querySelector('.payment-id').textContent;
                alert(`Opening payment details for: ${paymentId}`);
            });
        });

        // Task completion toggle
        document.querySelectorAll('.task-item').forEach(item => {
            item.addEventListener('dblclick', function() {
                if (this.classList.contains('pending')) {
                    this.classList.remove('pending');
                    this.classList.add('completed');
                    this.querySelector('.task-status').textContent = 'Task Completed';
                    this.querySelector('.task-status').className = 'task-status status-completed';
                }
            });
        });

        // SPOC table row interactions
        document.querySelectorAll('.spoc-table tbody tr').forEach(row => {
            row.style.cursor = 'pointer';
            row.addEventListener('click', function() {
                const agentName = this.querySelector('.agent-name').textContent;
                alert(`Viewing details for agent: ${agentName}`);
            });
        });

        // Modal functions
        function openAddUserModal() {
            document.getElementById('addUserModal').style.display = 'block';
        }

        function closeAddUserModal() {
            document.getElementById('addUserModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('addUserModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        // Add some interactive animations
        document.querySelectorAll('.stat-item').forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
                this.style.transition = 'all 0.3s ease';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });

                 // Add keyboard shortcuts for quick actions
         document.addEventListener('keydown', function(e) {
             // Ctrl + R for refresh
             if (e.ctrlKey && e.key === 'r') {
                 e.preventDefault();
                 refreshSPOCData();
             }
             
             // Ctrl + U for add user (admin only)
             if (e.ctrlKey && e.key === 'u') {
                 e.preventDefault();
                 <?php if ($is_admin): ?>
                 openAddUserModal();
                 <?php endif; ?>
             }
         });

         // Function to show metric details (called by onclick events)
         function showMetricDetails(metricType) {
             const metricNames = {
                 'quote': 'Time to first quote',
                 'followup': 'Time to first follow-up',
                 'l2c': 'L2C for best leads',
                 'hotleads': 'My Hot & Shortlisted leads per spoc',
                 'leadsworked': 'Leads worked per SPOC per day'
             };
             
             alert(`Detailed view for: ${metricNames[metricType]}\n\nThis would show detailed analytics, charts, and breakdowns for the selected metric.`);
         }

         // Function to show metric details (called by onclick events)
         function showMetricDetails(metricType) {
             const metricNames = {
                 'quote': 'Time to first quote',
                 'followup': 'Time to first follow-up',
                 'l2c': 'L2C for best leads',
                 'hotleads': 'My Hot & Shortlisted leads per spoc',
                 'leadsworked': 'Leads worked per SPOC per day'
             };
             
             alert(`Detailed view for: ${metricNames[metricType]}\n\nThis would show detailed analytics, charts, and breakdowns for the selected metric.`);
         }
    </script>
</body>
</html>