<?php
// delivery_add.php - Add New Delivery
require_once 'config.php';
requireLogin();
$db = Database::getInstance()->getConnection();
$error = '';
$success = '';
// Fetch farmers, varieties, permits, bins
$farmers = $db->query('SELECT id, farmer_name FROM farmers ORDER BY farmer_name')->fetchAll();
$varieties = $db->query('SELECT id, variety_name FROM seed_varieties ORDER BY variety_name')->fetchAll();
$permits = $db->query('SELECT id, permit_number FROM permits WHERE status = \'active\' ORDER BY permit_number')->fetchAll();
$bins = $db->query('SELECT id, bin_number, status, assigned_variety_id, current_moisture_content FROM bins ORDER BY bin_number')->fetchAll();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $permit_id = intval($_POST['permit_id'] ?? 0);
    $farmer_id = intval($_POST['farmer_id'] ?? 0);
    $variety_id = intval($_POST['variety_id'] ?? 0);
    $bin_id = intval($_POST['bin_id'] ?? 0);
    $bags_delivered = intval($_POST['bags_delivered'] ?? 0);
    $kg_delivered = floatval($_POST['kg_delivered'] ?? 0);
    $moisture_content = floatval($_POST['moisture_content'] ?? 0);
    $delivery_datetime = $_POST['delivery_datetime'] ?? date('Y-m-d\TH:i');
    $notes = sanitize($_POST['notes'] ?? '');
    $received_by = $_SESSION['user_id'];
    // Validate bin capacity
    $bin = $db->prepare('SELECT capacity_kg, current_stock_kg FROM bins WHERE id = ?');
    $bin->execute([$bin_id]);
    $bin_data = $bin->fetch();
    if ($bin_data && ($bin_data['current_stock_kg'] + $kg_delivered) > $bin_data['capacity_kg']) {
        $error = 'Bin capacity exceeded!';
    } else {
        // Insert delivery
        $stmt = $db->prepare('INSERT INTO deliveries (permit_id, farmer_id, variety_id, bin_id, bags_delivered, kg_delivered, moisture_content, delivery_datetime, received_by, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$permit_id, $farmer_id, $variety_id, $bin_id, $bags_delivered, $kg_delivered, $moisture_content, $delivery_datetime, $received_by, $notes]);
        // Update bin stock and status
        $db->prepare('UPDATE bins SET current_stock_kg = current_stock_kg + ?, current_moisture_content = ? WHERE id = ?')->execute([$kg_delivered, $moisture_content, $bin_id]);
        // Audit log
        logAudit($received_by, 'add_delivery', 'deliveries', $db->lastInsertId('deliveries_id_seq'), "Added delivery");
        $success = 'Delivery recorded successfully!';
    }
}
include 'includes/header.php';
?>
<div class="row justify-content-center mt-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Add New Delivery</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"> <?php echo $error; ?> </div>
                <?php elseif ($success): ?>
                    <div class="alert alert-success"> <?php echo $success; ?> </div>
                <?php endif; ?>
                <form method="post" autocomplete="off">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="permit_id" class="form-label">Permit Number</label>
                            <select name="permit_id" id="permit_id" class="form-select" required>
                                <option value="">Select Permit</option>
                                <?php foreach ($permits as $permit): ?>
                                    <option value="<?php echo $permit['id']; ?>"> <?php echo htmlspecialchars($permit['permit_number']); ?> </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="farmer_id" class="form-label">Farmer</label>
                            <select name="farmer_id" id="farmer_id" class="form-select" required>
                                <option value="">Select Farmer</option>
                                <?php foreach ($farmers as $farmer): ?>
                                    <option value="<?php echo $farmer['id']; ?>"> <?php echo htmlspecialchars($farmer['farmer_name']); ?> </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="variety_id" class="form-label">Seed Variety</label>
                            <select name="variety_id" id="variety_id" class="form-select" required>
                                <option value="">Select Variety</option>
                                <?php foreach ($varieties as $variety): ?>
                                    <option value="<?php echo $variety['id']; ?>"> <?php echo htmlspecialchars($variety['variety_name']); ?> </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="bin_id" class="form-label">Bin Number</label>
                            <select name="bin_id" id="bin_id" class="form-select" required>
                                <option value="">Select Bin</option>
                                <?php foreach ($bins as $bin): ?>
                                    <option value="<?php echo $bin['id']; ?>">Bin <?php echo $bin['bin_number']; ?> (<?php echo $bin['status']; ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="bags_delivered" class="form-label">Bags Delivered</label>
                            <input type="number" name="bags_delivered" id="bags_delivered" class="form-control" min="1" required>
                        </div>
                        <div class="col-md-4">
                            <label for="kg_delivered" class="form-label">Weight (kg)</label>
                            <input type="number" name="kg_delivered" id="kg_delivered" class="form-control" min="1" step="0.01" required>
                        </div>
                        <div class="col-md-4">
                            <label for="moisture_content" class="form-label">Moisture Content (%)</label>
                            <input type="number" name="moisture_content" id="moisture_content" class="form-control" min="0" step="0.01" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="delivery_datetime" class="form-label">Delivery Date & Time</label>
                        <input type="datetime-local" name="delivery_datetime" id="delivery_datetime" class="form-control" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Record Delivery</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
