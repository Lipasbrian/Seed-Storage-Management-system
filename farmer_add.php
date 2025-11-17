<?php
// farmer_add.php - Add Farmer Form
require_once 'config.php';
requireLogin();
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $farmer_name = sanitize($_POST['farmer_name'] ?? '');
    $id_number = sanitize($_POST['id_number'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $location = sanitize($_POST['location'] ?? '');
    $created_by = $_SESSION['user_id'];
    if (!$farmer_name || !$phone) {
        $error = 'Farmer name and phone are required.';
    } else {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare('INSERT INTO farmers (farmer_name, id_number, phone, email, location, created_by) VALUES (?, ?, ?, ?, ?, ?)');
        try {
            $stmt->execute([$farmer_name, $id_number, $phone, $email, $location, $created_by]);
            logAudit($created_by, 'add_farmer', 'farmers', $db->lastInsertId('farmers_id_seq'), "Added farmer");
            $success = 'Farmer added successfully!';
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
                <h4 class="mb-0">Add Farmer</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"> <?php echo $error; ?> </div>
                <?php elseif ($success): ?>
                    <div class="alert alert-success"> <?php echo $success; ?> </div>
                <?php endif; ?>
                <form method="post" autocomplete="off">
                    <div class="mb-3">
                        <label for="farmer_name" class="form-label">Farmer Name</label>
                        <input type="text" name="farmer_name" id="farmer_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="id_number" class="form-label">ID Number</label>
                        <input type="text" name="id_number" id="id_number" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" name="location" id="location" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add Farmer</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
