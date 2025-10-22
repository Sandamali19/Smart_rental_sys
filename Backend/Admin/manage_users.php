<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.html");
    exit();
}

if (isset($_GET['delete'])) {
    $user_id = intval($_GET['delete']);

    
    $check = $conn->query("SELECT role FROM users WHERE user_id = $user_id");
    $data = $check->fetch_assoc();
    if ($data && $data['role'] == 'admin') {
        echo "<script>alert('Cannot delete admin account!');window.location='manage_users.php';</script>";
        exit();
    }

  
    $conn->query("DELETE FROM users WHERE user_id = $user_id");
    echo "<script>alert('User deleted successfully!');window.location='manage_users.php';</script>";
    exit();
}
$result = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Manage Users</title>
<style>
body {
  font-family: Arial, sans-serif;
  margin: 0;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

.container {
  background: white;
  border-radius: 15px;
  padding: 25px;
  width: 90%;
  max-width: 1100px;
  box-shadow: 0 0 15px rgba(0,0,0,0.1);
}

h2 {
  text-align: center;
  color: #5a2dfc;
  margin-bottom: 8px;
  font-size: 26px;
}

p.subtitle {
  text-align: center;
  color: #777;
  margin-bottom: 25px;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  padding: 10px;
  text-align: center;
  border-bottom: 1px solid #ddd;
}

th {
  background-color: #7b2ff7;
  color: white;
}

tr:nth-child(even) {
  background-color: #f9f9f9;
}

a.delete {
  background: #dc3545;
  color: white;
  padding: 5px 10px;
  border-radius: 4px;
  text-decoration: none;
  font-weight: bold;
}
a.delete:hover { background: #a71d2a; }

.buttons {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-top: 20px;
}

a.back, a.new {
  background: #7b2ff7;
  color: white;
  padding: 10px 18px;
  border-radius: 6px;
  text-decoration: none;
  font-weight: bold;
  transition: 0.3s;
}
a.back:hover, a.new:hover {
  background: #5a2dfc;
}
</style>
</head>
<body>
<div class="container">
  <h2>Manage Users</h2>
  <p class="subtitle">View or remove user accounts registered on RentHub.</p>

  <table>
    <tr>
      <th>ID</th>
      <th>Username</th>
      <th>Email</th>
      <th>Role</th>
      <th>Actions</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
      <td><?= $row['user_id'] ?></td>
      <td><?= htmlspecialchars($row['username']) ?></td>
      <td><?= htmlspecialchars($row['email']) ?></td>
      <td><?= htmlspecialchars($row['role']) ?></td>
      <td>
        <?php if ($row['role'] != 'admin') { ?>
          <a href="manage_users.php?delete=<?= $row['user_id'] ?>"
             class="delete"
             onclick="return confirm('Are you sure you want to delete user <?= htmlspecialchars($row['username']) ?>?');">
             🗑 Delete
          </a>
        <?php } else { ?>
          <span style="color: gray;">(Admin)</span>
        <?php } ?>
      </td>
    </tr>
    <?php } ?>
  </table>

  <div class="buttons">
    <a href="dashboard.php" class="back">← Back to Dashboard</a>
    
  </div>
</div>
</body>
</html>

