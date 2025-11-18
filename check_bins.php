<?php
// check_bins.php - quick CLI helper to inspect bin state
require_once __DIR__ . '/config.php';
$db = Database::getInstance()->getConnection();

$limit = 48;
if (isset($argv[1]) && is_numeric($argv[1])) {
    $limit = intval($argv[1]);
}

$stmt = $db->query('SELECT id, bin_number, capacity_kg, current_stock_kg, status, last_updated FROM bins ORDER BY bin_number LIMIT ' . $limit);
$bins = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$bins) {
    echo "No bins found.\n";
    exit(0);
}

echo "Showing up to $limit bins:\n\n";
foreach ($bins as $b) {
    $cap = floatval($b['capacity_kg']);
    $cur = floatval($b['current_stock_kg']);
    $fill = $cap > 0 ? round(($cur / $cap) * 100, 2) : 0;
    printf("Bin %s: status=%s, stock=%s kg, cap=%s kg (fill=%s%%), last_updated=%s\n",
        $b['bin_number'], $b['status'], number_format($cur,2), number_format($cap,2), $fill, $b['last_updated']);
}

exit(0);
