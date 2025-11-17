-- Insert randomized permits with 6-digit permit numbers
INSERT INTO permits (permit_number, farmer_id, variety_id, total_bags, total_kg, issue_date, expiry_date, status) VALUES
('824563', 1, 1, 500, 25000, CURRENT_DATE - INTERVAL '30 days', CURRENT_DATE + INTERVAL '60 days', 'active'),
('937421', 2, 2, 300, 15000, CURRENT_DATE - INTERVAL '25 days', CURRENT_DATE + INTERVAL '65 days', 'active'),
('512847', 3, 3, 400, 20000, CURRENT_DATE - INTERVAL '20 days', CURRENT_DATE + INTERVAL '70 days', 'active'),
('658734', 4, 4, 350, 17500, CURRENT_DATE - INTERVAL '15 days', CURRENT_DATE + INTERVAL '75 days', 'active'),
('741856', 5, 6, 450, 22500, CURRENT_DATE - INTERVAL '10 days', CURRENT_DATE + INTERVAL '80 days', 'active'),
('326954', 6, 7, 280, 14000, CURRENT_DATE - INTERVAL '8 days', CURRENT_DATE + INTERVAL '82 days', 'active'),
('485621', 7, 9, 520, 26000, CURRENT_DATE - INTERVAL '5 days', CURRENT_DATE + INTERVAL '85 days', 'active'),
('693247', 8, 10, 310, 15500, CURRENT_DATE - INTERVAL '3 days', CURRENT_DATE + INTERVAL '87 days', 'active'),
('174938', 9, 11, 420, 21000, CURRENT_DATE - INTERVAL '2 days', CURRENT_DATE + INTERVAL '88 days', 'active'),
('562817', 10, 12, 380, 19000, CURRENT_DATE - INTERVAL '1 day', CURRENT_DATE + INTERVAL '89 days', 'active'),
('839162', 1, 13, 290, 14500, CURRENT_DATE, CURRENT_DATE + INTERVAL '90 days', 'active'),
('457382', 2, 14, 410, 20500, CURRENT_DATE, CURRENT_DATE + INTERVAL '90 days', 'active');
