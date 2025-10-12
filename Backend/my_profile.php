<?php
session_start();
include '../backend/config.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];


$sql = "SELECT * FROM users WHERE id='$user_id' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found!";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile</title>
  <link rel="stylesheet" href="../frontend/style.css">
</head>
<body>
  <div class="form-container">
    <h2>My Profile</h2>
    <div class="profile-card">
      <img src="https://via.placeholder.com/100" alt="Profile Picture" class="profile-pic">
      <h3><?php echo htmlspecialchars($user['name']); ?></h3>
      <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
      <p>Phone: <?php echo htmlspecialchars($user['phone']); ?></p>
      <a href="logout.php" class="btn">Logout</a>
    </div>
  </div>
</body>
</html>
