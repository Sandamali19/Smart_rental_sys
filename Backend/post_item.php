<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/login.html");
    exit();
}
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $conn->real_escape_string($_POST['item_name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $location = $conn->real_escape_string($_POST['location']);
    $contact_info = $conn->real_escape_string($_POST['contact_info']);
    $cat_id = intval($_POST['cat_id']);
    $user_id = $_SESSION['user_id'];
    $posted_at = date('Y-m-d H:i:s');

    
    $availability_status = 'available';
    $avg_rating = 0;
    $review_count = 0;
    $view_count = 0;
    $additional_images = '';

    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $image_name = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = $target_file;
        }}
        $sql = "INSERT INTO item (
                item_name, description, price, image_path, additional_images, 
                location, contact_info, availability_status, avg_rating, 
                review_count, view_count, posted_at, user_id, cat_id
            ) VALUES (
                '$item_name', '$description', '$price', '$image_path', '$additional_images',
                '$location', '$contact_info', '$availability_status', '$avg_rating',
                '$review_count', '$view_count', '$posted_at', '$user_id', '$cat_id'
            )";

    if ($conn->query($sql) === TRUE) {
        echo "<h2>✅ Item posted successfully!</h2>";
        echo "<a href='../frontend/post_item.html'>Post another item</a>";
    } else {
        echo "❌ Error inserting: " . $conn->error;
    }}

?>