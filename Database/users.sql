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
