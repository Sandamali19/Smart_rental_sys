<?php
session_start();
require_once 'config.php';

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../Frontend/login.html");
        exit();
    }

    $user_id= intval($_SESSION['user_id']);

    $user_sql = "SELECT username, email, phone, `address`,created_at 
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
    <h1>Hello, <?php echo htmlspecialchars($user['username']?? ''); ?></h1>
    <div class="section">
        <h2>My Details</h2>
        <div class="profile">
          <div class="profile-details">
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']  ?? '');  ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']  ?? '');  ?></p>
            <p><strong>Joined:</strong> <?php echo htmlspecialchars($user['created_at'] ?? ''); ?></p>
            <p><a href="edit_profile.php">Edit Profile</a></p>
          </div>
        </div>
      </div>
  <div class="section">
  <h2>My Bookings</h2>
    <?php if (count($bookings) === 0): ?>
      <p>No bookings found.</p>
      <?php else: ?>
        <table >
          <thead>
            <tr>
              <th>Item</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Total (Rs.)</th>
              <th>Status</th>
              <th>Order Date</th>
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
  <br>
  <div class="section">
    <h2>Payment History</h2>
    <?php if (count($payments) === 0): ?>
      <p>No payments made yet.</p>
      <?php else: ?>
        <table >
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
   <button class="button" onclick="window.location.href='logout.php'">Logout</button>
    <style>
      
     

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: "Poppins", Arial, sans-serif;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: #333;
  line-height: 1.6;
  min-height: 100vh;
  padding: 2rem;
}

.container {
  max-width: 1100px;
  margin: 0 auto;
  background: #fff;
  border-radius: 15px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
  padding: 2.5rem 3rem;
  animation: fade-in 0.8s ease-in-out;
}



/* ========================================= */
/* HEADER & PROFILE */
/* ========================================= */
h1 {
  text-align: center;
  color: #667eea;
  font-size: 2rem;
  margin-bottom: 2rem;
}

.profile {
  display: flex;
  align-items: flex-start;
  gap: 2rem;
  margin-bottom: 2rem;
  flex-wrap: wrap;
}

.profile img {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #667eea;
}

.profile-details {
  flex: 1;
  background: #f9f9ff;
  padding: 1.5rem;
  border-radius: 10px;
}

.profile-details p {
  margin-bottom: 0.5rem;
  font-size: 0.95rem;
}

.profile-details a {
  color: #667eea;
  text-decoration: none;
  font-weight: 600;
}

.profile-details a:hover {
  text-decoration: underline;
}

/* ========================================= */
/* SECTIONS */
/* ========================================= */
.section {
  margin-top: 2.5rem;
   overflow-x: auto;
}

.section h2 {
  color: #333;
  border-left: 6px solid #667eea;
  padding-left: 0.8rem;
  margin-bottom: 1.5rem;
}

/* ========================================= */
/* TABLES */
/* ========================================= */
table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 1.5rem;
  border-radius: 10px;
  overflow: hidden;
  background: white;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
}

th, td {
  padding: 1rem;
  text-align: left;
}

th {
  background: #667eea;
  color: white;
  text-transform: uppercase;
  font-size: 0.9rem;
  letter-spacing: 0.5px;
}

td {
  border-bottom: 1px solid #eee;
  font-size: 0.95rem;
}

tr:hover {
  background: #f8f8ff;
}

/* Status styling */
.status-pending {
  color: #e67e22;
  font-weight: 600;
}

.status-approved,
.status-success {
  color: #27ae60;
  font-weight: 600;
}

.status-cancelled {
  color: #e74c3c;
  font-weight: 600;
}

/* ========================================= */
/* BUTTON */
/* ========================================= */
.button {
  display: block;
  margin: 2rem auto 0;
  background: #667eea;
  color: white;
  padding: 0.9rem 2rem;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 1rem;
  font-weight: 600;
  transition: all 0.3s ease;
}

.button:hover {
  background: #5568d3;
  transform: translateY(-2px);
  box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
}

/* ========================================= */
/* RESPONSIVE */
/* ========================================= */
@media (max-width: 768px) {
  .container {
    padding: 1.5rem;
  }

  table th, table td {
    padding: 0.7rem;
  }

  .profile {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }

  .profile-details {
    width: 100%;
  }
}



    </style>
  </body>
</html>
