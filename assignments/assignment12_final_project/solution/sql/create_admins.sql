-- Run this if the admins table might not exist yet
CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  status ENUM('staff','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
