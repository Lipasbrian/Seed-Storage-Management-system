<?php
require_once 'config.php';
$db = Database::getInstance()->getConnection();

try {
    // Add invoice_no column if it doesn't exist
    $db->exec("ALTER TABLE permits ADD COLUMN IF NOT EXISTS invoice_no VARCHAR(50) UNIQUE");
    echo "✓ Column 'invoice_no' added to permits table (or already exists)\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
