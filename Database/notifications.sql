CREATE TABLE IF NOT EXISTS notifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  booking_id INT DEFAULT NULL,
  message TEXT NOT NULL,
  type VARCHAR(50) DEFAULT 'info', -- booking_confirmed, late_fee, reminder, late_fee_paid
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);