BEGIN;

-- 1) Set default capacity where missing or zero (300 bags × 60 kg = 18,000 kg)
UPDATE bins
SET capacity_kg = 18000
WHERE capacity_kg IS NULL OR capacity_kg = 0;

-- 2) Ensure bins have numeric current_stock_kg baseline
UPDATE bins
SET current_stock_kg = 0
WHERE current_stock_kg IS NULL;

-- 3) Recompute current stock from deliveries (authoritative sum)
WITH sums AS (
  SELECT bin_id, COALESCE(SUM(kg_delivered),0) AS total_kg
  FROM deliveries
  GROUP BY bin_id
)
UPDATE bins
SET current_stock_kg = COALESCE(sums.total_kg, 0)
FROM sums
WHERE bins.id = sums.bin_id;

-- 4) Recompute status from capacity vs current stock
UPDATE bins
SET status =
  CASE
    WHEN current_stock_kg <= 0 THEN 'empty'
    WHEN capacity_kg > 0 AND current_stock_kg >= capacity_kg THEN 'full'
    ELSE 'partial'
  END;

COMMIT;
