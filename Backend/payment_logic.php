<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in.");
}

$user_id = intval($_SESSION['user_id']); 

// check normal or late fee payment
$payment_type = $_POST['payment_type'] ?? 'initial';

if ($payment_type === 'late_fee') {
    $booking_id = intval($_POST['booking_id'] ?? 0);
    $amount = floatval($_POST['amount'] ?? 0);

    $booking = $conn->query("SELECT * FROM bookings WHERE booking_id = $booking_id AND user_id = $user_id")->fetch_assoc();
    if (!$booking) die("Invalid booking.");
    
    // get late fee agian to verify
    $PER_DAY_FEE = 500.00;
    $end_date = $booking['end_date'];
    $days_late = intval((strtotime(date('Y-m-d')) - strtotime($end_date)) / 86400);
    if ($days_late < 0) $days_late = 0;

    $late_amount = $days_late * $PER_DAY_FEE;
    $service_charge = round($late_amount * 0.03, 2);
    $total_due = round($late_amount + $service_charge, 2);

    if ($total_due <= 0) die("No late fee due.");
    if (abs($total_due - $amount) > 0.5) die("Amount mismatch.");

    $payment_success = true;

    // get username of buyer
    $user = $conn->query("SELECT username FROM users WHERE user_id = $user_id")->fetch_assoc();
    $username = $user ? $user['username'] : 'Unknown User';

    if ($payment_success) {
        // Generate unique transaction ID
        $transaction_id = 'TXN-' . strtoupper(uniqid());

        // Insert payment record with transaction ID
        $stmt = $conn->prepare("INSERT INTO payments 
            (booking_id, amount, payment_method, payment_status, transaction_id, payment_type) 
            VALUES (?, ?, 'Card', 'completed', ?, 'late_fee')");
        $stmt->bind_param("ids", $booking_id, $total_due, $transaction_id);
        $stmt->execute();

        // Mark late fee as paid 
        $conn->query("UPDATE bookings SET is_late_paid = 1, late_fee = $late_amount, status='completed' WHERE booking_id = $booking_id");

        // Add notification
        $msg = "Dear {$username}, your late fee payment of Rs.{$total_due} has been successfully completed.";
        $conn->query("INSERT INTO notifications (user_id, booking_id, message, type) VALUES ($user_id, $booking_id, '". $conn->real_escape_string($msg) ."', 'late_fee_paid')");

        echo "<script>alert('Late fee paid successfully.'); window.location='../Frontend/notifications.php';</script>";
        exit;
    } else {
        echo "<script>alert('Payment failed.'); window.history.back();</script>";
        exit;
    }

}
elseif (isset($post['confirm_booking'])) {

    $item_id = intval($_POST['item_id']);
    $price_per_day = floatval($_POST['price_per_day']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // to see user inputs correct date 
    $today = date('Y-m-d');

    if ($start_date < $today) {
    echo "<script>alert('Start date cannot be in the past. Please choose a future date.'); window.history.back();</script>";
    exit();
}
    if ($end_date < $start_date) {
    echo "<script>alert('End date must be after the start date.'); window.history.back();</script>";
    exit();
}
    // Validate dates
    function isAvailable($conn, $item_id, $start_date, $end_date) {
        $query = "SELECT COUNT(*) AS conflicts
                  FROM item_availability
                  WHERE item_id = ?
                  AND available_date BETWEEN ? AND ?
                  AND is_available = 0";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iss", $item_id, $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['conflicts'] == 0; 
    }

    if (!isAvailable($conn, $item_id, $start_date, $end_date)) {
        echo "<script>alert('Sorry, the item is not available for the selected dates.'); window.location='book_item.php?item_id=$item_id';</script>";
        exit();
    }

    // take the count of days
    $days = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24);
    if ($days == 0) $days = 1;

    //measure total payment
    $base_price = $price_per_day * $days;
    $service_charge = round($base_price * 0.03, 2); 
    $total_payment = round($base_price + $service_charge, 2);

    //store payement data
    $_SESSION['payment_data'] = [
        'item_id' => $item_id,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'days' => $days,
        'price_per_day' => $price_per_day,
        'base_price' => $base_price,
        'service_charge' => $service_charge,
        'total_payment' => $total_payment
    ];
    header("Location: ../Frontend/payment_view.php");
    exit();

} else {
    header("Location: all_items.php");
    exit();
}
?>