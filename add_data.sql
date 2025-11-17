-- Remove DH04 and DH02 varieties
DELETE FROM seed_varieties WHERE variety_name IN ('DH04', 'DH02');

-- Insert sample farmers
INSERT INTO farmers (farmer_name, id_number, phone, email, location, created_by) VALUES
('John Kipchoge', 'ID001', '0712345678', 'john.kipchoge@email.com', 'Uasin Gishu County', 1),
('Mary Cheruiyot', 'ID002', '0723456789', 'mary.cheruiyot@email.com', 'Elgeyo Marakwet County', 1),
('Samuel Koech', 'ID003', '0734567890', 'samuel.koech@email.com', 'Nandi County', 1),
('Grace Rotich', 'ID004', '0745678901', 'grace.rotich@email.com', 'Trans Nzoia County', 1),
('David Kiplagat', 'ID005', '0756789012', 'david.kiplagat@email.com', 'Uasin Gishu County', 1);

-- Insert sample permits
INSERT INTO permits (permit_number, farmer_id, variety_id, total_bags, total_kg, issue_date, expiry_date, status) VALUES
('PERM-2024-001', 1, 1, 50, 2500, '2024-11-01', '2024-12-31', 'active'),
('PERM-2024-002', 2, 3, 75, 3750, '2024-11-05', '2024-12-31', 'active'),
('PERM-2024-003', 3, 2, 100, 5000, '2024-11-10', '2024-12-31', 'active'),
('PERM-2024-004', 4, 5, 60, 3000, '2024-11-12', '2024-12-31', 'active'),
('PERM-2024-005', 5, 7, 80, 4000, '2024-11-15', '2024-12-31', 'active');
