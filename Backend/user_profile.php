<?php
session_start();
require_once 'config.php';


    $user_id=$_SESSION['user_id'];

    $user_sql = "SELECT username, email, phone, address, profile_image, created_at 
                FROM users WHERE user_id = ?";
    $user_stmt = $conn->prepare($user_sql);
    $user_stmt->bind_param("i", $user_id);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    $user = $user_result->fetch_assoc();
    $user_stmt->close();

    $bookings_sql = "SELECT b.booking_id, b.start_date, b.end_date, b.total_price, b.status, b.created_at,
                     i.item_name, i.image_path, i.location
                    FROM bookings b
                    JOIN items i ON b.item_id = i.item_id
                    WHERE b.user_id = ?
                    ORDER BY b.created_at DESC";
      $book_stmt = $conn->prepare($bookings_sql);
      $book_stmt->bind_param("i", $user_id);
      $book_stmt->execute();
      $bookings = $book_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
      $book_stmt->close();

    $pay_sql = "SELECT p.payment_id, p.amount, p.payment_method, p.payment_status, p.transaction_id, p.payment_date,
                b.booking_id
                FROM payments p
                JOIN bookings b ON p.booking_id = b.booking_id
                WHERE b.user_id = ?
                ORDER BY p.payment_date DESC";
      $pay_stmt = $conn->prepare($pay_sql);
      $pay_stmt->bind_param("i", $user_id);
      $pay_stmt->execute();
      $payments = $pay_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
      $pay_stmt->close();

?>
<html> 
<body>
  <div class="container">
    <h1>Hello, <?php echo htmlspecialchars($user['username']); ?></h1>
    <div class="profile">
      <img src=" " alt="Profile Picture">
       <div class="profile-details">
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
        <p><strong>Joined:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
        <p><a href=" ">Edit Profile</a></p>
    </div>
  </div>

  <div class="section">
    <h2>My Bookings</h2>
    <?php if (count($bookings) === 0): ?>
      <p>No bookings found.</p>
      <?php else: ?>
        <table border=1>
          <thead>
            <tr>
              <th>Item</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Total (Rs.)</th>
              <th>Status</th>
              <th>Created</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($bookings as $b): ?>
            <tr>
              <td><?php echo htmlspecialchars($b['item_name']); ?></td>
              <td><?php echo htmlspecialchars($b['start_date']); ?></td>
              <td><?php echo htmlspecialchars($b['end_date']); ?></td>
              <td><?php echo htmlspecialchars($b['total_price']); ?></td>
              <td class="status-<?php echo htmlspecialchars($b['status']); ?>"><?php echo ucfirst($b['status']); ?></td>
              <td><?php echo htmlspecialchars($b['created_at']); ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
         <?php endif; ?>
  </div>

  <div class="section">
    <h2>Payment History</h2>
    <?php if (count($payments) === 0): ?>
      <p>No payments made yet.</p>
      <?php else: ?>
        <table border=2>
          <thead>
            <tr>
              <th>Booking ID</th>
              <th>Amount (Rs.)</th>
              <th>Method</th>
              <th>Status</th>
              <th>Transaction ID</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($payments as $p): ?>
              <tr>
                <td><?php echo htmlspecialchars($p['booking_id']); ?></td>
                <td><?php echo htmlspecialchars($p['amount']); ?></td>
                <td><?php echo htmlspecialchars($p['payment_method']); ?></td>
                <td><?php echo htmlspecialchars($p['payment_status']); ?></td>
                <td><?php echo htmlspecialchars($p['transaction_id']); ?></td>
                <td><?php echo htmlspecialchars($p['payment_date']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php endif; ?>
  </div>
   <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
</body>
</html>
