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

$cat_sql = "SELECT cat_id, cat_name FROM categories ORDER BY cat_name ASC";
$cat_result = $conn->query($cat_sql);

$sql = "SELECT item_id, item_name, price, image_path,`description`, location,contact_info FROM items ORDER BY posted_at DESC LIMIT 10";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentHub - Rent Anything, Anytime</title>
    <link rel="stylesheet" href="Style/index.css">
    <link rel="icon" type="image/png" href="Uploads/fav_icon.png">
    
    
</head>
<body>
   
    <header>
        <div class="header-container">
            <a href="index.php" class="logo">
            <img src="Uploads/logo.png" alt="RentHub Logo" class="logo-img">
            </a>

            <nav class="nav-links">
                <a href="index.php">Home</a>
                <a href="Frontend/post_item.php">Post Item</a>
                <a href="Backend/all_items.php">Book Item</a>
                <a href="Backend/user_profile.php">My Profile</a>
                <a href="Frontend/notifications.php">Notifications</a>
                <a href="Frontend/login.html"><button class="btn">Login</button></a>
                <a href="Frontend/signup.html"><button class="btn">Signup</button></a>
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
                    <div class="stat-number"><?php echo $item_count; ?></div>
                    <div class="stat-label">Available Items</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-number"><?php echo $user_count; ?></div>
                    <div class="stat-label">Active Users</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🏷️</div>
                    <div class="stat-number"><?php echo $cat_count; ?></div>
                    <div class="stat-label">Categories</div>
                </div>
            </div>
        </div>

        <div class="search-container">
             <form class="search-bar" action="Backend/search.php" method="get">
          <input
            type="text"
            name="item"
            placeholder="Search for items..."
            required
          />
          <button type="submit">Search</button>
        </form>

        </div>

        <div class="categories-section">
            <h2>Browse by Category</h2>
             <div class="category-buttons">
        <?php if ($cat_result && $cat_result->num_rows > 0): ?>
            <?php while ($cat = $cat_result->fetch_assoc()): ?>
                <a href="Backend/category_items.php?cat_id=<?php echo $cat['cat_id']; ?>" class="category-btn">
                    <?php echo htmlspecialchars($cat['cat_name']); ?>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No categories found.</p>
        <?php endif; ?>
    </div>
            <div class="categories">
                 <?php
        if ($cat_result && $cat_result->num_rows > 0) {
            while ($row = $cat_result->fetch_assoc()) {
                ?>
                <a href="Backend/category_items.php?cat_id=<?php echo $row['cat_id']; ?>" class="category-card">
                    <h3><?php echo htmlspecialchars($row['cat_name']); ?></h3>
                </a>
                <?php
            }
        } else {
            echo "<p>No categories found.</p>";
        }
        ?>
            </div>
        </div>

        <h2>Latest Items for Rent</h2>
    <div class="item-grid">
        <?php
    
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                ?>
                <div class="item-card">
                    <img src="<?php echo $row['image_path']; ?>" alt="<?php echo htmlspecialchars($row['item_name']); ?>" width="200">
                    <h3><?php echo htmlspecialchars($row['item_name']); ?></h3>
                    <p><b>Description:</b> <?php echo htmlspecialchars($row['description']); ?></p>
                    <p><b>Price: Rs. </b><?php echo $row['price']; ?></p>
                    <p><b>Location: </b><?php echo htmlspecialchars($row['location']); ?></p>
                    <p><b>Contact Number:</b> <?php echo htmlspecialchars($row['contact_info']); ?></p>
                    
                    <a href="Backend/book_item.php?item_id=<?php echo $row['item_id']; ?>"><button>Book Item</button></a>
                </div>
                <?php
            }
        } else {
            echo "<p>No latest items available. Post one now!</p>";
        }
        ?>
    </div>
</div>
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
                        <li><a href="index.php">Home</a></li>
                        <li><a href="Frontend/signup.html">Signup</a></li>
                        <li><a href="Frontend/login.html">Login</a></li>
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