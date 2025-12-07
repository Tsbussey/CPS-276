CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  status ENUM('staff','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- seed admin (change password after testing)
INSERT INTO admins (name,email,password,status) VALUES
('Demo Admin','admin@example.com','$2y$10$e4p7xg3M1I8mCLk0o9k1yOx0lJ1.2w3q6F4w0o8i8r0tQkq8TQm1S','admin');
-- Password: Password123!
