<?php
// permit_add.php - Add Permit Form
require_once 'config.php';
requireLogin();
$error = '';
$success = '';
$db = Database::getInstance()->getConnection();
$farmers = $db->query('SELECT id, farmer_name FROM farmers ORDER BY farmer_name')->fetchAll();
$varieties = $db->query('SELECT id, variety_name FROM seed_varieties ORDER BY variety_name')->fetchAll();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $permit_number = sanitize($_POST['permit_number'] ?? '');
    $farmer_id = intval($_POST['farmer_id'] ?? 0);
    $variety_id = intval($_POST['variety_id'] ?? 0);
    $total_bags = intval($_POST['total_bags'] ?? 0);
    $total_kg = floatval($_POST['total_kg'] ?? 0);
    $issue_date = $_POST['issue_date'] ?? date('Y-m-d');
    $expiry_date = $_POST['expiry_date'] ?? null;
    $created_by = $_SESSION['user_id'];
    if (!$permit_number || !$farmer_id || !$variety_id || !$total_bags || !$total_kg) {
        $error = 'All fields except expiry date are required.';
    } else {
        $stmt = $db->prepare('INSERT INTO permits (permit_number, farmer_id, variety_id, total_bags, total_kg, issue_date, expiry_date, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)');
        try {
            $stmt->execute([$permit_number, $farmer_id, $variety_id, $total_bags, $total_kg, $issue_date, $expiry_date]);
            logAudit($created_by, 'add_permit', 'permits', $db->lastInsertId('permits_id_seq'), "Added permit");
            $success = 'Permit added successfully!';
        } catch (PDOException $e) {
            $error = 'Error: ' . $e->getMessage();
        }
    }
}
include 'includes/header.php';
?>
<div class="row justify-content-center mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Add Permit</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"> <?php echo $error; ?> </div>
                <?php elseif ($success): ?>
                    <div class="alert alert-success"> <?php echo $success; ?> </div>
                <?php endif; ?>
                <form method="post" autocomplete="off">
                    <div class="mb-3">
                        <label for="permit_number" class="form-label">Permit Number</label>
                        <input type="text" name="permit_number" id="permit_number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="farmer_id" class="form-label">Farmer</label>
                        <select name="farmer_id" id="farmer_id" class="form-select" required>
                            <option value="">Select Farmer</option>
                            <?php foreach ($farmers as $farmer): ?>
                                <option value="<?php echo $farmer['id']; ?>"> <?php echo htmlspecialchars($farmer['farmer_name']); ?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="variety_id" class="form-label">Seed Variety</label>
                        <select name="variety_id" id="variety_id" class="form-select" required>
                            <option value="">Select Variety</option>
                            <?php foreach ($varieties as $variety): ?>
                                <option value="<?php echo $variety['id']; ?>"> <?php echo htmlspecialchars($variety['variety_name']); ?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="total_bags" class="form-label">Total Bags</label>
                        <input type="number" name="total_bags" id="total_bags" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="total_kg" class="form-label">Total Weight (kg)</label>
                        <input type="number" name="total_kg" id="total_kg" class="form-control" min="1" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="issue_date" class="form-label">Issue Date</label>
                        <input type="date" name="issue_date" id="issue_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="expiry_date" class="form-label">Expiry Date</label>
                        <input type="date" name="expiry_date" id="expiry_date" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add Permit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
