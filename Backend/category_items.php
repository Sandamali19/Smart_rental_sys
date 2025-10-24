<?php
include 'config.php';
session_start();

if (!isset($_GET['cat_id'])) {
    die("Category not specified.");
}

$cat_id = intval($_GET['cat_id']);

$cat_sql = "SELECT cat_name FROM categories WHERE cat_id = $cat_id";
$cat_result = $conn->query($cat_sql);
$cat_name = ($cat_result->num_rows > 0) ? $cat_result->fetch_assoc()['cat_name'] : "Unknown Category";

$item_sql = "SELECT item_id, item_name, price, image_path, location 
             FROM items 
             WHERE cat_id = $cat_id AND availability_status = 'available'";
$item_result = $conn->query($item_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($cat_name); ?> - RentHub</title>
    <link rel="stylesheet" href="../Style/all_items.css">
</head>
<body>
 
    <main>
        <h1>Items in <?php echo htmlspecialchars($cat_name); ?></h1>

        <div class="item-grid">
            <?php
            if ($item_result->num_rows > 0) {
                while($row = $item_result->fetch_assoc()) {
                    ?>
                    <div class="item-card">
                        <img src="<?php echo $row['image_path']; ?>" alt="<?php echo $row['item_name']; ?>" />
                        <h3><?php echo $row['item_name']; ?></h3>
                        <p>Price: Rs. <?php echo $row['price']; ?></p>
                        <p>Location: <?php echo $row['location']; ?></p>
                        <a href="Book_item.php?item_id=<?php echo $row['item_id']; ?>"><button>Book item</button></a>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No items found in this category.</p>";
            }
            ?>
        </div>
    </main>

 
</body>
</html>

<?php
$conn->close();
?>