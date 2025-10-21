<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Frontend/login.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = $_POST['item_id'];
    $user_id = $_SESSION['user_id'];
    $name = $_POST['item_name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $location = $_POST['location'];
    $contact = $_POST['contact_info'];
    $status = $_POST['availability_status'];

    $sql = "UPDATE items 
            SET item_name='$name', description='$desc', price='$price', 
                location='$location', contact_info='$contact', 
                availability_status='$status'
            WHERE item_id='$item_id' AND user_id='$user_id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../Frontend/view_post.php");
        exit;
    } else {
        echo "Error updating item: " . $conn->error;
    }
}

$conn->close();
?>
