CREATE TABLE IF NOT EXISTS items ( 
    item_id INT AUTO_INCREMENT PRIMARY KEY, 
    item_name VARCHAR(100) NOT NULL, 
    description TEXT, 
    price DECIMAL(10, 2) NOT NULL, 
    image_path VARCHAR(255), 
    location VARCHAR(255), 
    contact_info VARCHAR(255), 
    posted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    user_id INT, 
    cat_id INT, 
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE, 
    FOREIGN KEY (cat_id) REFERENCES categories(cat_id) ON DELETE SET NULL 
); 

alter table items 
add column availability_status ENUM('available', 'rented', 'maintenance', 'sold') DEFAULT 'available';

ALTER TABLE items 
MODIFY posted_at TIMESTAMP NULL;