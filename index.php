<?php
include 'Backend/config.php';
session_start();

$item_sql = "SELECT COUNT(*) AS total_items FROM items WHERE availability_status = 'available'";
$item_result = $conn->query($item_sql);
$item_count = ($item_result->num_rows > 0) ? $item_result->fetch_assoc()['total_items'] : 0;

$user_sql = "SELECT COUNT(*) AS total_users FROM users";
$user_result = $conn->query($user_sql);
$user_count = ($user_result && $user_result->num_rows > 0) ? $user_result->fetch_assoc()['total_users'] : 0;

$cat_sql = "SELECT COUNT(*) AS total_cats FROM categories";
$cat_result = $conn->query($cat_sql);
$cat_count = ($cat_result->num_rows > 0) ? $cat_result->fetch_assoc()['total_cats'] : 0;

$sql = "SELECT item_id, item_name, price, image_path,`description`, location FROM items ORDER BY posted_at DESC LIMIT 10";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentHub - Rent Anything, Anytime</title>
    <link rel="stylesheet" href="../style/index.css">
    
    
</head>
<body>
   
    <header>
        <div class="header-container">
            <a href="#" class="logo">🏠 RentHub</a>
            <nav class="nav-links">
                <a href="index.html">Home</a>
                <a href="Frontend/post_item.html">Post Item</a>
                <a href="book_item.php">Book Item</a>
                <a href="">My Profile</a>
                <a href="Frontend/login.html"><button class="category-btn">Login</button></a>
                <a href="Frontend/signup.html"><button class="category-btn">Signup</button></a>
            </nav>
        </div>
    </header>

   
    <main>
        <div class="welcome-section">
            <h1>Welcome to RentHub!</h1>
            <p>Your trusted platform for renting and selling items safely and conveniently.</p>
            
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-icon">📦</div>
                    <div class="stat-number"></div>
                    <div class="stat-label">Available Items</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-number"></div>
                    <div class="stat-label">Active Users</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🏷️</div>
                    <div class="stat-number"></div>
                    <div class="stat-label">Categories</div>
                </div>
            </div>
        </div>

        <div class="search-container">
            <div class="search-bar">
                <input type="text" placeholder="Search for items to rent...">
                <button>Search</button>
            </div>
        </div>

        <div class="categories-section">
            <h2>Browse by Category</h2>
            <div class="categories">
                 <?php
                if ($cat_result && $cat_result->num_rows > 0) {
                    while ($row = $cat_result->fetch_assoc()) {
                    echo '
                    <div class="category-card" href="category_items.php?cat_id=' . $row['cat_id'] . '" class="view-btn">
                    <h3>' . htmlspecialchars($row['cat_name']) . '</h3>
                    
                    </div>';
                }
                } else {
                echo "<p>No categories found.</p>";
                }
                ?>
            </div>
        </div>

        <div class="item-grid">
<?php


if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        ?>
        <div class="item-card">
            <img src="<?php echo $row['image_path']; ?>" alt="<?php echo $row['item_name']; ?>" />
            <h3><?php echo $row['item_name']; ?></h3>
            <p>Price: Rs. <?php echo $row['price']; ?></p>
            <p>Location: <?php echo $row['location']; ?></p>
            <a href="Book_item.php?id=<?php echo $row['item_id']; ?>"><button>Book item</button></a>
        </div>
        <?php
    }
} else {
    echo "<p>No items available.</p>";
}
?>
</div>

        <div class="how-it-works">
            <h2>How It Works</h2>
            <div class="steps-container">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h3>Sign Up</h3>
                    <p>Create your free account in seconds</p>
                </div>
                <div class="step-card">
                    <div class="step-number">2</div>
                    <h3>Browse Items</h3>
                    <p>Find what you need from our wide selection</p>
                </div>
                <div class="step-card">
                    <div class="step-number">3</div>
                    <h3>Book or Buy</h3>
                    <p>Reserve items or purchase directly</p>
                </div>
                <div class="step-card">
                    <div class="step-number">4</div>
                    <h3>Enjoy</h3>
                    <p>Get your item and enjoy the experience</p>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About RentHub</h3>
                    <p>Your trusted platform for renting and selling items safely and conveniently.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="">Home</a></li>
                        <li><a href="">Search</a></li>
                        <li><a href="">Signup</a></li>
                        <li><a href=" ">Login</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact Us</h3>
                    <p>Email: info@renthub.com</p>
                    <p>Phone: +94 123 456 789</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 RentHub Rental System. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>