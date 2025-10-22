<!DOCTYPE html>
<html>
<head>
    <title>My Items</title>
    <link rel="stylesheet" href="../Style/post_item.css">
</head>
<body>



<div class="items-container">
    <h2>My Posted Items</h2><br>
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

include '../Backend/config.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM items WHERE user_id = '$user_id' ORDER BY posted_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='item-card'>";
        if (!empty($row['image_path'])) {
            echo "<img src='../Uploads/" . htmlspecialchars($row['image_path']) . "' alt='Item Image'>";
        }
        echo "<h3>" . htmlspecialchars($row['item_name']) . "</h3>";
        echo "<p><b>Description:</b> " . htmlspecialchars($row['description']) . "</p>";
        echo "<p><b>Price:</b> Rs. " . htmlspecialchars($row['price']) . "</p>";
        echo "<p><b>Status:</b> " . htmlspecialchars($row['availability_status']) . "</p>";
        echo "<a href='edit_post.php?id=" . $row['item_id'] . "' class='button edit'>Edit</a> ";
        echo "<a href='../Backend/delete_post.php?id=" . $row['item_id'] . "' class='button delete' onclick='return confirm(\"Are you sure to delete this item?\")'>Delete</a>";
        echo "</div>";
    }
} else {
    echo "<p>No items posted yet.</p>";
}
?>
      <a href="../index.php" class="btn">Back to Home</a>

</div>


</body>
</html>
