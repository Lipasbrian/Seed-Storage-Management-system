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
// Invoice statistics
$stmt = $db->query("SELECT COUNT(*) as total FROM invoices");
$total_invoices = $stmt->fetch()['total'] ?? 0;
$stmt = $db->query("SELECT SUM(amount) as total FROM invoices");
$total_invoice_amount = $stmt->fetch()['total'] ?? 0;
include 'includes/header.php';
?>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Dashboard</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="delivery_add.php" class="btn btn-sm btn-ks-primary">
                        <i class="bi bi-plus-circle"></i> New Delivery
                    </a>
                </div>
            </div>
        </div>
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card">
                    <div class="dashboard-card-header">
                        <h5 class="dashboard-card-title">
                            <i class="bi bi-box"></i> Empty Bins
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="stat-value"><?php echo $empty_bins; ?></div>
                        <div class="stat-label">out of 48 bins</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card">
                    <div class="dashboard-card-header">
                        <h5 class="dashboard-card-title">
                            <i class="bi bi-box-seam"></i> Partial Bins
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="stat-value" style="color: var(--kenya-seed-yellow);"><?php echo $partial_bins; ?></div>
                        <div class="stat-label">out of 48 bins</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card">
                    <div class="dashboard-card-header">
                        <h5 class="dashboard-card-title">
                            <i class="bi bi-box2-heart"></i> Full Bins
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="stat-value" style="color: #dc3545;"><?php echo $full_bins; ?></div>
                        <div class="stat-label">out of 48 bins</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card">
                    <div class="dashboard-card-header">
                        <h5 class="dashboard-card-title">
                            <i class="bi bi-receipt"></i> Invoices
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="stat-value"><?php echo $total_invoices; ?></div>
                        <div class="stat-label">total invoices</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card">
                    <div class="dashboard-card-header">
                        <h5 class="dashboard-card-title">
                            <i class="bi bi-currency-dollar"></i> Invoice Total
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="stat-value"><?php echo number_format($total_invoice_amount, 2); ?></div>
                        <div class="stat-label">amount total</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card dashboard-card">
                    <div class="dashboard-card-header">
                        <h5 class="dashboard-card-title">
                            <i class="bi bi-truck"></i> Today's Deliveries
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 text-center border-end">
                                <div class="stat-value"><?php echo $today_deliveries; ?></div>
                                <div class="stat-label">deliveries</div>
                            </div>
                            <div class="col-6 text-center">
                                <div class="stat-value"><?php echo number_format($today_kg); ?></div>
                                <div class="stat-label">kg received</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Recent Deliveries -->
        <div class="card dashboard-card">
            <div class="dashboard-card-header">
                <h5 class="dashboard-card-title">
                    <i class="bi bi-clock-history"></i> Recent Deliveries
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-branded table-hover">
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
<?php include 'includes/footer.php'; ?>
