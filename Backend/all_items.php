
<?php
include 'config.php';
session_start();

// check user is logged in or not
if(!isset($_SESSION['user_id'])){
    header("Location: ../frontend/login.html");
    exit();
}

// get all items from the database
$sql = "SELECT * FROM items ORDER BY posted_at DESC";
$result = $conn->query($sql);
if(!$result){
    die("Query failed: " . $conn->error);
}

$conn->close(); 
?>