CREATE TABLE IF NOT EXISTS item_availability ( 
    availability_id INT AUTO_INCREMENT PRIMARY KEY, 
    item_id INT NOT NULL, 
    available_date DATE NOT NULL, 
    is_available BOOLEAN DEFAULT TRUE, 
    FOREIGN KEY (item_id) REFERENCES items(item_id) ON DELETE CASCADE
);