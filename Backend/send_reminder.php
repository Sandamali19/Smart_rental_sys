<?php

require 'config.php';
session_start();

include_once 'late_fee_handler.php';

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_reminder_booking_id'])){
    $booking_id = intval($_POST['send_reminder_booking_id']);
    
    $booking = $conn->query("SELECT user_id, end_date FROM bookings WHERE booking_id = $booking_id")->fetch_assoc();
    if($booking){
        $buyer_id = intval($booking['user_id']);
            $item = $conn->query("SELECT i.item_name, u.username AS buyer_name 
                        FROM bookings b 
                        JOIN items i ON b.item_id = i.item_id 
                        JOIN users u ON b.user_id = u.user_id 
                        WHERE b.booking_id = $booking_id")->fetch_assoc();

    $item_name = $item['item_name'];
    $buyer_name = $item['buyer_name'];

    $msg = "⏰ Reminder for $buyer_name: Please return '$item_name' (Booking #$booking_id) by {$booking['end_date']}.";

        $conn->query("INSERT INTO notifications (user_id, booking_id, message, type) VALUES ($buyer_id, $booking_id, '". $conn->real_escape_string($msg) ."', 'reminder')");
    }
    echo "<script>
    alert('Reminder sent successfully!');
    window.location.href='user_profile.php';
    </script>";
    exit;

}
echo "Done.";