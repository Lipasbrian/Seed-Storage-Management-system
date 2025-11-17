<?php
require_once 'config.php';
$db = Database::getInstance()->getConnection();

try {
    $db->exec("
        CREATE TABLE IF NOT EXISTS invoices (
            id SERIAL PRIMARY KEY,
            invoice_no VARCHAR(50) UNIQUE NOT NULL,
            permit_id INTEGER NOT NULL REFERENCES permits(id),
            delivery_id INTEGER REFERENCES deliveries(id),
            invoice_date DATE NOT NULL,
            amount DECIMAL(12,2) NOT NULL,
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Invoices table created successfully\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
