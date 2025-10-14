
<?php
include 'db_connect.php';


if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<h3 style='color:red;text-align:center;'>No item selected!</h3>");
}


$item_id = intval($_GET['id']);


$sql = "SELECT i.*, u.username, c.cat_name 
        FROM items i
        JOIN users u ON i.user_id = u.user_id
        JOIN categories c ON i.cat_id = c.cat_id
        WHERE i.item_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("<h3 style='color:red;text-align:center;'>Item not found!</h3>");
}

$item = $result->fetch_assoc();
?>


<?php include '../Frontend/view_item.html';
 ?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>View Item</title>
  <link rel="stylesheet" type="text/css" href="../Style/view_item.css">
</head>


</html>
