
<?php
include 'config.php';
session_start();

// check user is logged in or not
if(!isset($_SESSION['user_id'])){
    header("Location: ../frontend/login.html");
    exit();
}

// get all items from the database
$sql = "SELECT * FROM items ORDER BY posted_at DESC";
$result = $conn->query($sql);
if(!$result){
    die("Query failed: " . $conn->error);
}

$conn->close(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Items | RentHub</title>
    <link rel="stylesheet" href="../Style/all_items.css">
</head>
<body>

   
    <form class="search-bar" action="search.php" method="get">
        <input type="text" name="item" placeholder="Search items..." required>
        <button type="submit">Search</button>
    </form>

    <h2>All Items</h2>
    <div class="item-grid">
    <?php
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                ?>
                <div class="item-card">
                    <img src="<?php echo $row['image_path']; ?>" alt="<?php echo htmlspecialchars($row['item_name']); ?>" width="200">
                    <h3><?php echo htmlspecialchars($row['item_name']); ?></h3>
                    <p><b>Description:</b> <?php echo htmlspecialchars($row['description']); ?></p>
                    <p><b>Price:</b> Rs. <?php echo $row['price']; ?></p>
                    <p><b>Location:</b> <?php echo htmlspecialchars($row['location']); ?></p>
                    <p><b>Contact Number:</b><?php echo htmlspecialchars($row['contact_info']); ?></p>
                    
                    <a href="book_item.php?item_id=<?php echo $row['item_id']; ?>"><button>Book Item</button></a>
                </div>
                <?php
            }
        } else {
            echo "<p>No items found.</p>";
        }
        ?>
    </div>

</body>
</html>

