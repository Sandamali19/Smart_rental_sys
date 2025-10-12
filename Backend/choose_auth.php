<?php
session_start();


if (isset($_SESSION['user_id'])) {
    header("Location: ../frontend/post_item.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign In or Sign Up</title>
  <link rel="stylesheet" href="../frontend/style.css">
</head>
<body>
  <div class="form-container">
    <h2>Post Your Item</h2>
    <p>You need an account before posting an item. Please choose:</p>

    <div class="btn-group">
      <a href="../frontend/login.html" class="btn">Sign In</a>
      <a href="../frontend/signup.html" class="btn">Sign Up</a>
    </div>
  </div>
</body>
</html>