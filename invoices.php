<?php
// invoices.php - Invoice Management
require_once 'config.php';
requireLogin();
$db = Database::getInstance()->getConnection();

$error = '';
$success = '';

// Add new invoice
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoice_no = sanitize($_POST['invoice_no'] ?? '');
    $permit_id = sanitize($_POST['permit_id'] ?? '');
    $delivery_id = sanitize($_POST['delivery_id'] ?? '');
    $invoice_date = sanitize($_POST['invoice_date'] ?? '');
    $amount = sanitize($_POST['amount'] ?? '');
    $notes = sanitize($_POST['notes'] ?? '');

    if (!$invoice_no || !$permit_id || !$invoice_date || !$amount) {
        $error = 'Invoice No, Permit, Date, and Amount are required.';
    } else {
        $stmt = $db->prepare('INSERT INTO invoices (invoice_no, permit_id, delivery_id, invoice_date, amount, notes, created_at) VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)');
        try {
            $stmt->execute([$invoice_no, $permit_id, $delivery_id ?: null, $invoice_date, $amount, $notes]);
            $success = 'Invoice added successfully!';
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'unique') !== false) {
                $error = 'Invoice number already exists.';
            } else {
                $error = 'Error: ' . $e->getMessage();
            }
        }
    }
}

// Get all invoices sorted ascending
$stmt = $db->query('
    SELECT i.*, p.permit_number, f.farmer_name, d.delivery_datetime
    FROM invoices i
    JOIN permits p ON i.permit_id = p.id
    JOIN farmers f ON p.farmer_id = f.id
    LEFT JOIN deliveries d ON i.delivery_id = d.id
    ORDER BY i.invoice_no ASC
');
$invoices = $stmt->fetchAll();

// Get permits for dropdown
$stmt = $db->query('SELECT id, permit_number FROM permits ORDER BY permit_number');
$permits = $stmt->fetchAll();

// Get deliveries for dropdown
$stmt = $db->query('SELECT id, delivery_datetime FROM deliveries ORDER BY delivery_datetime ASC');
$deliveries = $stmt->fetchAll();

include 'includes/header.php';
?>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Invoices Management</h1>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-ks-primary text-white">
                <h5 class="mb-0">Create Invoice</h5>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php elseif ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <form method="post">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="invoice_no" class="form-label">Invoice No</label>
                            <input type="text" name="invoice_no" id="invoice_no" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label for="permit_id" class="form-label">Permit</label>
                            <select name="permit_id" id="permit_id" class="form-select" required>
                                <option value="">Select Permit</option>
                                <?php foreach($permits as $permit): ?>
                                    <option value="<?php echo $permit['id']; ?>"><?php echo htmlspecialchars($permit['permit_number']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="invoice_date" class="form-label">Invoice Date</label>
                            <input type="date" name="invoice_date" id="invoice_date" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control" step="0.01" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="delivery_id" class="form-label">Delivery (Optional)</label>
                            <select name="delivery_id" id="delivery_id" class="form-select">
                                <option value="">Select Delivery</option>
                                <?php foreach($deliveries as $delivery): ?>
                                    <option value="<?php echo $delivery['id']; ?>"><?php echo formatDate($delivery['delivery_datetime']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-ks-primary w-100">Create Invoice</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Invoices List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Invoice No</th>
                                <th>Permit Number</th>
                                <th>Farmer</th>
                                <th>Invoice Date</th>
                                <th>Amount</th>
                                <th>Delivery Date</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($invoices as $invoice): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($invoice['invoice_no']); ?></strong></td>
                                <td><?php echo htmlspecialchars($invoice['permit_number']); ?></td>
                                <td><?php echo htmlspecialchars($invoice['farmer_name']); ?></td>
                                <td><?php echo formatDate($invoice['invoice_date']); ?></td>
                                <td><?php echo number_format($invoice['amount'], 2); ?></td>
                                <td><?php echo $invoice['delivery_datetime'] ? formatDate($invoice['delivery_datetime']) : '-'; ?></td>
                                <td><?php echo htmlspecialchars($invoice['notes'] ?? '-'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
<?php include 'includes/footer.php'; ?>
