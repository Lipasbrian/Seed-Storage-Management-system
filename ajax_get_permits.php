<?php
// ajax_get_permits.php - Dynamic Permit Loading (AJAX)
require_once 'config.php';
requireLogin();
header('Content-Type: application/json');
$db = Database::getInstance()->getConnection();
$farmer_id = intval($_GET['farmer_id'] ?? 0);
$variety_id = intval($_GET['variety_id'] ?? 0);
$where = [];
$params = [];
if ($farmer_id) {
    $where[] = 'farmer_id = ?';
    $params[] = $farmer_id;
}
if ($variety_id) {
    $where[] = 'variety_id = ?';
    $params[] = $variety_id;
}
$where[] = "status = 'active'";
$sql = "SELECT id, permit_number FROM permits";
if ($where) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY permit_number";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$permits = $stmt->fetchAll();
echo json_encode($permits);
