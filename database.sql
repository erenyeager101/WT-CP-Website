-- 1. Create the Database
CREATE DATABASE IF NOT EXISTS shantai;
USE shantai;

-- 2. Create `users` Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. Create `services` Table
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    fixed_charge DECIMAL(10,2) NOT NULL
) ENGINE=InnoDB;

-- 4. Create `bookings` Table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    service_id INT NOT NULL,
    event_date DATE NOT NULL,
    event_time TIME NOT NULL,
    decoration ENUM('None', 'Basic', 'Premium') DEFAULT 'None',
    lighting ENUM('None', 'Standard', 'Advanced') DEFAULT 'None',
    other_requests TEXT,
    total_amount DECIMAL(10,2),
    payment_status ENUM('Pending', 'Completed') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    UNIQUE KEY unique_service_date (service_id, event_date)
) ENGINE=InnoDB;

-- 5. Create `payments` Table
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    payment_method VARCHAR(50),
    amount DECIMAL(10,2),
    payment_status ENUM('Pending', 'Completed') DEFAULT 'Pending',
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    image_path VARCHAR(255) NULL,  -- New column for image path
    rating TINYINT(1) NULL,        -- New column for rating (range 1-5)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE availability (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    admin_id INT(11),
    FOREIGN KEY (admin_id) REFERENCES users(id)
);

-- 7. Insert Initial Data into `services` Table
INSERT INTO services (name, description, fixed_charge) VALUES
('Banquet Hall', 'Elegant and spacious halls for grand occasions.', 20000.00),
('Lawn', 'Beautifully landscaped lawns for outdoor events.', 15000.00),
('Lodge', 'Comfortable accommodation for your guests.', 10000.00)
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- 8. Insert an Admin User into `users` Table
-- **Important:** Replace the password hash with a secure hash generated using PHP's `password_hash()` function.

INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@shantai.com', '$2y$10$e0NR0r1j0U8y3R5zG1jEHeQi7rVZCjK1XkA0pK3JjG8Jz8QO1H1Aa', 'admin')
ON DUPLICATE KEY UPDATE username = VALUES(username), email = VALUES(email), password = VALUES(password), role = VALUES(role);

-- 9. Insert Sample Users into `users` Table (Optional)
INSERT INTO users (username, email, password) VALUES
('john_doe', 'john@example.com', '$2y$10$abcdefghijklmnopqrstuv'), -- Replace with actual password hashes
('jane_smith', 'jane@example.com', '$2y$10$1234567890abcdefghijk')
ON DUPLICATE KEY UPDATE username = VALUES(username), email = VALUES(email), password = VALUES(password);

-- 10. Insert Sample Bookings into `bookings` Table (Optional)
-- Ensure that user_id and service_id correspond to existing users and services.

INSERT INTO bookings (user_id, service_id, event_date, event_time, decoration, lighting, other_requests, total_amount, payment_status) VALUES
(2, 1, '2024-12-25', '18:00:00', 'Premium', 'Advanced', 'Need extra chairs and tables.', 35000.00, 'Pending'),
(3, 2, '2024-11-15', '12:00:00', 'Basic', 'Standard', 'Set up a stage for performances.', 20000.00, 'Completed')
ON DUPLICATE KEY UPDATE event_date = VALUES(event_date);

-- 11. Insert Sample Payments into `payments` Table (Optional)
INSERT INTO payments (booking_id, payment_method, amount, payment_status) VALUES
(2, 'Credit Card', 35000.00, 'Pending'),
(3, 'PayPal', 20000.00, 'Completed')
ON DUPLICATE KEY UPDATE payment_method = VALUES(payment_method), amount = VALUES(amount), payment_status = VALUES(payment_status);

-- 12. Insert Sample Comments into `comments` Table (Optional)
INSERT INTO comments (user_id, comment) VALUES
(2, 'Had a wonderful experience booking the Banquet Hall. Highly recommended!'),
(3, 'The Lawn setup was perfect for our outdoor wedding.')
ON DUPLICATE KEY UPDATE comment = VALUES(comment);
