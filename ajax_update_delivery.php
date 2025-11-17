<?php
// ajax_update_delivery.php - Update delivery data
require_once 'config.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = Database::getInstance()->getConnection();
    
    $delivery_id = sanitize($_POST['delivery_id'] ?? '');
    $bags_delivered = sanitize($_POST['bags_delivered'] ?? '');
    $kg_delivered = sanitize($_POST['kg_delivered'] ?? '');
    $moisture_content = sanitize($_POST['moisture_content'] ?? '');

    if (!$delivery_id || !$bags_delivered || !$kg_delivered || !$moisture_content) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }

    try {
        $stmt = $db->prepare('
            UPDATE deliveries 
            SET bags_delivered = ?, kg_delivered = ?, moisture_content = ?
            WHERE id = ?
        ');
        $stmt->execute([$bags_delivered, $kg_delivered, $moisture_content, $delivery_id]);

        // Log audit
        logAudit($_SESSION['user_id'], 'update_delivery', 'deliveries', $delivery_id, "Updated delivery: bags=$bags_delivered, kg=$kg_delivered, moisture=$moisture_content");

        echo json_encode(['success' => true, 'message' => 'Delivery updated successfully']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
