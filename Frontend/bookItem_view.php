<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Book Item - <?php echo $item_name ?></title>
    <link rel="stylesheet" href="../Style/bookItem_view.css" />
  </head>
  <body>
    <div class="booking-container">
      <h1><?php echo $item_name ?></h1>
      <img
        src="<?php echo $image_path ?>"
        alt="Item Image"
        class="item-image"
      />

      <p>
        <strong>Description:</strong>
        <?php echo $description ?>
      </p>
      <p>
        <strong>Price per day:</strong> Rs.
        <?php echo number_format($price_per_day, 2) ?>
      </p>
      <p>
        <strong>Location:</strong>
        <?php echo $location ?>
      </p>

      <form action="../Backend/payment_logic.php" method="POST">
        <input type="hidden" name="item_id" value="<?php echo $item_id ?>" />
        <input
          type="hidden"
          name="price_per_day"
          value="<?php echo $price_per_day ?>"
        />
        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" required /><br><br>

        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" required /><br>

        <button type="submit" name="confirm_booking" class="book-btn">
          Confirm Booking
        </button>
      </form>
    </div>
  </body>
</html>