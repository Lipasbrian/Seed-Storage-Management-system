<?php
// bin_reconcile.php
// Usage (CLI): php bin_reconcile.php [--dry-run]

require_once __DIR__ . '/config.php';

$dryRun = in_array('--dry-run', $argv);

echo ($dryRun ? "DRY RUN: no changes will be saved\n" : "Running reconciliation...\n");

$db = Database::getInstance()->getConnection();

try {
    if (!$dryRun) $db->beginTransaction();

    // 1) Set default capacity where missing or zero
    $sql1 = "UPDATE bins SET capacity_kg = 18000 WHERE capacity_kg IS NULL OR capacity_kg = 0";
    if ($dryRun) {
        echo "Would run: $sql1\n";
    } else {
        $db->exec($sql1);
        echo "Set default capacities where missing.\n";
    }

    // 2) Ensure bins have numeric current_stock_kg baseline
    $sql2 = "UPDATE bins SET current_stock_kg = 0 WHERE current_stock_kg IS NULL";
    if ($dryRun) {
        echo "Would run: $sql2\n";
    } else {
        $db->exec($sql2);
        echo "Initialized null current_stock_kg to 0.\n";
    }

    // 3) Recompute sums from deliveries and update bins
    $sql3 = "WITH sums AS (
      SELECT bin_id, COALESCE(SUM(kg_delivered),0) AS total_kg
      FROM deliveries
      GROUP BY bin_id
    )
    UPDATE bins
    SET current_stock_kg = COALESCE(sums.total_kg, 0)
    FROM sums
    WHERE bins.id = sums.bin_id";

    if ($dryRun) {
        echo "Would run reconciliation update from deliveries.\n";
    } else {
        $db->exec($sql3);
        echo "Recomputed bin current_stock_kg from deliveries.\n";
    }

    // 4) Recompute status from capacity vs current stock
    $sql4 = "UPDATE bins SET status = CASE WHEN current_stock_kg <= 0 THEN 'empty' WHEN capacity_kg > 0 AND current_stock_kg >= capacity_kg THEN 'full' ELSE 'partial' END";
    if ($dryRun) {
        echo "Would run: $sql4\n";
    } else {
        $db->exec($sql4);
        echo "Updated bin status based on capacity and stock.\n";
    }

    if (!$dryRun) {
        $db->commit();
        echo "Reconciliation committed successfully.\n";
    } else {
        echo "Dry-run complete. No changes saved.\n";
    }
} catch (Exception $e) {
    if (!$dryRun && $db->inTransaction()) $db->rollBack();
    echo "Reconciliation failed: " . $e->getMessage() . "\n";
    exit(1);
}

exit(0);
