<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Frontend/login.html");
    exit;
}

if (isset($_GET['id'])) {
    $item_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM items WHERE item_id='$item_id' AND user_id='$user_id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../Frontend/view_post.php");
        exit;
    } else {
        echo "Error deleting item: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
