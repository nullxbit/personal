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
                            <h3>847</h3>
                            <p>Total Clients</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="stat-card active">
                        <div class="stat-info">
                            <h3>623</h3>
                            <p>Active Clients</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                    <div class="stat-card inactive">
                        <div class="stat-info">
                            <h3>124</h3>
                            <p>Inactive</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-user-times"></i>
                        </div>
                    </div>
                    <div class="stat-card new">
                        <div class="stat-info">
                            <h3>67</h3>
                            <p>New This Month</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                    </div>
                    <div class="stat-card vip">
                        <div class="stat-info">
                            <h3>33</h3>
                            <p>VIP Clients</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="filters-section">
                    <div class="filters-row">
                        <div class="filter-group">
                            <label>From Date</label>
                            <input type="date" id="fromDate">
                        </div>
                        <div class="filter-group">
                            <label>To Date</label>
                            <input type="date" id="toDate">
                        </div>
                        <div class="filter-group">
                            <label>Client Name/Email</label>
                            <input type="text" placeholder="Search client..." id="clientSearch">
                        </div>
                        <div class="filter-group">
                            <label>Status</label>
                            <select id="statusFilter">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Sales Person</label>
                            <select id="salesPersonFilter">
                                <option value="">All Sales Person</option>
                                <option value="felix">Felix Feria Travel</option>
                                <option value="john">John Smith</option>
                                <option value="sarah">Sarah Wilson</option>
                            </select>
                        </div>
                        <button class="search-btn" onclick="filterClients()">
                            <i class="fas fa-search"></i>
                            Search
                        </button>
                    </div>
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
                            <tr>
                                <td>
                                    <div class="client-info">
                                        <div class="client-avatar">G</div>
                                        <div class="client-details">
                                            <h4>Gopal Vyas</h4>
                                            <p>gopalvyas795@gmail.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="client-details">
                                        <p><i class="fas fa-envelope"></i> gopalvyas795@gmail.com</p>
                                        <p><i class="fas fa-phone"></i> 75680 40856</p>
                                    </div>
                                </td>
                                <td>
                                    <div class="client-details">
                                        <p>Delhi, Delhi, India</p>
                                        <p>metro station 3E/14, near jhandewalan</p>
                                    </div>
                                </td>
                                <td>Felix Feria Travel</td>
                                <td><span class="status-badge status-active">Active</span></td>
                                <td>24 Jun 2023</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="#" class="action-btn edit-btn" onclick="editClient(1)">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="#" class="action-btn archive-btn" onclick="archiveClient(1)">
                                            <i class="fas fa-archive"></i> Archive
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="client-info">
                                        <div class="client-avatar">S</div>
                                        <div class="client-details">
                                            <h4>Shalini Singh</h4>
                                            <p>shalinee22@gmail.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="client-details">
                                        <p><i class="fas fa-envelope"></i> shalinee22@gmail.com</p>
                                        <p><i class="fas fa-phone"></i> 9557017521</p>
                                    </div>
                                </td>
                                <td>
                                    <div class="client-details">
                                        <p>Bhopal, Madhya Pradesh, India</p>
                                        <p>Gm -19 block -b mansarovar complex bhopal madhya pardesh 462016</p>
                                    </div>
                                </td>
                                <td>Felix Feria Travel</td>
                                <td><span class="status-badge status-active">Active</span></td>
                                <td>24 Jun 2023</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="#" class="action-btn edit-btn" onclick="editClient(2)">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="#" class="action-btn archive-btn" onclick="archiveClient(2)">
                                            <i class="fas fa-archive"></i> Archive
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="client-info">
                                        <div class="client-avatar">C</div>
                                        <div class="client-details">
                                            <h4>Chintu</h4>
                                            <p>Koletiivijay2590@gmail.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="client-details">
                                        <p><i class="fas fa-envelope"></i> Koletiivijay2590@gmail.com</p>
                                        <p><i class="fas fa-phone"></i> 9508495083</p>
                                    </div>
                                </td>
                                <td>
                                    <div class="client-details">
                                        <p>Delhi, Delhi, India</p>
                                        <p>C-26, Anoop Nagar Pankha Road West Delhi 110059</p>
                                    </div>
                                </td>
                                <td>Felix Feria Travel</td>
                                <td><span class="status-badge status-active">Active</span></td>
                                <td>24 Jun 2023</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="#" class="action-btn edit-btn" onclick="editClient(3)">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="#" class="action-btn archive-btn" onclick="archiveClient(3)">
                                            <i class="fas fa-archive"></i> Archive
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="client-info">
                                        <div class="client-avatar">S</div>
                                        <div class="client-details">
                                            <h4>Sushma</h4>
                                            <p>vsushma93@gmail.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="client-details">
                                        <p><i class="fas fa-envelope"></i> vsushma93@gmail.com</p>
                                        <p><i class="fas fa-phone"></i> 9148094128</p>
                                    </div>
                                </td>
                                <td>
                                    <div class="client-details">
                                        <p>Delhi, Delhi, India</p>
                                        <p>Galaxy Stephire Noida Ext. Off 301, Jawahar Park New Delhi 110093</p>
                                    </div>
                                </td>
                                <td>Felix Feria Travel</td>
                                <td><span class="status-badge status-active">Active</span></td>
                                <td>24 Jun 2023</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="#" class="action-btn edit-btn" onclick="editClient(4)">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="#" class="action-btn archive-btn" onclick="archiveClient(4)">
                                            <i class="fas fa-archive"></i> Archive
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="client-info">
                                        <div class="client-avatar">M</div>
                                        <div class="client-details">
                                            <h4>Manjunath</h4>
                                            <p>manju0893@gmail.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="client-details">
                                        <p><i class="fas fa-envelope"></i> manju0893@gmail.com</p>
                                        <p><i class="fas fa-phone"></i> 9481783618</p>
                                    </div>
                                </td>
                                <td>
                                    <div class="client-details">
                                        <p>Kolkata, West Bengal, India</p>
                                        <p>8 num Lalin Sarani,2nd Floor,Wachal Molla Building,700013</p>
                                    </div>
                                </td>
                                <td>Felix Feria Travel</td>
                                <td><span class="status-badge status-active">Active</span></td>
                                <td>24 Jun 2023</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="#" class="action-btn edit-btn" onclick="editClient(5)">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="#" class="action-btn archive-btn" onclick="archiveClient(5)">
                                            <i class="fas fa-archive"></i> Archive
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="client-info">
                                        <div class="client-avatar">K</div>
                                        <div class="client-details">
                                            <h4>Kirti Agrawal</h4>
                                            <p>gargi.kirti07@gmail.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="client-details">
                                        <p><i class="fas fa-envelope"></i> gargi.kirti07@gmail.com</p>
                                        <p><i class="fas fa-phone"></i> 7699640760</p>
                                    </div>
                                </td>
                                <td>
                                    <div class="client-details">
                                        <p>Kolkata, West Bengal, India</p>
                                        <p>Behala Commercial Complex - 1, 620, Diamond Harbour Rd, opp. VIVEKANANDA WOMENS COLLEGE</p>
                                    </div>
                                </td>
                                <td>Felix Feria Travel</td>
                                <td><span class="status-badge status-active">Active</span></td>
                                <td>24 Jun 2023</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="#" class="action-btn edit-btn" onclick="editClient(6)">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="#" class="action-btn archive-btn" onclick="archiveClient(6)">
                                            <i class="fas fa-archive"></i> Archive
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    <div class="pagination-container">
                        <div class="pagination-info">
                            Showing 6 Records of 847 entries
                        </div>
                        <div class="pagination">
                            <a href="#" class="page-btn">Previous</a>
                            <a href="#" class="page-btn active">1</a>
                            <a href="#" class="page-btn">2</a>
                            <a href="#" class="page-btn">3</a>
                            <a href="#" class="page-btn">Next</a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('menuToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('mobile-open');
        });

        // Enhanced filter clients function
        function filterClients() {
            const fromDate = document.getElementById('fromDate').value;
            const toDate = document.getElementById('toDate').value;
            const clientSearch = document.getElementById('clientSearch').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const salesPersonFilter = document.getElementById('salesPersonFilter').value;

            const tableBody = document.getElementById('clientsTableBody');
            const rows = tableBody.querySelectorAll('tr');

            let visibleCount = 0;
            const totalCount = rows.length;

            rows.forEach(row => {
                const clientName = row.querySelector('.client-details h4').textContent.toLowerCase();
                const clientEmail = row.querySelector('.client-details p').textContent.toLowerCase();
                const status = row.querySelector('.status-badge').textContent.toLowerCase();
                const salesPerson = row.cells[3].textContent.toLowerCase();
                const dateCell = row.cells[5].textContent; // Date column

                let showRow = true;

                // Filter by search term (name or email)
                if (clientSearch && !clientName.includes(clientSearch) && !clientEmail.includes(clientSearch)) {
                    showRow = false;
                }

                // Filter by status
                if (statusFilter && !status.includes(statusFilter)) {
                    showRow = false;
                }

                // Filter by sales person
                if (salesPersonFilter && !salesPerson.includes(salesPersonFilter)) {
                    showRow = false;
                }

                // Filter by date range
                if (fromDate || toDate) {
                    const rowDate = parseDate(dateCell);
                    if (fromDate && rowDate < new Date(fromDate)) {
                        showRow = false;
                    }
                    if (toDate && rowDate > new Date(toDate)) {
                        showRow = false;
                    }
                }

                row.style.display = showRow ? '' : 'none';
                if (showRow) visibleCount++;
            });

            // Update pagination info
            updatePaginationInfo(visibleCount, totalCount);
            
            // Show/hide no results message
            showNoResultsMessage(visibleCount);
        }

        // Parse date string to Date object
        function parseDate(dateString) {
            // Handle different date formats
            const date = new Date(dateString);
            if (isNaN(date.getTime())) {
                // Try parsing different formats
                const parts = dateString.split(' ');
                if (parts.length >= 3) {
                    const day = parseInt(parts[0]);
                    const month = getMonthNumber(parts[1]);
                    const year = parseInt(parts[2]);
                    return new Date(year, month, day);
                }
            }
            return date;
        }

        // Get month number from month name
        function getMonthNumber(monthName) {
            const months = {
                'jan': 0, 'feb': 1, 'mar': 2, 'apr': 3, 'may': 4, 'jun': 5,
                'jul': 6, 'aug': 7, 'sep': 8, 'oct': 9, 'nov': 10, 'dec': 11
            };
            return months[monthName.toLowerCase()] || 0;
        }

        // Update pagination information
        function updatePaginationInfo(visibleCount, totalCount) {
            const paginationInfo = document.querySelector('.pagination-info');
            if (paginationInfo) {
                paginationInfo.textContent = `Showing ${visibleCount} Records of ${totalCount} entries`;
            }
        }

        // Show/hide no results message
        function showNoResultsMessage(visibleCount) {
            let noResultsMsg = document.getElementById('noResultsMessage');
            
            if (visibleCount === 0) {
                if (!noResultsMsg) {
                    noResultsMsg = document.createElement('tr');
                    noResultsMsg.id = 'noResultsMessage';
                    noResultsMsg.innerHTML = `
                        <td colspan="7" style="text-align: center; padding: 40px; color: #666;">
                            <i class="fas fa-search" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                            <p>No clients found matching your filters</p>
                            <button onclick="clearAllFilters()" style="margin-top: 10px; padding: 8px 16px; background: #4e73df; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                Clear All Filters
                            </button>
                        </td>
                    `;
                    document.getElementById('clientsTableBody').appendChild(noResultsMsg);
                }
            } else if (noResultsMsg) {
                noResultsMsg.remove();
            }
        }

        // Clear all filters
        function clearAllFilters() {
            document.getElementById('fromDate').value = '';
            document.getElementById('toDate').value = '';
            document.getElementById('clientSearch').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('salesPersonFilter').value = '';
            
            // Reset to default dates
            const today = new Date().toISOString().split('T')[0];
            const thirtyDaysAgo = new Date();
            thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
            
            document.getElementById('toDate').value = today;
            document.getElementById('fromDate').value = thirtyDaysAgo.toISOString().split('T')[0];
            
            // Show all rows
            filterClients();
        }

        // Add new client function
        function addNewClient() {
            alert('Add New Client functionality would open a form modal or redirect to a new page');
            // In a real application, this would open a modal or redirect to a form page
        }

        // Enhanced real-time search with debouncing
        let searchTimeout;
        document.getElementById('clientSearch').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filterClients();
            }, 300); // Wait 300ms after user stops typing
        });

        // Add change event listeners to all filter inputs
        document.getElementById('fromDate').addEventListener('change', filterClients);
        document.getElementById('toDate').addEventListener('change', filterClients);
        document.getElementById('statusFilter').addEventListener('change', filterClients);
        document.getElementById('salesPersonFilter').addEventListener('change', filterClients);

        // Edit client function
        function editClient(clientId) {
            alert(`Edit client functionality for ID: ${clientId}`);
            // In a real application, this would open an edit form
        }

        // Archive client function
        function archiveClient(clientId) {
            if (confirm('Are you sure you want to archive this client?')) {
                alert(`Client ${clientId} archived successfully`);
                // In a real application, this would make an API call to archive the client
                // After successful archive, refresh the table
                // filterClients();
            }
        }

        // Add click handlers to navigation
        document.querySelector('.logo').addEventListener('click', function() {
            window.location.href = 'dashboard.html';
        });

        // Enhanced pagination functionality
        function goToPage(pageNumber) {
            // In a real application, this would fetch data for the specific page
            console.log(`Navigating to page ${pageNumber}`);
            
            // Update active page button
            document.querySelectorAll('.page-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
        }

        // Add click handlers to pagination buttons
        document.addEventListener('DOMContentLoaded', function() {
            const pageBtns = document.querySelectorAll('.page-btn');
            pageBtns.forEach(btn => {
                if (btn.textContent !== 'Previous' && btn.textContent !== 'Next') {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        goToPage(this.textContent);
                    });
                }
            });
        });

        // Set current date as default for date filters
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('toDate').value = today;
            
            // Set from date to 30 days ago
            const thirtyDaysAgo = new Date();
            thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
            document.getElementById('fromDate').value = thirtyDaysAgo.toISOString().split('T')[0];
            
            // Initial filter to show current data
            filterClients();
        });

        // Export filtered data function
        function exportFilteredData() {
            const visibleRows = Array.from(document.querySelectorAll('#clientsTableBody tr')).filter(row => 
                row.style.display !== 'none' && !row.id
            );
            
            if (visibleRows.length === 0) {
                alert('No data to export');
                return;
            }

            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "Client Name,Email,Phone,Address,Sales Person,Status,Date\n";
            
            visibleRows.forEach(row => {
                const name = row.querySelector('.client-details h4').textContent;
                const email = row.querySelector('.client-details p').textContent;
                const phone = row.cells[1].querySelector('p:last-child').textContent.replace('📞 ', '');
                const address = row.cells[2].querySelector('p:first-child').textContent;
                const salesPerson = row.cells[3].textContent;
                const status = row.querySelector('.status-badge').textContent;
                const date = row.cells[5].textContent;
                
                csvContent += `"${name}","${email}","${phone}","${address}","${salesPerson}","${status}","${date}"\n`;
            });
            
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "filtered_clients.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Add export button to filters section
        document.addEventListener('DOMContentLoaded', function() {
            const filtersSection = document.querySelector('.filters-section');
            const exportBtn = document.createElement('button');
            exportBtn.className = 'search-btn';
            exportBtn.style.background = '#6c757d';
            exportBtn.innerHTML = '<i class="fas fa-download"></i> Export CSV';
            exportBtn.onclick = exportFilteredData;
            
            // Insert export button after search button
            const searchBtn = document.querySelector('.search-btn');
            searchBtn.parentNode.insertBefore(exportBtn, searchBtn.nextSibling);
        });
    </script>
</body>
</html>