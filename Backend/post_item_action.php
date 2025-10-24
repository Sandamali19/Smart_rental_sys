<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Frontend/login.html");
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
    date_default_timezone_set('Asia/Colombo');
    $posted_at = date('Y-m-d H:i:s');

    
    $availability_status = 'available';
    

    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../Uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $image_name = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = $target_file;
        }}
        $sql = "INSERT INTO items (
                item_name, description, price, image_path, 
                location, contact_info, posted_at, user_id, cat_id,availability_status
            ) VALUES (
                '$item_name', '$description', '$price', '$image_path',
                '$location', '$contact_info', '$posted_at', '$user_id', '$cat_id','$availability_status'
            )";

    if ($conn->query($sql) === TRUE) {
        // Redirect to success.html after insertion
        header("Location: ../Frontend/success.html");
        exit();
    } else {
        echo "❌ Error inserting: " . $conn->error;
    }
}

?>