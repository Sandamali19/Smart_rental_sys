<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.html");
    exit();
}

if (isset($_GET['delete'])) {
    $item_id = intval($_GET['delete']);
    $conn->query("DELETE FROM items WHERE item_id = $item_id");

    echo "<script>alert('Item deleted successfully!');window.location='manage_items.php';</script>";
    exit();
}

$res = $conn->query("
    SELECT i.item_id, i.item_name, i.price, i.availability_status, i.posted_at,
           c.cat_name, u.username
    FROM items i
    LEFT JOIN categories c ON i.cat_id = c.cat_id
    LEFT JOIN users u ON i.user_id = u.user_id
    ORDER BY i.posted_at DESC
");
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Manage Items | RentHub</title>
<style>

body {
  font-family: 'Segoe UI', Arial, sans-serif;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  margin: 0;
  padding: 0;
  color: #333;
}

.container {
  width: 90%;
  margin: 50px auto;
  background: #fff;
  padding: 30px;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.header {
  text-align: center;
  margin-bottom: 25px;
}
.header h1 {
  font-size: 28px;
  color: #5a00b5;
  margin-bottom: 5px;
}
.header p {
  color: #666;
  margin: 0;
}

.action-buttons {
  text-align: center;
  margin-bottom: 25px;
}
a.button {
  display: inline-block;
  background: linear-gradient(90deg, #6a11cb, #2575fc);
  color: white;
  padding: 10px 20px;
  border-radius: 8px;
  text-decoration: none;
  margin: 5px;
  transition: 0.3s ease;
}
a.button:hover {
  opacity: 0.85;
}
a.delete {
  background: #e74c3c;
  padding: 6px 12px;
  border-radius: 5px;
  color: white;
  text-decoration: none;
  transition: 0.3s;
}
a.delete:hover {
  background: #c0392b;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  background: #fff;
  border-radius: 10px;
  overflow: hidden;
}
th {
  background: #7b2ff7;
  color: white;
  padding: 12px;
}
td {
  padding: 10px;
  border-bottom: 1px solid #eee;
  text-align: center;
}
tr:hover {
  background: #f8f8ff;
}
</style>
</head>
<body>

<div class="container">
  <div class="header">
    <h1>Manage Items</h1>
    <p>View or remove items posted on RentHub.</p>
  </div>

  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Category</th>
      <th>Price (LKR)</th>
      <th>Status</th>
      <th>Owner</th>
      <th>Posted</th>
      <th>Actions</th>
    </tr>

    <?php if ($res && $res->num_rows > 0): ?>
      <?php while($r = $res->fetch_assoc()): ?>
        <tr>
          <td><?= $r['item_id'] ?></td>
          <td><?= htmlspecialchars($r['item_name']) ?></td>
          <td><?= htmlspecialchars($r['cat_name']) ?></td>
          <td><?= number_format($r['price'],2) ?></td>
          <td><?= $r['availability_status'] ?></td>
          <td><?= htmlspecialchars($r['username']) ?></td>
          <td><?= $r['posted_at'] ?></td>
          <td>
            <a href="manage_items.php?delete=<?= $r['item_id'] ?>"
               class="delete"
               onclick="return confirm('Are you sure you want to delete <?= htmlspecialchars($r['item_name']) ?>?');">
               🗑 Delete
            </a>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="8">No items found.</td></tr>
    <?php endif; ?>
  </table>
  
  <div class="action-buttons">
    <a href="dashboard.php" class="button">← Back to Dashboard</a>
    <a href="../../Frontend/post_item.php" class="button">+ Post New Item</a>
  </div>
</div>
</div>

</body>
</html>

