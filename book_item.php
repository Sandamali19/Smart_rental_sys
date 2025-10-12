<?php
include 'config.php';

if (!isset($_GET['item_id'])) {
    die("No item selected.");
}

$item_id = intval($_GET['item_id']);
$query = "SELECT * FROM items WHERE item_id = $item_id";
$result = $conn->query($query);
$item = $result->fetch_assoc();

if (!$item) {
    die("Item not found.");
}

$price_per_day = $item['price'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Item - <?php echo htmlspecialchars($item['item_name']); ?></title>
    <link rel="stylesheet" href="style/book_item.css">
</head>
<body>
    <div class="booking-container">
        <h1><?php echo htmlspecialchars($item['item_name']); ?></h1>
        <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="Item Image" class="item-image">

        <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
        <p><strong>Price per day:</strong> Rs. <?php echo number_format($price_per_day, 2); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($item['location']); ?></p>

        <!-- Submit directly to payment.php (same folder) -->
        <form action="payment.php" method="POST">
            <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
            <input type="hidden" name="price_per_day" value="<?php echo $price_per_day; ?>">

            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" required>

            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" required>

            <label for="notes">Additional Notes:</label>
            <textarea name="notes" placeholder="Any message for the owner..."></textarea>

            <button type="submit" name="confirm_booking" class="book-btn">Confirm Booking</button>
        </form>
    </div>
</body>
</html>
