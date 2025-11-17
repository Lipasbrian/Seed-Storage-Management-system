<?php
// users.php - User Management (Admin Only)
require_once 'config.php';
requireLogin();
if (!hasRole('admin')) {
    header('Location: dashboard.php');
    exit();
}
$db = Database::getInstance()->getConnection();
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $full_name = sanitize($_POST['full_name'] ?? '');
    $role = sanitize($_POST['role'] ?? 'viewer');
    $email = sanitize($_POST['email'] ?? '');
    if (!$username || !$password || !$full_name || !$role) {
        $error = 'All fields except email are required.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare('INSERT INTO users (username, password, full_name, role, email, status) VALUES (?, ?, ?, ?, ?, ?)');
        try {
            $stmt->execute([$username, $hash, $full_name, $role, $email, 'active']);
            logAudit($_SESSION['user_id'], 'add_user', 'users', $db->lastInsertId('users_id_seq'), "Added user");
            $success = 'User added successfully!';
        } catch (PDOException $e) {
            $error = 'Error: ' . $e->getMessage();
        }
    }
}
$stmt = $db->query('SELECT * FROM users ORDER BY created_at DESC');
$users = $stmt->fetchAll();
include 'includes/header.php';
?>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Users Management</h1>
        </div>
        <div class="card mb-4">
            <div class="card-header bg-ks-primary text-white">
                <h5 class="mb-0">Add User</h5>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"> <?php echo $error; ?> </div>
                <?php elseif ($success): ?>
                    <div class="alert alert-success"> <?php echo $success; ?> </div>
                <?php endif; ?>
                <form method="post" autocomplete="off">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" name="full_name" id="full_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="admin">Admin</option>
                                <option value="data_entry">Data Entry</option>
                                <option value="viewer">Viewer</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add User</button>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5>Users List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Full Name</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Last Login</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['role']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo $user['status'] == 'active' ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>'; ?></td>
                                <td><?php echo formatDate($user['created_at']); ?></td>
                                <td><?php echo $user['last_login'] ? formatDate($user['last_login']) : '-'; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
<?php include 'includes/footer.php'; ?>
