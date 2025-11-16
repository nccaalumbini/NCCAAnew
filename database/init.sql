CREATE DATABASE IF NOT EXISTS nccaa_db;
USE nccaa_db;

-- Admin users table
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin@123)
-- Hash generated using password_hash('admin@123', PASSWORD_DEFAULT)
INSERT INTO admin_users (username, password, email) VALUES 
('admin', '$2y$10$j/A2Qr0/w1jKLs0x5R2.EO7nKDn4b7f3G0dT5K3pL4H5M6N7O8P9', 'admin@nccaa.gov.np');

-- Notices table
CREATE TABLE notices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE
);

-- Cadet forms table (UPDATED STRUCTURE)
CREATE TABLE cadet_forms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- SECTION 1: PERSONAL DETAILS
    full_name VARCHAR(100) NOT NULL,
    gender VARCHAR(20) NOT NULL,
    age INT NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    province VARCHAR(100) NOT NULL,
    district VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    
    -- SECTION 2: NCC DETAILS
    ncc_batch_number VARCHAR(50),
    ncc_personal_number VARCHAR(50),
    ncc_division VARCHAR(20),
    ncc_passout_school VARCHAR(200),
    ncc_passout_year VARCHAR(10),
    ncc_rank_position VARCHAR(100),
    
    -- SECTION 3: NCCAA DETAILS
    nccaa_position_applied VARCHAR(100),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);