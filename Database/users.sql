
CREATE TABLE IF NOT EXISTS payments ( 
    payment_id INT AUTO_INCREMENT PRIMARY KEY, 
    booking_id INT NOT NULL, 
    amount DECIMAL(10, 2) NOT NULL, 
    payment_method VARCHAR(50) NOT NULL, 
    payment_status ENUM('pending', 'completed', 'failed', 'refunded') NOT NULL DEFAULT 
    'pending', 
    transaction_id VARCHAR(255), 
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE CASCADE 
); 
alter table payments
add column payment_type varchar(50);

CREATE TABLE IF NOT EXISTS users ( 
    user_id INT AUTO_INCREMENT PRIMARY KEY, 
    username VARCHAR(50) NOT NULL UNIQUE, 
    email VARCHAR(100) NOT NULL UNIQUE, 
    password VARCHAR(255) NOT NULL, 
    phone VARCHAR(20), 
    address TEXT, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP 
); 

ALTER TABLE users
ADD COLUMN role ENUM('admin', 'user') NOT NULL DEFAULT 'user' 
after address;

UPDATE users
SET role = 'admin'
WHERE email = 'admin@gmail.com';

