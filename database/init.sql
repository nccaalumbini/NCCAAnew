CREATE DATABASE IF NOT EXISTS nccaa_db;
USE nccaa_db;

CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admin_users (username, password, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@nccaa.gov.np');

CREATE TABLE notices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE
);

CREATE TABLE cadet_forms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    province VARCHAR(100) NOT NULL,
    post VARCHAR(100) NOT NULL,
    nsc_batch VARCHAR(50) NOT NULL,
    year_batch VARCHAR(50) NOT NULL,
    division_training_year VARCHAR(50) NOT NULL,
    previous_district VARCHAR(100) NOT NULL,
    available_time VARCHAR(100) NOT NULL,
    personal_number VARCHAR(50) NOT NULL,
    rank_position VARCHAR(50) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    gender VARCHAR(10) NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    graduated_school VARCHAR(200) NOT NULL,
    signature_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);