<?php
// scripts/apply_set_capacity_min.php
// Usage: php apply_set_capacity_min.php [--dry-run]
require_once __DIR__ . '/../config.php';
$dry = in_array('--dry-run', $argv);
$db = Database::getInstance()->getConnection();

try {
    // count affected
    $countStmt = $db->query("SELECT COUNT(*) AS cnt FROM bins WHERE capacity_kg IS NULL OR capacity_kg < 18000");
    $cnt = (int)$countStmt->fetchColumn();
    echo "Bins to update: $cnt\n";
    if ($cnt === 0) {
        echo "No bins require updating.\n";
        exit(0);
    }
    if ($dry) {
        echo "Dry-run: no changes will be made.\n";
        exit(0);
    }
    $db->beginTransaction();
    $db->exec("UPDATE bins SET capacity_kg = 18000 WHERE capacity_kg IS NULL OR capacity_kg < 18000");
    $db->commit();
    echo "Updated $cnt bins to capacity_kg = 18000.\n";
} catch (Exception $e) {
    if ($db->inTransaction()) $db->rollBack();
    echo "Failed: " . $e->getMessage() . "\n";
    exit(1);
}
exit(0);
