<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Item Posted Successfully</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="form-container">
    <h2>✅ Item Posted Successfully!</h2>

    <p>
      <?php
        if (isset($_POST['item_name'])) {
          $item_name = htmlspecialchars($_POST['item_name']);
          echo "Your item <strong>$item_name</strong> has been successfully added to the rental list.";
        } else {
          echo "Your item has been successfully added to the rental list.";
        }
      ?>
    </p>

    <div class="button-group">
      <a href="index.html" class="btn">Back to Home</a>
      <a href="post_item.html" class="btn">Post Another Item</a>
    </div>
  </div>
</body>
</html>