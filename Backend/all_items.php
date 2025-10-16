
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Items | RentHub</title>
    <link rel="stylesheet" href="styles/all_items.css">
</head>
<body>

   
    <form class="search-bar" action="search.php" method="get">
        <input type="text" name="item" placeholder="Search items..." required>
        <button type="submit">Search</button>
    </form>

    <h2>All Items</h2>
</body>
    </html>