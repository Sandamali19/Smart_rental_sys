<?php
include 'Backend/config.php';

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
?>
