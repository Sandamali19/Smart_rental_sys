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

// create variables for my new HTML page to use
$price_per_day = $item['price'];
$item_name = htmlspecialchars($item['item_name']);
$image_path = htmlspecialchars($item['image_path']);
$description = nl2br(htmlspecialchars($item['description']));
$location = htmlspecialchars($item['location']);

include '../Frontend/bookItem_view.php';

?>
