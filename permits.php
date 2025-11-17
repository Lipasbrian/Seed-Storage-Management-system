<?php
// permits.php - Permits Management
require_once 'config.php';
requireLogin();
$db = Database::getInstance()->getConnection();
// Search/filter logic
$where = [];
$params = [];
if (!empty($_GET['permit_number'])) {
    $where[] = 'permit_number ILIKE ?';
    $params[] = '%' . sanitize($_GET['permit_number']) . '%';
}
if (!empty($_GET['farmer'])) {
    $where[] = 'f.farmer_name ILIKE ?';
    $params[] = '%' . sanitize($_GET['farmer']) . '%';
}
if (!empty($_GET['variety'])) {
    $where[] = 'sv.variety_name ILIKE ?';
    $params[] = '%' . sanitize($_GET['variety']) . '%';
}
$sql = "SELECT p.*, f.farmer_name, sv.variety_name FROM permits p JOIN farmers f ON p.farmer_id = f.id JOIN seed_varieties sv ON p.variety_id = sv.id";
if ($where) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY p.issue_date DESC";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$permits = $stmt->fetchAll();
include 'includes/header.php';
?>
<div class="row">
    <?php include 'includes/sidebar.php'; ?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Permits Management</h1>
            <form class="row g-2" method="get">
                <div class="col-auto">
                    <input type="text" name="permit_number" class="form-control" placeholder="Permit Number" value="<?php echo htmlspecialchars($_GET['permit_number'] ?? ''); ?>">
                </div>
                <div class="col-auto">
                    <input type="text" name="farmer" class="form-control" placeholder="Farmer" value="<?php echo htmlspecialchars($_GET['farmer'] ?? ''); ?>">
                </div>
                <div class="col-auto">
                    <input type="text" name="variety" class="form-control" placeholder="Variety" value="<?php echo htmlspecialchars($_GET['variety'] ?? ''); ?>">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
        <div class="card">
            <div class="card-header">
                <h5>Permits List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Permit Number</th>
                                <th>Farmer</th>
                                <th>Variety</th>
                                <th>Total Bags</th>
                                <th>Total Weight (kg)</th>
                                <th>Issue Date</th>
                                <th>Expiry Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($permits as $permit): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($permit['permit_number']); ?></td>
                                <td><?php echo htmlspecialchars($permit['farmer_name']); ?></td>
                                <td><?php echo htmlspecialchars($permit['variety_name']); ?></td>
                                <td><?php echo $permit['total_bags']; ?></td>
                                <td><?php echo number_format($permit['total_kg'], 2); ?></td>
                                <td><?php echo formatDate($permit['issue_date']); ?></td>
                                <td><?php echo $permit['expiry_date'] ? formatDate($permit['expiry_date']) : '-'; ?></td>
                                <td>
                                    <?php
                                        if($permit['status'] == 'active') echo '<span class="badge bg-success">Active</span>';
                                        elseif($permit['status'] == 'completed') echo '<span class="badge bg-info">Completed</span>';
                                        else echo '<span class="badge bg-danger">Expired</span>';
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include 'includes/footer.php'; ?>
