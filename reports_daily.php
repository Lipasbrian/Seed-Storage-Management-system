<?php
// reports_daily.php - Daily Delivery Report
require_once 'config.php';
requireLogin();
$db = Database::getInstance()->getConnection();
$date = $_GET['date'] ?? date('Y-m-d');
$stmt = $db->prepare("SELECT d.*, f.farmer_name, sv.variety_name, p.permit_number, b.bin_number, u.full_name as received_by
    FROM deliveries d
    JOIN farmers f ON d.farmer_id = f.id
    JOIN seed_varieties sv ON d.variety_id = sv.id
    JOIN permits p ON d.permit_id = p.id
    JOIN bins b ON d.bin_id = b.id
    JOIN users u ON d.received_by = u.id
    WHERE DATE(d.delivery_datetime) = ?
    ORDER BY d.delivery_datetime DESC");
$stmt->execute([$date]);
$deliveries = $stmt->fetchAll();
$total_kg = 0;
foreach ($deliveries as $delivery) {
    $total_kg += $delivery['kg_delivered'];
}
include 'includes/header.php';
?>
<div class="row">
    <?php include 'includes/sidebar.php'; ?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Daily Delivery Report</h1>
            <form class="row g-2" method="get">
                <div class="col-auto">
                    <input type="date" name="date" class="form-control" value="<?php echo htmlspecialchars($date); ?>">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Show</button>
                </div>
            </form>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <h5>Summary for <?php echo date('d M Y', strtotime($date)); ?></h5>
            </div>
            <div class="card-body">
                <p><strong>Total Deliveries:</strong> <?php echo count($deliveries); ?></p>
                <p><strong>Total Weight Delivered:</strong> <?php echo number_format($total_kg, 2); ?> kg</p>
            </div>
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
