<?php
// bins.php - Bins Management
require_once 'config.php';
requireLogin();
$db = Database::getInstance()->getConnection();
// Fetch bins
$stmt = $db->query('SELECT b.*, sv.variety_name FROM bins b LEFT JOIN seed_varieties sv ON b.assigned_variety_id = sv.id ORDER BY b.bin_number');
$bins = $stmt->fetchAll();
include 'includes/header.php';
?>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Bins Management</h1>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <h5>All Bins (1-48)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Bin Number</th>
                                <th>Capacity (kg)</th>
                                <th>Current Stock (kg)</th>
                                <th>Status</th>
                                <th>Variety</th>
                                <th>Moisture Content (%)</th>
                                <th>Last Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($bins as $bin): ?>
                            <tr>
                                <td><?php echo $bin['bin_number']; ?></td>
                                <td><?php echo number_format($bin['capacity_kg']); ?></td>
                                <td><?php echo number_format($bin['current_stock_kg']); ?></td>
                                <td>
                                    <?php
                                        if($bin['status'] == 'empty') echo '<span class="badge bg-success">Empty</span>';
                                        elseif($bin['status'] == 'partial') echo '<span class="badge bg-warning">Partial</span>';
                                        else echo '<span class="badge bg-danger">Full</span>';
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($bin['variety_name'] ?? ''); ?></td>
                                <td><?php echo $bin['current_moisture_content']; ?></td>
                                <td><?php echo formatDate($bin['last_updated']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
<?php include 'includes/footer.php'; ?>
