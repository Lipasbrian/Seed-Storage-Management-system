-- Migration: set capacity_kg to 18000 where NULL or less than 18000
BEGIN;

UPDATE bins
SET capacity_kg = 18000
WHERE capacity_kg IS NULL OR capacity_kg < 18000;

COMMIT;
