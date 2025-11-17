<?php
// deliveries.php - All Deliveries Listing
require_once 'config.php';
requireLogin();
$db = Database::getInstance()->getConnection();
// Search/filter logic
$where = [];
$params = [];
if (!empty($_GET['farmer'])) {
    $where[] = 'f.farmer_name ILIKE ?';
    $params[] = '%' . sanitize($_GET['farmer']) . '%';
}
if (!empty($_GET['variety'])) {
    $where[] = 'sv.variety_name ILIKE ?';
    $params[] = '%' . sanitize($_GET['variety']) . '%';
}
if (!empty($_GET['date_from'])) {
    $where[] = 'd.delivery_datetime >= ?';
    $params[] = $_GET['date_from'];
}
if (!empty($_GET['date_to'])) {
    $where[] = 'd.delivery_datetime <= ?';
    $params[] = $_GET['date_to'];
}
$sql = "SELECT d.*, f.farmer_name, sv.variety_name, p.permit_number, b.bin_number, u.full_name as received_by
        FROM deliveries d
        JOIN farmers f ON d.farmer_id = f.id
        JOIN seed_varieties sv ON d.variety_id = sv.id
        JOIN permits p ON d.permit_id = p.id
        JOIN bins b ON d.bin_id = b.id
        JOIN users u ON d.received_by = u.id";
if ($where) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY d.delivery_datetime DESC LIMIT 100";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$deliveries = $stmt->fetchAll();
include 'includes/header.php';
?>
<div class="row">
    <?php include 'includes/sidebar.php'; ?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">All Deliveries</h1>
            <form class="row g-2" method="get">
                <div class="col-auto">
                    <input type="text" name="farmer" class="form-control" placeholder="Farmer" value="<?php echo htmlspecialchars($_GET['farmer'] ?? ''); ?>">
                </div>
                <div class="col-auto">
                    <input type="text" name="variety" class="form-control" placeholder="Variety" value="<?php echo htmlspecialchars($_GET['variety'] ?? ''); ?>">
                </div>
                <div class="col-auto">
                    <input type="date" name="date_from" class="form-control" value="<?php echo htmlspecialchars($_GET['date_from'] ?? ''); ?>">
                </div>
                <div class="col-auto">
                    <input type="date" name="date_to" class="form-control" value="<?php echo htmlspecialchars($_GET['date_to'] ?? ''); ?>">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
        <div class="card">
            <div class="card-header">
                <h5>Deliveries List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Date/Time</th>
                                <th>Farmer</th>
                                <th>Variety</th>
                                <th>Permit</th>
                                <th>Bags</th>
                                <th>Weight (kg)</th>
                                <th>Moisture</th>
                                <th>Bin</th>
                                <th>Received By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($deliveries as $delivery): ?>
                            <tr>
                                <td><?php echo formatDate($delivery['delivery_datetime']); ?></td>
                                <td><?php echo htmlspecialchars($delivery['farmer_name']); ?></td>
                                <td><?php echo htmlspecialchars($delivery['variety_name']); ?></td>
                                <td><?php echo htmlspecialchars($delivery['permit_number']); ?></td>
                                <td><?php echo $delivery['bags_delivered']; ?></td>
                                <td><?php echo number_format($delivery['kg_delivered'], 2); ?></td>
                                <td><?php echo $delivery['moisture_content']; ?>%</td>
                                <td><span class="badge bg-primary">Bin <?php echo $delivery['bin_number']; ?></span></td>
                                <td><?php echo htmlspecialchars($delivery['received_by']); ?></td>
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
