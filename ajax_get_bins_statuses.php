<?php
// ajax_get_bins_statuses.php - Return JSON of all bins for realtime UI updates
require_once 'config.php';
requireLogin();
header('Content-Type: application/json');
$db = Database::getInstance()->getConnection();
$stmt = $db->query('SELECT id, bin_number, capacity_kg, current_stock_kg, status, last_updated FROM bins ORDER BY bin_number');
$bins = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Compute fill percent for each
foreach ($bins as &$b) {
    $cap = floatval($b['capacity_kg']);
    $cur = floatval($b['current_stock_kg']);
    $b['fill_percent'] = ($cap > 0) ? min(100, ($cur / $cap) * 100) : 0;
}
echo json_encode($bins);
?>
