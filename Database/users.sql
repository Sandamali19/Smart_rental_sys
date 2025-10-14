
CREATE DATABASE IF NOT EXISTS smart_rental_db;
USE smart_rental_db;


CREATE TABLE IF NOT EXISTS users (
    user_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    phone VARCHAR(20),
    address TEXT,
    profile_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS categories (
    cat_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    cat_name VARCHAR(50) NOT NULL UNIQUE,
    cat_description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS items (
    item_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image_path VARCHAR(255),
    additional_images TEXT,
    type ENUM('sale', 'rent') NOT NULL,
    location VARCHAR(255),
    contact_info VARCHAR(255),
    availability_status ENUM('available', 'rented', 'maintenance', 'sold') DEFAULT 'available',
    avg_rating DECIMAL(3, 2) DEFAULT 0,
    review_count INT(11) DEFAULT 0,
    view_count INT(11) DEFAULT 0,
    posted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT(11),
    cat_id INT(11),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (cat_id) REFERENCES categories(cat_id) ON DELETE SET NULL
);


CREATE TABLE IF NOT EXISTS bookings (
    booking_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    item_id INT(11) NOT NULL,
    user_id INT(11) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES items(item_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS reviews (
    review_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    item_id INT(11) NOT NULL,
    user_id INT(11) NOT NULL,
    rating INT(1) NOT NULL CHECK (rating BETWEEN 1 AND 5),
    review_text TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES items(item_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS messages (
    message_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    sender_id INT(11) NOT NULL,
    receiver_id INT(11) NOT NULL,
    item_id INT(11),
    subject VARCHAR(255) NOT NULL,
    message_text TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES items(item_id) ON DELETE SET NULL
);


CREATE TABLE IF NOT EXISTS notifications (
    notification_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('booking', 'message', 'review', 'system') DEFAULT 'system',
    related_id INT(11),
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS payments (
    payment_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    booking_id INT(11) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    payment_status ENUM('pending', 'completed', 'failed', 'refunded') NOT NULL DEFAULT 'pending',
    transaction_id VARCHAR(255),
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS item_availability (
    availability_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    item_id INT(11) NOT NULL,
    available_date DATE NOT NULL,
    is_available BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (item_id) REFERENCES items(item_id) ON DELETE CASCADE
);


INSERT IGNORE INTO users (username, email, password, role) VALUES ('admin', 'admin@admin.com', 'admin123', 'admin');


INSERT IGNORE INTO categories (cat_name, cat_description) VALUES 
('Electronics', 'Electronic devices and gadgets'),
('Books', 'Books and educational materials'),
('Furniture', 'Home and office furniture'),
('Vehicles', 'Cars, bikes, and other vehicles'),
('Properties', 'Real estate and properties'),
('Clothing', 'Fashion and apparel'),
('Sports', 'Sports equipment and gear'),
('Other', 'Miscellaneous items');


INSERT IGNORE INTO users (username, email, password, role, phone, address) VALUES 
('john_doe', 'john@example.com', 'password123', 'user', '0771234567', '123 Main St, Colombo'),
('jane_smith', 'jane@example.com', 'password123', 'user', '0772345678', '456 Oak Ave, Kandy'),
('mike_wilson', 'mike@example.com', 'password123', 'user', '0773456789', '789 Pine Rd, Galle'),
('sarah_jones', 'sarah@example.com', 'password123', 'user', '0774567890', '321 Elm St, Negombo'),
('robert_brown', 'robert@example.com', 'password123', 'user', '0775678901', '654 Maple Dr, Jaffna');


INSERT IGNORE INTO items (item_name, description, price, image_path, type, location, contact_info, user_id, cat_id) VALUES 
('MacBook Pro 2020', '13-inch MacBook Pro with M1 chip, 8GB RAM, 256GB SSD. Excellent condition, barely used.', 150000, 'https://picsum.photos/seed/macbook/400/300.jpg', 'rent', 'Colombo', '0771234567', 2, 1),
('iPhone 12 Pro', '128GB Pacific Blue iPhone 12 Pro. Like new condition with original box and accessories.', 120000, 'https://picsum.photos/seed/iphone/400/300.jpg', 'sale', 'Kandy', '0772345678', 3, 1),
('Study Table', 'Wooden study table with drawers. Perfect for students or work from home.', 8000, 'https://picsum.photos/seed/table/400/300.jpg', 'sale', 'Galle', '0773456789', 4, 3),
('Honda Civic 2018', 'Well maintained Honda Civic for rent. Automatic transmission, air conditioning, music system.', 5000, 'https://picsum.photos/seed/car/400/300.jpg', 'rent', 'Negombo', '0774567890', 5, 4),
('Programming Books Set', 'Collection of 10 programming books including JavaScript, Python, and Java.', 5000, 'https://picsum.photos/seed/books/400/300.jpg', 'sale', 'Jaffna', '0775678901', 6, 2),
('Canon DSLR Camera', 'Canon EOS 200D with 18-55mm lens. Perfect for photography enthusiasts.', 3500, 'https://picsum.photos/seed/camera/400/300.jpg', 'rent', 'Matara', '0776789012', 2, 1),
('Office Chair', 'Ergonomic office chair with lumbar support. Adjustable height and armrests.', 4500, 'https://picsum.photos/seed/chair/400/300.jpg', 'sale', 'Colombo', '0771234567', 3, 3),
('Mountain Bike', '21-speed mountain bike with suspension. Great for trails and off-road adventures.', 15000, 'https://picsum.photos/seed/bike/400/300.jpg', 'sale', 'Kandy', '0772345678', 4, 8),
('Laptop Stand', 'Adjustable aluminum laptop stand. Improves posture and reduces neck strain.', 2500, 'https://picsum.photos/seed/stand/400/300.jpg', 'sale', 'Galle', '0773456789', 5, 1),
('Tennis Racket', 'Professional grade tennis racket with carrying case. Used only a few times.', 6000, 'https://picsum.photos/seed/tennis/400/300.jpg', 'rent', 'Negombo', '0774567890', 6, 7);


INSERT IGNORE INTO bookings (item_id, user_id, start_date, end_date, total_price, status) VALUES 
(1, 3, '2023-11-01', '2023-11-07', 105000, 'confirmed'),
(2, 4, '2023-11-05', '2023-11-05', 120000, 'completed'),
(3, 5, '2023-11-10', '2023-11-10', 8000, 'pending'),
(4, 6, '2023-11-15', '2023-11-17', 10000, 'confirmed'),
(5, 2, '2023-11-20', '2023-11-20', 5000, 'completed');


INSERT IGNORE INTO reviews (item_id, user_id, rating, review_text) VALUES 
(1, 3, 5, 'Excellent laptop! Works perfectly for my development work. Owner was very helpful.'),
(2, 4, 4, 'Great phone, camera quality is amazing. Battery life is also good.'),
(3, 5, 5, 'Sturdy table, exactly as described. Delivered on time.'),
(4, 6, 4, 'Well maintained car, clean and comfortable. Good value for money.'),
(5, 2, 3, 'Books are in good condition but some have highlighting. Overall satisfied.');


INSERT IGNORE INTO messages (sender_id, receiver_id, item_id, subject, message_text) VALUES 
(3, 2, 1, 'Question about MacBook', 'Is the laptop still available for rent? I need it for next week.'),
(2, 3, 1, 'Re: Question about MacBook', 'Yes, it\'s available. When exactly do you need it?'),
(4, 5, 2, 'Price negotiation', 'Would you accept LKR 110,000 for the iPhone?'),
(5, 4, 2, 'Re: Price negotiation', 'I can do LKR 115,000. That\'s my best offer.'),
(6, 1, 3, 'Delivery options', 'Do you offer delivery for the study table? I live in Panadura.');


INSERT IGNORE INTO notifications (user_id, message, type, related_id) VALUES 
(2, 'You have a new message from John Doe about MacBook Pro', 'message', 1),
(3, 'Your booking for MacBook Pro has been confirmed', 'booking', 1),
(4, 'Your booking for iPhone 12 Pro has been completed', 'booking', 2),
(5, 'You have a new message from Sarah Jones about Study Table', 'message', 3),
(6, 'Your booking for Honda Civic has been confirmed', 'booking', 4);


UPDATE items i SET 
    avg_rating = (SELECT AVG(rating) FROM reviews WHERE item_id = i.item_id),
    review_count = (SELECT COUNT(*) FROM reviews WHERE item_id = i.item_id)
WHERE i.item_id IN (SELECT item_id FROM reviews);


UPDATE items SET view_count = FLOOR(RAND() * 100) + 10 WHERE view_count = 0;