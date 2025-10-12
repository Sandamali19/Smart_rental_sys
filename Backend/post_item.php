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
}

?>