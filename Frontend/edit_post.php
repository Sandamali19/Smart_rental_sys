<?php
session_start();
include '../Backend/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit;
}

$item_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM items WHERE item_id = '$item_id' AND user_id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Item not found or not authorized.";
    exit;
}

$item = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Item</title>
    <link rel="stylesheet" href="../Style/post_item.css">
</head>
<body>

<div class="form-container">

<h2>Edit My Item</h2>

<form action="../Backend/update_post.php" method="POST">
    <input type="hidden" name="item_id" value="<?php echo $item['item_id']; ?>">

    <label>Item Name:</label>
    <input type="text" name="item_name" value="<?php echo htmlspecialchars($item['item_name']); ?>" required>

    <label>Description:</label>
    <textarea name="description" required><?php echo htmlspecialchars($item['description']); ?></textarea>

    <label>Price (Rs):</label>
    <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($item['price']); ?>" required>

    <label>Location:</label>
    <input type="text" name="location" value="<?php echo htmlspecialchars($item['location']); ?>">

    <label>Contact Info:</label>
    <input type="text" name="contact_info" value="<?php echo htmlspecialchars($item['contact_info']); ?>">

    <label>Status:</label>
    <select name="availability_status">
        <option value="available" <?php if($item['availability_status']=='available') echo 'selected'; ?>>Available</option>
        <option value="rented" <?php if($item['availability_status']=='rented') echo 'selected'; ?>>Rented</option>
        
    </select>

    <button type="submit">Update Item</button>
</form>
      <a href="../index.php" class="btn">Back to Home</a>

</div>

</body>
</html>
