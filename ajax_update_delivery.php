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
        // Fetch existing delivery to compute delta
        $oldStmt = $db->prepare('SELECT kg_delivered, bin_id FROM deliveries WHERE id = ?');
        $oldStmt->execute([$delivery_id]);
        $old = $oldStmt->fetch(PDO::FETCH_ASSOC);

        if ($old) {
            $oldKg = floatval($old['kg_delivered']);
            $oldBinId = intval($old['bin_id']);

            // Update the delivery
            $stmt = $db->prepare(
                'UPDATE deliveries SET bags_delivered = ?, kg_delivered = ?, moisture_content = ? WHERE id = ?'
            );
            $stmt->execute([$bags_delivered, $kg_delivered, $moisture_content, $delivery_id]);

            $kgDelta = floatval($kg_delivered) - $oldKg;
            // If bin changed in the edit (not supported by UI normally), handle it
            // For safety, read bin_id from POST if provided
            $newBinId = isset($_POST['bin_id']) ? intval($_POST['bin_id']) : $oldBinId;

            if ($newBinId === $oldBinId) {
                // Apply delta to the same bin
                $db->prepare('UPDATE bins SET current_stock_kg = current_stock_kg + ?, current_moisture_content = ? WHERE id = ?')
                    ->execute([$kgDelta, $moisture_content, $oldBinId]);
                // Recompute status for this bin
                $binInfo = $db->prepare('SELECT id, capacity_kg, current_stock_kg FROM bins WHERE id = ?');
                $binInfo->execute([$oldBinId]);
                $b = $binInfo->fetch(PDO::FETCH_ASSOC);
                if ($b) {
                    $newStock = floatval($b['current_stock_kg']);
                    $cap = floatval($b['capacity_kg']);
                    $newStatus = 'partial';
                    if ($newStock <= 0) $newStatus = 'empty';
                    elseif ($cap > 0 && $newStock >= $cap) $newStatus = 'full';
                    $db->prepare('UPDATE bins SET status = ? WHERE id = ?')->execute([$newStatus, $b['id']]);
                }
            } else {
                // Subtract from old bin and add to new bin
                $db->prepare('UPDATE bins SET current_stock_kg = current_stock_kg - ?, current_moisture_content = ? WHERE id = ?')
                    ->execute([$oldKg, $moisture_content, $oldBinId]);
                $db->prepare('UPDATE bins SET current_stock_kg = current_stock_kg + ?, current_moisture_content = ? WHERE id = ?')
                    ->execute([$kg_delivered, $moisture_content, $newBinId]);
                // Recompute statuses for both bins
                $affected = [$oldBinId, $newBinId];
                foreach ($affected as $bid) {
                    $binInfo = $db->prepare('SELECT id, capacity_kg, current_stock_kg FROM bins WHERE id = ?');
                    $binInfo->execute([$bid]);
                    $b = $binInfo->fetch(PDO::FETCH_ASSOC);
                    if ($b) {
                        $newStock = floatval($b['current_stock_kg']);
                        $cap = floatval($b['capacity_kg']);
                        $newStatus = 'partial';
                        if ($newStock <= 0) $newStatus = 'empty';
                        elseif ($cap > 0 && $newStock >= $cap) $newStatus = 'full';
                        $db->prepare('UPDATE bins SET status = ? WHERE id = ?')->execute([$newStatus, $b['id']]);
                    }
                }
            }

            // Log audit
            logAudit($_SESSION['user_id'], 'update_delivery', 'deliveries', $delivery_id, "Updated delivery: bags=$bags_delivered, kg=$kg_delivered, moisture=$moisture_content");

            echo json_encode(['success' => true, 'message' => 'Delivery updated and bin(s) adjusted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Original delivery not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
