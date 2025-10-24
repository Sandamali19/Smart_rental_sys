
<?php
include("config.php");

// serach item by their name
$searchTerm = "";
if(isset($_GET['item']) && !empty($_GET['item'])){
    $searchTerm = $conn->real_escape_string($_GET['item']);
    $sql = "SELECT * FROM items WHERE item_name LIKE '%$searchTerm%' ORDER BY posted_at DESC";
} else {
    $sql = "SELECT * FROM items ORDER BY posted_at DESC";
}

$result = $conn->query($sql);
if(!$result){
    die("Query failed: " . $conn->error);
}

$conn->close();
?>

<html>
<head>
    <title>Search Results</title>
    <link rel="stylesheet" href="../style/all_items.css">
    <h2 style="color:white";><?php echo $searchTerm ? "Search Results for '$searchTerm'" : "All Items"; ?></h2><br>
    
    <div class="item-grid">
        <?php
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                ?>
                <div class="item-card">
                    <img src="<?php echo $row['image_path']; ?>" alt="<?php echo $row['item_name']; ?>" width="200">
                    <h3><?php echo $row['item_name']; ?></h3>
                    <p>Description: <?php echo htmlspecialchars($row['description']); ?></p>
                    <p>Price: Rs. <?php echo $row['price']; ?></p>
                    <p>Location: <?php echo $row['location']; ?></p>
                    <p>Contact Number:<?php echo htmlspecialchars($row['contact_info']); ?></p>
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