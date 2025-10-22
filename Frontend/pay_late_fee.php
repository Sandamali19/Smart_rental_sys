<?php
require '../Backend/config.php';
session_start();

$user_id = intval($_SESSION['user_id']);
$booking_id = intval($_GET['booking_id'] ?? 0);

$booking = $conn->query("SELECT * FROM bookings WHERE booking_id = $booking_id AND user_id = $user_id")->fetch_assoc();
if(!$booking) die("Booking not found.");

// get late fee details
$PER_DAY_FEE = 500.00;
$end = $booking['end_date'];
$days_late = intval((strtotime(date('Y-m-d')) - strtotime($end))/86400);
if($days_late < 0) $days_late = 0;

$late_amount = $days_late * $PER_DAY_FEE;
$service_charge = round($late_amount * 0.03, 2);
$total_due = round($late_amount + $service_charge, 2);

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Pay Late Fee</title>
<link rel="stylesheet" href="../Style/pay_late_fee.css">
</head>
<body>

<main>
  <div class="latefee-wrapper">
    <div class="latefee-container">
      <h2>Pay Late Fee for <?= htmlspecialchars($username) ?></h2>
      <div class="latefee-details">
        <p><strong>Days late:</strong> <?= $days_late ?></p>
        <p><strong>Late amount (<?= $PER_DAY_FEE ?> per day):</strong> Rs. <?= number_format($late_amount,2) ?></p>
        <p><strong>Service charge (3%):</strong> Rs. <?= number_format($service_charge,2) ?></p>
        <h3>Total due: Rs. <?= number_format($total_due,2) ?></h3>
      </div>

      <form class="latefee-form" method="post" action="../Backend/payment_logic.php">
        <input type="hidden" name="booking_id" value="<?= $booking_id ?>">
        <input type="hidden" name="payment_type" value="late_fee">
        <input type="hidden" name="amount" value="<?= $total_due ?>">

        <label>Card number:
          <input name="card_number" required placeholder="1234 5678 9012 3456">
        </label>

        <button type="submit" class="pay-button">Pay Rs. <?= number_format($total_due,2) ?></button>
      </form>
    </div>
  </div>
</main>

</body>
</html>
