<?php
// ajax_check_bins.php - Bin Availability Checker (AJAX)
require_once 'config.php';
requireLogin();
header('Content-Type: application/json');
$db = Database::getInstance()->getConnection();
$variety_id = intval($_GET['variety_id'] ?? 0);
$moisture_content = floatval($_GET['moisture_content'] ?? 0);
$sql = "SELECT id, bin_number, status, capacity_kg, current_stock_kg FROM bins WHERE assigned_variety_id = ? AND status != 'full' ORDER BY bin_number";
$stmt = $db->prepare($sql);
$stmt->execute([$variety_id]);
$bins = $stmt->fetchAll();
echo json_encode($bins);
