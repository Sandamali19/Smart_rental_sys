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

    pay_sql = "SELECT p.payment_id, p.amount, p.payment_method, p.payment_status, p.transaction_id, p.payment_date,
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