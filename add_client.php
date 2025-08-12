<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'config/database.php';

$success_message = '';
$error_message = '';

// Load sales persons from users table
try {
    $salesStmt = $pdo->prepare("SELECT id, CONCAT(first_name, ' ', last_name) AS full_name, email FROM users ORDER BY first_name, last_name");
    $salesStmt->execute();
    $salesPersons = $salesStmt->fetchAll();
} catch (PDOException $e) {
    $salesPersons = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $client_name = trim($_POST['client_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $sales_person = trim($_POST['sales_person'] ?? '');
        $status = $_POST['status'] ?? 'ACTIVE';

        if ($client_name === '' || $email === '' || $phone === '') {
            throw new Exception('Client name, email and phone are required.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format.');
        }

        $sql = "INSERT INTO clients (client_name, email, phone, address, sales_person, status, date) VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$client_name, $email, $phone, $address, $sales_person, $status]);
        $success_message = 'Client added successfully!';
        $_POST = [];
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    } catch (PDOException $e) {
        $error_message = 'Database error: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Client - Tripenure CRM</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background:#f5f7fa; margin:0; }
        .container { max-width: 900px; margin: 40px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.08); padding: 24px; }
        h1 { margin: 0 0 20px; color:#ff7b00; }
        .row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
        .row-1 { grid-template-columns: 1fr; }
        label { display:block; font-size: 13px; color:#555; margin-bottom:6px; }
        input, select, textarea { width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:6px; font-size:14px; }
        textarea { resize: vertical; min-height: 80px; }
        .actions { display:flex; gap:12px; margin-top: 20px; }
        .btn { border:none; padding:10px 16px; border-radius:6px; cursor:pointer; font-weight:600; }
        .btn-primary { background:#4e73df; color:#fff; }
        .btn-secondary { background:#6c757d; color:#fff; }
        .alert { padding:10px 12px; border-radius:6px; margin-bottom: 16px; }
        .alert-success { background:#d1eddf; color:#1c7c52; }
        .alert-error { background:#f8d7da; color:#842029; }
        a { text-decoration:none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add Client</h1>
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form method="POST" action="add_client.php">
            <div class="row">
                <div>
                    <label>Client Name</label>
                    <input type="text" name="client_name" value="<?php echo htmlspecialchars($_POST['client_name'] ?? ''); ?>" required />
                </div>
                <div>
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required />
                </div>
                <div>
                    <label>Phone</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" required />
                </div>
                <div>
                    <label>Sales Person</label>
                    <select name="sales_person">
                        <option value="">Not Assigned</option>
                        <?php foreach ($salesPersons as $sp): ?>
                            <?php $name = trim(($sp['full_name'] ?? '') ?: ($sp['email'] ?? '')); ?>
                            <option value="<?php echo htmlspecialchars($name); ?>" <?php echo (($_POST['sales_person'] ?? '') === $name) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row-1">
                    <label>Address</label>
                    <textarea name="address"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                </div>
                <div>
                    <label>Status</label>
                    <select name="status">
                        <option value="ACTIVE" <?php echo (($_POST['status'] ?? '') === 'ACTIVE') ? 'selected' : ''; ?>>Active</option>
                        <option value="INACTIVE" <?php echo (($_POST['status'] ?? '') === 'INACTIVE') ? 'selected' : ''; ?>>Inactive</option>
                        <option value="ARCHIVED" <?php echo (($_POST['status'] ?? '') === 'ARCHIVED') ? 'selected' : ''; ?>>Archived</option>
                    </select>
                </div>
            </div>
            <div class="actions">
                <button type="submit" class="btn btn-primary">Save Client</button>
                <a href="client.php" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</body>
</html>