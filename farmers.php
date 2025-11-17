<?php
// farmers.php - Farmers Listing
require_once 'config.php';
requireLogin();
$db = Database::getInstance()->getConnection();
// Search/filter logic
$where = [];
$params = [];
if (!empty($_GET['name'])) {
    $where[] = 'farmer_name ILIKE ?';
    $params[] = '%' . sanitize($_GET['name']) . '%';
}
if (!empty($_GET['location'])) {
    $where[] = 'location ILIKE ?';
    $params[] = '%' . sanitize($_GET['location']) . '%';
}
$sql = "SELECT * FROM farmers";
if ($where) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY farmer_name";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$farmers = $stmt->fetchAll();
include 'includes/header.php';
?>
<div class="row">
    <?php include 'includes/sidebar.php'; ?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Farmers Listing</h1>
            <form class="row g-2" method="get">
                <div class="col-auto">
                    <input type="text" name="name" class="form-control" placeholder="Farmer Name" value="<?php echo htmlspecialchars($_GET['name'] ?? ''); ?>">
                </div>
                <div class="col-auto">
                    <input type="text" name="location" class="form-control" placeholder="Location" value="<?php echo htmlspecialchars($_GET['location'] ?? ''); ?>">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
        <div class="card">
            <div class="card-header">
                <h5>Farmers List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>ID Number</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Location</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($farmers as $farmer): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($farmer['farmer_name']); ?></td>
                                <td><?php echo htmlspecialchars($farmer['id_number']); ?></td>
                                <td><?php echo htmlspecialchars($farmer['phone']); ?></td>
                                <td><?php echo htmlspecialchars($farmer['email']); ?></td>
                                <td><?php echo htmlspecialchars($farmer['location']); ?></td>
                                <td><?php echo formatDate($farmer['created_at']); ?></td>
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
