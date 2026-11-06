CREATE DATABASE IF NOT EXISTS blood_bank_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE blood_bank_db;

CREATE TABLE IF NOT EXISTS admins (
  admin_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS blood_stock (
  stock_id INT AUTO_INCREMENT PRIMARY KEY,
  blood_group VARCHAR(5) NOT NULL UNIQUE,
  units_available INT NOT NULL DEFAULT 0,
  last_updated DATE NOT NULL
);

CREATE TABLE IF NOT EXISTS blood_requests (
  request_id INT AUTO_INCREMENT PRIMARY KEY,
  hospital_name VARCHAR(150) NOT NULL,
  blood_group VARCHAR(5) NOT NULL,
  units_required INT NOT NULL,
  request_date DATE NOT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'Pending'
);

INSERT IGNORE INTO blood_stock (blood_group, units_available, last_updated) VALUES
('A+', 12, CURDATE()), ('A-', 5, CURDATE()),
('B+', 10, CURDATE()), ('B-', 4, CURDATE()),
('AB+', 6, CURDATE()), ('AB-', 2, CURDATE()),
('O+', 15, CURDATE()), ('O-', 3, CURDATE());
