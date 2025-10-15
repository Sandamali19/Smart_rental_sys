<?php
session_start();

//no payment data will redirect
if (!isset($_SESSION['payment_data'])) {
    header("Location: ../frontend/all_items.php");
    exit();
}

$data = $_SESSION['payment_data'];
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Payment</title>
    <link rel="stylesheet" href="../styles/payment.css" />
  </head>
  <body>
    <div class="payment-card">
      <h2>Confirm Payment</h2>

      <div class="summary">
        <p>
          <b>Days:</b> <span><?= $data['days']; ?></span>
        </p>
        <p>
          <b>Price/Day:</b>
          <span
            >Rs.
            <?= number_format($data['price_per_day'], 2); ?></span
          >
        </p>
        <p>
          <b>Base Price:</b>
          <span
            >Rs.
            <?= number_format($data['base_price'], 2); ?></span
          >
        </p>
        <p>
          <b>Service Charge (3%):</b>
          <span
            >Rs.
            <?= number_format($data['service_charge'], 2); ?></span
          >
        </p>
        <hr />
        <p>
          <b>Total Payment:</b>
          <span
            >Rs.
            <?= number_format($data['total_payment'], 2); ?></span
          >
        </p>
      </div>

      <h3>Enter Card Details</h3>
      
      <form action="../backend/process_payment.php" method="POST">
        <label>Card Number</label>
        <input type="text" name="card_number" required /><br><br>

        <label>Card Holder Name</label>
        <input type="text" name="card_name" required /><br><br>

        <label>Expiry Date</label>
        <input type="month" name="expiry_date" required /><br><br>

        <label>CVV</label>
        <input type="password" name="cvv" maxlength="3" required /><br>

        <h3>Enter Personal Details</h3>

        <label>Phone Number</label>
        <input type="text" name="phone" required /><br><br>

        <label>Address</label>
        <textarea name="address" rows="3" placeholder="Enter your address" required></textarea><br><br>

        <button type="submit" name="pay_now">
          Pay Rs.
          <?php echo number_format($data['total_payment'], 2); ?><br>

        </button>

      </form>
    </div>
  </body>
</html>
