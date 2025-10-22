<?php

require '../Backend/config.php';
session_start();
$uid = intval($_SESSION['user_id']);

include '../Backend/late_fee_handler.php';

$res = $conn->query("SELECT n.*, b.end_date, b.late_fee, b.is_late_paid
                     FROM notifications n
                     LEFT JOIN bookings b ON b.booking_id = n.booking_id
                     WHERE n.user_id = $uid
                     ORDER BY n.created_at DESC");
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Notifications</title>
    <link rel="stylesheet" href="../Style/notifications.css">
</head>
<body>

    <div class="notification-wrapper">
        <h2 class="notification-title">Your Notifications</h2>

        <?php if ($res->num_rows == 0): ?>
            <div class="no-notifications">
                <p>No notifications yet 🌱</p>
            </div>
        <?php else: ?>
            <?php while($row = $res->fetch_assoc()): ?>
                <div class="notification-card">
                    <p class="notification-message"><?= htmlspecialchars($row['message']) ?></p>
                    <small class="notification-date"><?= $row['created_at'] ?></small>

                    <div class="notification-actions">
                        <?php
                        $type = $row['type'];
                        $end_date = $row['end_date'];
                        $today = date('Y-m-d');

                        if (in_array($type, ['booking_confirmed', 'late_fee', 'reminder', 'late_fee_paid'])) {
                            if ($end_date) {
                                if ($today <= $end_date) {
                                    echo '<a href="../Backend/return_item.php?booking_id='.$row['booking_id'].'" class="btn return-btn">Return Item</a>';
                                } else {
                                    if (floatval($row['late_fee']) > 0 && $row['is_late_paid'] == 0) {
                                        echo '<a href="pay_late_fee.php?booking_id='.$row['booking_id'].'" class="btn pay-btn">Pay Late Fee</a>';
                                    } elseif ($row['is_late_paid'] == 1) {
                                        echo '<button class="btn paid-btn" disabled>Late Fee Paid</button>';
                                    }
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

</body>
</html>