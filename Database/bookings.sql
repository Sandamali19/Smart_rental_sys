CREATE TABLE IF NOT EXISTS bookings ( 
    booking_id INT AUTO_INCREMENT PRIMARY KEY, 
    item_id INT NOT NULL, 
    user_id INT NOT NULL, 
    start_date DATE NOT NULL, 
    end_date DATE NOT NULL, 
    total_price DECIMAL(10, 2) NOT NULL, 
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
    FOREIGN KEY (item_id) REFERENCES items(item_id) ON DELETE CASCADE, 
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE 
); 

ALTER TABLE bookings
  ADD COLUMN late_fee DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER total_price,
  ADD COLUMN is_late_paid TINYINT(1) NOT NULL DEFAULT 0 AFTER late_fee;