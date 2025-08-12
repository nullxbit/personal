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

        /* Leads Filter Section */
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .filter-row {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }

        .filter-input {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            min-width: 120px;
        }

        .filter-select {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            min-width: 120px;
            background: white;
            cursor: pointer;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #ff6b6b;
            color: white;
        }

        .btn-primary:hover {
            background: #ff5252;
        }

        .btn-success {
            background: #51cf66;
            color: white;
        }

        .btn-success:hover {
            background: #40c057;
        }

        .btn-menu {
            background: #868e96;
            color: white;
            padding: 10px 15px;
        }

        /* Status Cards */
        .status-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .status-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .status-card:hover {
            transform: translateY(-2px);
        }

        .status-card.new-leads { background: #ffd43b; }
        .status-card.active-leads { background: #339af0; }
        .status-card.no-response { background: #7048e8; }
        .status-card.hot-lead { background: #20c997; }
        .status-card.confirm { background: #51cf66; }
        .status-card.cancel { background: #ff6b6b; }
        .status-card.lost { background: #495057; }

        .status-number {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .status-label {
            font-size: 12px;
            text-transform: uppercase;
        }

        /* Leads Table */
        .leads-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: #6c7b7f;
            color: white;
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
        }

        .table td {
            padding: 15px 12px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
            vertical-align: top;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        .lead-id {
            font-weight: bold;
            color: #495057;
        }

        .lead-status {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: white;
        }

        .status-new { background: #ffd43b; color: #856404; }
        .status-hot { background: #ff6b6b; }

        .query-info {
            line-height: 1.4;
        }

        .query-title {
            font-weight: 600;
            color: #4c6ef5;
            margin-bottom: 5px;
        }

        .query-details {
            font-size: 12px;
            color: #666;
            margin-bottom: 3px;
        }

        .client-info {
            line-height: 1.4;
        }

        .client-name {
            font-weight: 600;
            margin-bottom: 3px;
        }

        .client-contact {
            font-size: 12px;
            color: #666;
            margin-bottom: 2px;
        }

        .priority-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            font-size: 16px;
        }

        .priority-normal {
            background: #e9ecef;
            color: #666;
        }

        .priority-hot {
            background: #ff6b6b;
            color: white;
        }

        .assign-tag {
            background: #4c6ef5;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            flex-direction: column;
        }

        .btn-edit {
            background: #4c6ef5;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            font-size: 10px;
            cursor: pointer;
            text-transform: uppercase;
            font-weight: bold;
        }

        .btn-archive {
            background: #ff6b6b;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            font-size: 10px;
            cursor: pointer;
            text-transform: uppercase;
            font-weight: bold;
        }

        .red-dot {
            color: #ff4757;
            font-weight: bold;
            margin-right: 5px;
        }

        .flag-icon {
            color: #ffd43b;
            margin-left: 5px;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
            }
            
            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }
            
            .status-cards {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .table-container {
                overflow-x: auto;
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
            <a href="leads.html" class="nav-item active">
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
            <a href="client.html" class="nav-item">
                <span>👥</span>
                <span>Clients</span>
            </a>
            <a href="#" class="nav-item">
                <span>⚙️</span>
                <span>Settings</span>
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

            <!-- Filter Section -->
            <div class="filter-section">
                <div class="filter-row">
                    <button class="btn btn-primary">⊕ Add Query</button>
                    <input type="date" id="filterStartDate" class="filter-input" placeholder="Start Date">
                    <input type="date" id="filterEndDate" class="filter-input" placeholder="End Date">
                    <input type="text" id="filterLeadId" class="filter-input" placeholder="LeadId">
                    <input type="text" id="filterSubjectName" class="filter-input" placeholder="Subject, Name">
                    <select id="filterStatus" class="filter-select">
                        <option value="">Status</option>
                        <option>New Leads</option>
                        <option>Hot Lead</option>
                        <option>Active Leads</option>
                        <option>Confirm</option>
                        <option>Cancel</option>
                        <option>Lost</option>
                        <option>No Response</option>
                    </select>
                    <select id="filterOps" class="filter-select">
                        <option value="">Select OPS</option>
                    </select>
                    <select id="filterDestination" class="filter-select">
                        <option value="">Select Destination</option>
                    </select>
                    <select id="filterArchived" class="filter-select">
                        <option>Un-Archived</option>
                        <option>Archived</option>
                    </select>
                </div>
                <div class="filter-row">
                    <button class="btn btn-success">🔍 Search</button>
                </div>
            </div>

            <!-- Status Cards -->
            <div class="status-cards">
                <div class="status-card new-leads" onclick="filterByStatus('new', event)">
                    <div class="status-number">48</div>
                    <div class="status-label">New Leads</div>
                </div>
                <div class="status-card active-leads" onclick="filterByStatus('active', event)">
                    <div class="status-number">91</div>
                    <div class="status-label">Active Leads</div>
                </div>
                <div class="status-card no-response" onclick="filterByStatus('no-response', event)">
                    <div class="status-number">103</div>
                    <div class="status-label">No Response</div>
                </div>
                <div class="status-card hot-lead" onclick="filterByStatus('hot', event)">
                    <div class="status-number">52</div>
                    <div class="status-label">Hot Lead</div>
                </div>
                <div class="status-card confirm" onclick="filterByStatus('confirm', event)">
                    <div class="status-number">23</div>
                    <div class="status-label">Confirm</div>
                </div>
                <div class="status-card cancel" onclick="filterByStatus('cancel', event)">
                    <div class="status-number">426</div>
                    <div class="status-label">Cancel</div>
                </div>
                <div class="status-card lost" onclick="filterByStatus('lost', event)">
                    <div class="status-number">2</div>
                    <div class="status-label">Lost</div>
                </div>
            </div>

            <!-- Leads Table -->
            <div class="leads-table">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Query Information</th>
                                <th>Client</th>
                                <th>Priority</th>
                                <th>Assign To</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <span class="red-dot">●</span>
                                    <div class="lead-id">#F001083</div>
                                    <span class="lead-status status-new">New Leads</span>
                                </td>
                                <td>
                                    <div class="query-info">
                                        <div class="query-title">Ladakh Tour Packages</div>
                                        <div class="query-details">📅 Start Date: 12 Jun 2023 • 📅 End Date: 17 Jun 2023 | ⏱ Duration: 6 Days</div>
                                        <div class="query-details">📍 India To Ladakh | 📦 Complete Package | 👥 Lead Source: Facebook</div>
                                        <div class="query-details">📅 Date: 08 Jun 2023 | ⏰ Time: 10:34 am | ➕ Added By: Adi Bhaiya</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="client-info">
                                        <div class="client-name">👤 Preethi Chola</div>
                                        <div class="client-contact">📧 Email: preethi.chola1788@gmail.com</div>
                                        <div class="client-contact">📱 Mobile No.: 7639664577</div>
                                        <div class="client-contact">👥 Adults: 1 | 👶 Childs: 0 | 🍼 Infants: 0</div>
                                        <div class="client-contact">🔄 Last Updated:</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="priority-icon priority-normal">⚪</div>
                                    <div style="text-align: center; margin-top: 5px; font-size: 12px;">Normal</div>
                                </td>
                                <td>
                                    <span class="assign-tag">Adi Bhaiya</span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-edit">✏️ Edit</button>
                                        <button class="btn-archive">📁 Archive</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="red-dot">●</span>
                                    <div class="lead-id">#F001082</div>
                                    <span class="lead-status status-hot">Hot Lead</span>
                                </td>
                                <td>
                                    <div class="query-info">
                                        <div class="query-title">KASHMIR PACKAGE 4N/5D</div>
                                        <div class="query-details">📅 Start Date: 20 Jun 2023 • 📅 End Date: 24 Jun 2023 | ⏱ Duration: 5 Days</div>
                                        <div class="query-details">📍 India To Kashmir | 📦 Complete Package | 👥 Lead Source: Facebook</div>
                                        <div class="query-details">📅 Date: 07 Jun 2023 | ⏰ Time: 17:13 pm | ➕ Added By: Adi Bhaiya</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="client-info">
                                        <div class="client-name">👤 Alkaparashar</div>
                                        <div class="client-contact">📧 Email: Alkaparashar@gmail.com</div>
                                        <div class="client-contact">📱 Mobile No.: 89297 07401</div>
                                        <div class="client-contact">👥 Adults: 2 | 👶 Childs: 0 | 🍼 Infants: 0</div>
                                        <div class="client-contact">🔄 Last Updated: 07 Jun 2023 17:14 pm</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="priority-icon priority-hot">🔥</div>
                                    <div style="text-align: center; margin-top: 5px; font-size: 12px;">Hot</div>
                                </td>
                                <td>
                                    <span class="assign-tag">Adi Bhaiya</span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-edit">✏️ Edit</button>
                                        <button class="btn-archive">📁 Archive</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="red-dot">●</span>
                                    <span class="flag-icon">🚩</span>
                                    <div class="lead-id">#F001081</div>
                                    <span class="lead-status status-new">Normal Lead</span>
                                </td>
                                <td>
                                    <div class="query-info">
                                        <div class="query-title">Bali Tour Packages</div>
                                        <div class="query-details">📅 Start Date: 05 Dec 2023 • 📅 End Date: 10 Dec 2023 | ⏱ Duration: 6 Days</div>
                                        <div class="query-details">📍 Mumbai To Bali | 📦 Complete Package | 👥 Lead Source: Facebook</div>
                                        <div class="query-details">📅 Date: 07 Jun 2023 | ⏰ Time: 14:28 pm | ➕ Added By: Adi Bhaiya</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="client-info">
                                        <div class="client-name">👤 Prashant Kerkar</div>
                                        <div class="client-contact">📧 Email: kerkarprashant04@gmail.com</div>
                                        <div class="client-contact">📱 Mobile No.: 9699632971</div>
                                        <div class="client-contact">👥 Adults: 2 | 👶 Childs: 0 | 🍼 Infants: 0</div>
                                        <div class="client-contact">🔄 Last Updated: 08 Jun 2023 11:00 am</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="priority-icon priority-normal">⚪</div>
                                    <div style="text-align: center; margin-top: 5px; font-size: 12px;">Normal</div>
                                </td>
                                <td>
                                    <span class="assign-tag">Adi Bhaiya</span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-edit">✏️ Edit</button>
                                        <button class="btn-archive">📁 Archive</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="red-dot">●</span>
                                    <span class="flag-icon">🚩</span>
                                    <div class="lead-id">#F001080</div>
                                    <span class="lead-status status-new">Normal Lead</span>
                                </td>
                                <td>
                                    <div class="query-info">
                                        <div class="query-title">Bali Tour Packages</div>
                                        <div class="query-details">📅 Start Date: 20 Sep 2023 • 📅 End Date: 24 Sep 2023 | ⏱ Duration: 5 Days</div>
                                        <div class="query-details">📍 Hyderabad To Bali | 📦 Complete Package | 👥 Lead Source: Facebook</div>
                                        <div class="query-details">📅 Date: 07 Jun 2023 | ⏰ Time: 14:27 pm | ➕ Added By: Adi Bhaiya</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="client-info">
                                        <div class="client-name">👤 Venkata Madhu</div>
                                        <div class="client-contact">📧 Email: g.venkatamadhu131@gmail.com</div>
                                        <div class="client-contact">📱 Mobile No.: 9010687049</div>
                                        <div class="client-contact">👥 Adults: 2 | 👶 Childs: 0 | 🍼 Infants: 0</div>
                                        <div class="client-contact">🔄 Last Updated:</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="priority-icon priority-normal">⚪</div>
                                    <div style="text-align: center; margin-top: 5px; font-size: 12px;">Normal</div>
                                </td>
                                <td>
                                    <span class="assign-tag">Adi Bhaiya</span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-edit">✏️ Edit</button>
                                        <button class="btn-archive">📁 Archive</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
const statusMap = {
  new: 'New Leads',
  active: 'Active Leads',
  'no-response': 'No Response',
  hot: 'Hot Lead',
  confirm: 'Confirm',
  cancel: 'Cancel',
  lost: 'Lost'
};

function parseDateLike(text) {
  // Accepts "12 Jun 2023" or "2023-06-12"
  if (!text) return null;
  const d = new Date(text);
  return isNaN(d.getTime()) ? null : d;
}

function getRowData(tr) {
  const leadIdRaw = (tr.querySelector('.lead-id')?.textContent || '').trim(); // e.g. #F001083
  const leadId = leadIdRaw.replace('#', '');
  const status = (tr.querySelector('.lead-status')?.textContent || '').trim();
  const title = (tr.querySelector('.query-title')?.textContent || '').trim();
  const clientName = (tr.querySelector('.client-name')?.textContent || '').trim();
  const assignTo = (tr.querySelector('.assign-tag')?.textContent || '').trim();

  const details = Array.from(tr.querySelectorAll('.query-details')).map(el => el.textContent.trim());
  const datesText = details[0] || '';
  const destText = details[1] || '';

  let destination = '';
  const toIdx = destText.indexOf(' To ');
  if (toIdx !== -1) {
    destination = destText.slice(toIdx + 4).split('|')[0].trim();
  }

  let startDate = null, endDate = null;
  const startMatch = datesText.match(/Start Date:\s*([0-9]{1,2}\s+\w{3}\s+[0-9]{4})/i);
  const endMatch = datesText.match(/End Date:\s*([0-9]{1,2}\s+\w{3}\s+[0-9]{4})/i);
  if (startMatch) startDate = parseDateLike(startMatch[1]);
  if (endMatch) endDate = parseDateLike(endMatch[1]);

  const archived = tr.dataset.archived === 'true';

  return {
    el: tr,
    id: leadId,
    idRaw: leadIdRaw,
    status,
    title,
    clientName,
    assignTo,
    destination,
    startDate,
    endDate,
    archived
  };
}

function populateDynamicFilters() {
  const rows = Array.from(document.querySelectorAll('.table tbody tr'));
  const opsSet = new Set();
  const destSet = new Set();

  rows.forEach(tr => {
    const d = getRowData(tr);
    if (d.assignTo) opsSet.add(d.assignTo);
    if (d.destination) destSet.add(d.destination);
  });

  const opsSelect = document.getElementById('filterOps');
  const destSelect = document.getElementById('filterDestination');

  if (opsSelect) {
    const current = opsSelect.value;
    opsSelect.innerHTML = '<option value="">Select OPS</option>' +
      Array.from(opsSet).sort().map(v => `<option>${v}</option>`).join('');
    if (Array.from(opsSet).includes(current)) opsSelect.value = current;
  }

  if (destSelect) {
    const current = destSelect.value;
    destSelect.innerHTML = '<option value="">Select Destination</option>' +
      Array.from(destSet).sort().map(v => `<option>${v}</option>`).join('');
    if (Array.from(destSet).includes(current)) destSelect.value = current;
  }
}

function applyFilters() {
  const startVal = document.getElementById('filterStartDate')?.value || '';
  const endVal = document.getElementById('filterEndDate')?.value || '';
  const leadIdVal = (document.getElementById('filterLeadId')?.value || '').trim().toLowerCase();
  const subjectNameVal = (document.getElementById('filterSubjectName')?.value || '').trim().toLowerCase();
  const statusVal = (document.getElementById('filterStatus')?.value || '').trim().toLowerCase();
  const opsVal = (document.getElementById('filterOps')?.value || '').trim().toLowerCase();
  const destVal = (document.getElementById('filterDestination')?.value || '').trim().toLowerCase();
  const archivedVal = (document.getElementById('filterArchived')?.value || 'Un-Archived').trim();
  const searchBarVal = (document.querySelector('.search-bar input')?.value || '').trim().toLowerCase();

  const startFilter = startVal ? parseDateLike(startVal) : null;
  const endFilter = endVal ? parseDateLike(endVal) : null;
  const wantArchived = archivedVal === 'Archived';

  const rows = Array.from(document.querySelectorAll('.table tbody tr'));
  rows.forEach(tr => {
    const d = getRowData(tr);
    let show = true;

    if (startFilter && d.startDate) show = show && d.startDate >= startFilter;
    if (endFilter && d.endDate) show = show && d.endDate <= endFilter;

    if (leadIdVal) {
      const idStr = (d.idRaw || '').toLowerCase();
      show = show && idStr.includes(leadIdVal);
    }

    if (subjectNameVal) {
      const hay = `${d.title} ${d.clientName}`.toLowerCase();
      show = show && hay.includes(subjectNameVal);
    }

    if (statusVal) {
      const rowStatus = (d.status || '').toLowerCase();
      show = show && rowStatus.includes(statusVal);
    }

    if (opsVal) {
      const rowOps = (d.assignTo || '').toLowerCase();
      show = show && rowOps === opsVal;
    }

    if (destVal) {
      const rowDest = (d.destination || '').toLowerCase();
      show = show && rowDest === destVal;
    }

    if (wantArchived) {
      show = show && d.archived === true;
    } else {
      show = show && d.archived !== true;
    }

    if (searchBarVal) {
      const hay = [
        d.idRaw, d.title, d.clientName, d.assignTo, d.destination, d.status
      ].join(' ').toLowerCase();
      show = show && hay.includes(searchBarVal);
    }

    tr.style.display = show ? '' : 'none';
  });
}

// Expose globally for onclick handlers
function filterByStatus(status, ev) {
  const label = statusMap[status] || '';
  const statusSelect = document.getElementById('filterStatus');
  if (statusSelect) statusSelect.value = label;

  // Dim all, highlight clicked
  document.querySelectorAll('.status-card').forEach(card => { card.style.opacity = '0.6'; });
  if (ev && ev.target) {
    const card = ev.target.closest('.status-card');
    if (card) card.style.opacity = '1';
  }
  applyFilters();
}

// Navigation functionality
document.querySelector('.logo').addEventListener('click', function() {
    window.location.href = 'dashboard.html';
});

// Search button → apply filters
document.querySelector('.btn-success')?.addEventListener('click', function() {
  applyFilters();
});

// Also live-apply on changes
['filterStartDate','filterEndDate','filterLeadId','filterSubjectName','filterStatus','filterOps','filterDestination','filterArchived'].forEach(id => {
  const el = document.getElementById(id);
  if (el) el.addEventListener('change', applyFilters);
});

// Add Query button
document.querySelector('.btn-primary')?.addEventListener('click', function() {
  alert('Add new query form would open here');
});

// Edit buttons
document.querySelectorAll('.btn-edit').forEach(btn => {
  btn.addEventListener('click', function(e) {
    e.stopPropagation();
    const leadId = this.closest('tr').querySelector('.lead-id').textContent;
    alert(`Edit lead ${leadId}`);
  });
});

// Archive buttons: mark archived and re-filter
document.querySelectorAll('.btn-archive').forEach(btn => {
  btn.addEventListener('click', function(e) {
    e.stopPropagation();
    const row = this.closest('tr');
    const leadId = row.querySelector('.lead-id').textContent;
    if (confirm(`Archive lead ${leadId}?`)) {
      row.style.opacity = '0.5';
      row.dataset.archived = 'true';
      applyFilters();
      alert(`Lead ${leadId} archived`);
    }
  });
});

// Notification clicks
document.querySelectorAll('.notification').forEach(notification => {
  notification.addEventListener('click', function() {
    this.classList.remove('has-alert');
  });
});

// User profile dropdown
document.querySelector('.user-profile')?.addEventListener('click', function() {
  alert('User profile menu');
});

// Table row click
document.querySelectorAll('.table tbody tr').forEach(row => {
  row.addEventListener('click', function() {
    const leadId = this.querySelector('.lead-id').textContent;
    console.log('Clicked lead:', leadId);
  });
});

// Search bar live filter
document.querySelector('.search-bar input')?.addEventListener('input', function() {
  applyFilters();
});

// Initialize dynamic selects and first filter pass
populateDynamicFilters();
applyFilters();
</script>