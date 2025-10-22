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