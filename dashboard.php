<?php
// dashboard.php - Main Dashboard
require_once 'config.php';
requireLogin();
$db = Database::getInstance()->getConnection();
// Bin statistics
$stmt = $db->query("SELECT COUNT(*) as total FROM bins WHERE status = 'empty'");
$empty_bins = $stmt->fetch()['total'];
$stmt = $db->query("SELECT COUNT(*) as total FROM bins WHERE status = 'partial'");
$partial_bins = $stmt->fetch()['total'];
$stmt = $db->query("SELECT COUNT(*) as total FROM bins WHERE status = 'full'");
$full_bins = $stmt->fetch()['total'];
$stmt = $db->query("SELECT SUM(current_stock_kg) as total FROM bins");
$total_stock = $stmt->fetch()['total'] ?? 0;
// Today's deliveries
$stmt = $db->query("SELECT COUNT(*) as total FROM deliveries WHERE DATE(delivery_datetime) = CURRENT_DATE");
$today_deliveries = $stmt->fetch()['total'];
$stmt = $db->query("SELECT SUM(kg_delivered) as total FROM deliveries WHERE DATE(delivery_datetime) = CURRENT_DATE");
$today_kg = $stmt->fetch()['total'] ?? 0;
// Recent deliveries
$stmt = $db->query("SELECT * FROM delivery_summary ORDER BY delivery_datetime DESC LIMIT 10");
$recent_deliveries = $stmt->fetchAll();
// Bin status grid
$stmt = $db->query("SELECT * FROM bin_utilization ORDER BY bin_number");
$bins = $stmt->fetchAll();
include 'includes/header.php';
?>
<div class="row">
    <?php include 'includes/sidebar.php'; ?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Dashboard</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="delivery_add.php" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle"></i> New Delivery
                    </a>
                </div>
            </div>
        </div>
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Empty Bins</h5>
                        <h2><?php echo $empty_bins; ?>/48</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Partial Bins</h5>
                        <h2><?php echo $partial_bins; ?>/48</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <h5 class="card-title">Full Bins</h5>
                        <h2><?php echo $full_bins; ?>/48</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Total Stock</h5>
                        <h2><?php echo number_format($total_stock); ?> kg</h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- Today's Stats -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Today's Deliveries</h5>
                        <h2><?php echo $today_deliveries; ?></h2>
                        <p class="text-muted">Total: <?php echo number_format($today_kg); ?> kg</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Recent Deliveries -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Recent Deliveries</h5>
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recent_deliveries as $delivery): ?>
                            <tr>
                                <td><?php echo formatDate($delivery['delivery_datetime']); ?></td>
                                <td><?php echo htmlspecialchars($delivery['farmer_name']); ?></td>
                                <td><?php echo htmlspecialchars($delivery['variety_name']); ?></td>
                                <td><?php echo htmlspecialchars($delivery['permit_number']); ?></td>
                                <td><?php echo $delivery['bags_delivered']; ?></td>
                                <td><?php echo number_format($delivery['kg_delivered'], 2); ?></td>
                                <td><?php echo $delivery['moisture_content']; ?>%</td>
                                <td><span class="badge bg-primary">Bin <?php echo $delivery['bin_number']; ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Bin Status Grid -->
        <div class="card">
            <div class="card-header">
                <h5>Bin Status (1-48)</h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <?php foreach($bins as $bin): ?>
                    <div class="col-md-1 col-sm-2 col-3">
                        <div class="card text-center 
                            <?php 
                                if($bin['status'] == 'empty') echo 'bg-success text-white';
                                elseif($bin['status'] == 'partial') echo 'bg-warning';
                                else echo 'bg-danger text-white';
                            ?>">
                            <div class="card-body p-2">
                                <strong><?php echo $bin['bin_number']; ?></strong>
                                <br>
                                <small><?php echo round($bin['utilization_percent']); ?>%</small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include 'includes/footer.php'; ?>
