<?php

require 'config.php';

//one  day fee
$PER_DAY_FEE = 500.00;

$sql = "SELECT booking_id, user_id, end_date
        FROM bookings
        WHERE status = 'confirmed' AND DATE(end_date) < CURDATE() AND is_late_paid = 0";

$res = $conn->query($sql);
if(!$res) { error_log('Late fee query error: '.$conn->error); exit; }

while($b = $res->fetch_assoc()){
    $booking_id = intval($b['booking_id']);
    $user_id = intval($b['user_id']);
    $end_date = $b['end_date'];

    $days_late = intval((strtotime(date('Y-m-d')) - strtotime($end_date))/86400);
    if($days_late <= 0) continue;

    $late_fee_amount = $days_late * $PER_DAY_FEE;

    // Update bookings table with late fee
    $stmt = $conn->prepare("UPDATE bookings SET late_fee = ?, is_late_paid = 0 WHERE booking_id = ?");
    $stmt->bind_param("di", $late_fee_amount, $booking_id);
    $stmt->execute();

    //user name
    $user = $conn->query("SELECT username FROM users WHERE user_id = $user_id")->fetch_assoc();
    $username = $user ? $user['username'] : 'Unknown User';


    // insert notification to buyer 
    $msg = "Dear #{$username},your booking is overdue by {$days_late} day(s). Late fee: Rs.{$late_fee_amount}.";
    $conn->query("INSERT INTO notifications (user_id, booking_id, message, type) VALUES ($user_id, $booking_id, '". $conn->real_escape_string($msg) ."', 'late_fee')");
}


?>