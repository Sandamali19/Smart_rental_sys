<?php
session_start();

//no payment data will redirect
if (!isset($_SESSION['payment_data'])) {
    header("Location: ../Backend/all_items.php");
    exit();
}

$data = $_SESSION['payment_data'];
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Payment</title>
    <link rel="stylesheet" href="../Style/payment_view.css" />
  </head>
  <body>
    
    <style>
    body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);}

    .same-row{
      display: flex; 
      gap: 10px;
    }
    .details1{
      flex:1
    }
    .details2{
      flex:0.4
    }
    .summary{
      background: #ece8ff;
    }
    .payment-card{
      max-width: 600px;
    }
    .total p{
      font-size: 1.4em;
      
    }
    .payment-card h1 {
      text-align: center;
      color: #667eea;
      margin-bottom: 1.5rem;
    }
    .payment-card h3 {
     text-align:left;
    }

    </style>

    <div class="payment-card">
      <h1>Confirm Payment</h1>

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
        <div class="total">
          <p>
            <b>Total Payment:</b>
            <span 
              >Rs.
              <?= number_format($data['total_payment'], 2); ?></span
            >
          </p>
        </div>
      </div>

      <h3>Enter Card Details</h3>
      
      <form action="../Backend/process_payment.php" method="POST">
        <label>Card Number</label>
        <input type="text" name="card_number" required placeholder="Ex:  4693 2156 1254 4596" /><br><br>

        <label>Card Holder Name</label>
        <input type="text" name="card_name" required placeholder="Ex:  K.G.K.P.Bandara"/><br><br>

        <div class="same-row" >
          <div class="details1" >
          <label>Expiry Date</label>
          <input type="month" name="expiry_date" required /><br><br>
          </div>

          <div  class="details2">
          <label>CVV</label>
          <input type="password" name="cvv" maxlength="3" required  placeholder="910"/><br>
          </div>
        </div>

        <h3>Enter Personal Details</h3>

        <label>Phone Number</label>
        <input type="text" name="phone" required placeholder="Ex:076 7380531" /><br><br>

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
