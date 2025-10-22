<?php
require 'config.php';
session_start();
$user_id = intval($_SESSION['user_id']);
$booking_id = intval($_GET['booking_id'] ?? 0);

if(!$booking_id) die("Invalid.");

$booking = $conn->query("SELECT * FROM bookings WHERE booking_id = $booking_id AND user_id = $user_id")->fetch_assoc();
if(!$booking) die("Booking not found.");

//control duplicate return
if ($booking['status'] === 'completed') {
    echo "<script>alert('This item has already been returned.'); window.location='../Frontend/notifications.php';</script>";
    exit;
}

$today = date('Y-m-d');

//Prevent return before start date
if ($today < $booking['start_date']) {
    echo "<script>alert('You cannot return this item before the start date.'); window.location='../Frontend/notifications.php';</script>";
    exit;
}

$user = $conn->query("SELECT username FROM users WHERE user_id = $user_id")->fetch_assoc();
$username = $user ? $user['username'] : 'Unknown User';


if($today <= $booking['end_date']){
    // Normal return - no late fee
    $conn->query("UPDATE bookings SET status='completed', late_fee = 0, is_late_paid = 0 WHERE booking_id = $booking_id");
    $msg = "Dear {$username},You have successfully returned your item on time. Thank you!";
    
    $conn->query("INSERT INTO notifications (user_id, booking_id, message, type) VALUES ($user_id, $booking_id, '". $conn->real_escape_string($msg) ."', 'returned')");
    echo "<script>alert('Returned successfully.'); window.location='../Frontend/notifications.php';</script>";
    exit;
} else {
    // Late => redirect to pay page
    header("Location: pay_late_fee.php?booking_id=$booking_id");
    exit;
}
?>