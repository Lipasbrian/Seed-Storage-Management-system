-- Users Table
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role VARCHAR(20) NOT NULL CHECK (role IN ('admin', 'data_entry', 'viewer')),
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP,
    status VARCHAR(20) DEFAULT 'active' CHECK (status IN ('active', 'inactive'))
);

-- Farmers Table
CREATE TABLE farmers (
    id SERIAL PRIMARY KEY,
    farmer_name VARCHAR(100) NOT NULL,
    id_number VARCHAR(20) UNIQUE,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),    php -S localhost:8000
    location VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INTEGER REFERENCES users(id)
);

-- Seed Varieties Table
CREATE TABLE seed_varieties (
    id SERIAL PRIMARY KEY,
    variety_name VARCHAR(100) UNIQUE NOT NULL,
    optimal_moisture_min DECIMAL(5,2),
    optimal_moisture_max DECIMAL(5,2),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bins Table (48 bins)
CREATE TABLE bins (
    id SERIAL PRIMARY KEY,
    bin_number INTEGER UNIQUE NOT NULL CHECK (bin_number BETWEEN 1 AND 48),
    capacity_kg DECIMAL(10,2) NOT NULL,
    current_stock_kg DECIMAL(10,2) DEFAULT 0,
    status VARCHAR(20) DEFAULT 'empty' CHECK (status IN ('empty', 'partial', 'full')),
    assigned_variety_id INTEGER REFERENCES seed_varieties(id),
    current_moisture_content DECIMAL(5,2),
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- seed_storage_db.sql - PostgreSQL Database Schema

-- Permits Table
CREATE TABLE permits (
    id SERIAL PRIMARY KEY,
    permit_number VARCHAR(50) UNIQUE NOT NULL,
    farmer_id INTEGER NOT NULL REFERENCES farmers(id),
    variety_id INTEGER NOT NULL REFERENCES seed_varieties(id),
    total_bags INTEGER NOT NULL,
    total_kg DECIMAL(10,2) NOT NULL,
    issue_date DATE NOT NULL,
    expiry_date DATE,
    status VARCHAR(20) DEFAULT 'active' CHECK (status IN ('active', 'completed', 'expired')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Deliveries Table
CREATE TABLE deliveries (
    id SERIAL PRIMARY KEY,
    permit_id INTEGER NOT NULL REFERENCES permits(id),
    farmer_id INTEGER NOT NULL REFERENCES farmers(id),
    variety_id INTEGER NOT NULL REFERENCES seed_varieties(id),
    bin_id INTEGER NOT NULL REFERENCES bins(id),
    bags_delivered INTEGER NOT NULL,
    kg_delivered DECIMAL(10,2) NOT NULL,
    moisture_content DECIMAL(5,2) NOT NULL,
    delivery_datetime TIMESTAMP NOT NULL,
    received_by INTEGER NOT NULL REFERENCES users(id),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Audit Log Table
CREATE TABLE audit_log (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(id),
    action VARCHAR(50) NOT NULL,
    table_name VARCHAR(50) NOT NULL,
    record_id INTEGER,
    details TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, password, full_name, role, email) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin', 'admin@seedstorage.com');

-- Insert maize-only seed varieties
INSERT INTO seed_varieties (variety_name, optimal_moisture_min, optimal_moisture_max, description) VALUES
('H513', 12.0, 13.5, 'Maize hybrid variety'),
('H6213', 12.0, 13.5, 'Maize hybrid variety'),
('H517', 12.0, 13.5, 'Maize hybrid variety'),
('H6210', 12.0, 13.5, 'Maize hybrid variety'),
('DH04', 12.0, 13.5, 'Maize hybrid variety'),
('H624', 12.0, 13.5, 'Maize hybrid variety'),
('H629', 12.0, 13.5, 'Maize hybrid variety'),
('DH02', 12.0, 13.5, 'Maize hybrid variety'),
('PH1', 12.0, 13.5, 'Maize hybrid variety'),
('PH4', 12.0, 13.5, 'Maize hybrid variety'),
('H516', 12.0, 13.5, 'Maize hybrid variety'),
('H520', 12.0, 13.5, 'Maize hybrid variety'),
('H628', 12.0, 13.5, 'Maize hybrid variety'),
('H6218', 12.0, 13.5, 'Maize hybrid variety'),
('H614D', 12.0, 13.5, 'Maize hybrid variety');

-- Insert all 48 bins with default capacity
INSERT INTO bins (bin_number, capacity_kg) 
SELECT generate_series(1, 48), 5000;

-- Views for reporting
CREATE OR REPLACE VIEW delivery_summary AS
SELECT 
    d.id,
    d.delivery_datetime,
    f.farmer_name,
    sv.variety_name,
    p.permit_number,
    d.bags_delivered,
    d.kg_delivered,
    d.moisture_content,
    b.bin_number,
    u.full_name as received_by
FROM deliveries d
JOIN farmers f ON d.farmer_id = f.id
JOIN seed_varieties sv ON d.variety_id = sv.id
JOIN permits p ON d.permit_id = p.id
JOIN bins b ON d.bin_id = b.id
JOIN users u ON d.received_by = u.id;

CREATE OR REPLACE VIEW bin_utilization AS
SELECT 
    b.bin_number,
    b.capacity_kg,
    b.current_stock_kg,
    ROUND((b.current_stock_kg / b.capacity_kg) * 100, 2) as utilization_percent,
    b.status,
    sv.variety_name,
    b.current_moisture_content
FROM bins b
LEFT JOIN seed_varieties sv ON b.assigned_variety_id = sv.id
ORDER BY b.bin_number;

-- Create indexes for better performance
CREATE INDEX idx_deliveries_datetime ON deliveries(delivery_datetime);
CREATE INDEX idx_deliveries_farmer ON deliveries(farmer_id);
CREATE INDEX idx_deliveries_variety ON deliveries(variety_id);
CREATE INDEX idx_bins_status ON bins(status);
CREATE INDEX idx_permits_farmer ON permits(farmer_id);
CREATE INDEX idx_audit_log_user ON audit_log(user_id);
CREATE INDEX idx_audit_log_datetime ON audit_log(created_at);
